<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        return view('admin.portfolio.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.portfolio.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'proficiency' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:7', // Hex color #ffffff
        ]);

        Skill::create($request->all());

        return redirect()->route('admin.portfolio.skills.index')->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.portfolio.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'proficiency' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:7',
        ]);

        $skill->update($request->all());

        return redirect()->route('admin.portfolio.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.portfolio.skills.index')->with('success', 'Skill deleted successfully.');
    }
}
