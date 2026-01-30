<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::latest()->paginate(8);
        return view('admin.skillset', compact('skills'));
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
        ]);
        Skill::create($request->all());
        return redirect()->route('skills.index');
    }


    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
        ]);

        $skill->update($request->all());
        return redirect()->route('skills.index');
    }


    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('skills.index')->with('success', 'Skill deleted successfully.');
    }

}
