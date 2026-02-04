<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('sort_order')->get();
        return view('admin.portfolio.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.portfolio.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|url',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'nullable|string', // Comma separated for now
            'type_3d' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $project = new Project($request->all());
        $project->slug = Str::slug($request->title);
        
        // Handle tech stack conversion from comma-string to array
        if ($request->tech_stack) {
            $project->tech_stack = array_map('trim', explode(',', $request->tech_stack));
        }

        $project->save();

        return redirect()->route('admin.portfolio.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.portfolio.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|url',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'nullable|string',
            'type_3d' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $project->fill($request->except('tech_stack'));
        $project->slug = Str::slug($request->title);

        if ($request->tech_stack) {
            $project->tech_stack = array_map('trim', explode(',', $request->tech_stack));
        } else {
            $project->tech_stack = [];
        }

        $project->save();

        return redirect()->route('admin.portfolio.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.portfolio.projects.index')->with('success', 'Project deleted successfully.');
    }
}
