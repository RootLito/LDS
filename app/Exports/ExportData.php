<?php
namespace App\Exports;

use App\Models\Training;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class ExportData implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Training::query();

        if (!empty($this->filters['title'])) {
            $query->where('title', 'like', '%' . $this->filters['title'] . '%');
        }

        if (!empty($this->filters['applicable_for'])) {
            $query->where('applicable_for', $this->filters['applicable_for']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $trainings = $query->latest()->get();

        // Add nominees (employees who have NOT attended this training)
        foreach ($trainings as $training) {
            $nominees = Employee::whereDoesntHave('trainingsAttended', function ($q) use ($training) {
                $q->where('title', $training->title);
            })->get();

            $training->nominees = $nominees;
            $training->number_of_nominees = $nominees->count();
        }

        return $trainings;
    }

    public function headings(): array
    {
        return [
            'TITLE',
            'STATUS',
            'DURATION',
            'CONDUCTED BY / FACILITATOR',
            'CHARGING OF FUNDS',
            'NAME OF NOMINEES / PARTICIPANTS',
            'NUMBER OF NOMINEES / PARTICIPANTS',
            'ENDORSED / RECOMMENDED BY',
            'HRDC RESOLUTION NO.'
        ];
    }

    public function map($training): array
    {
        // Join nominees' full names by newline
        $nomineesNames = $training->nominees->pluck('fullname')->implode("\n");

        return [
            $training->title,
            $training->status,
            $training->duration,
            $training->conducted_by,
            $training->charging_of_funds,
            $nomineesNames,
            $training->number_of_nominees,
            $training->endorsed_by,
            $training->hrdc_resolution_no,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', 'Learning and Development System');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
