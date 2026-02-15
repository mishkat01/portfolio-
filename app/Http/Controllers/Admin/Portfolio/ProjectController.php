<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'thumbnail_url' => 'nullable', // Can be a URL or an Image File
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'nullable|string', // Comma separated for now
            'type_3d' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['thumbnail_url', 'tech_stack']);

        if ($request->hasFile('thumbnail_url')) {
            $path = $request->file('thumbnail_url')->store('projects', 'public');
            $data['thumbnail_url'] = $path;
        }
        elseif ($request->thumbnail_url) {
            $data['thumbnail_url'] = $request->thumbnail_url;
        }

        $project = new Project($data);
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
            'thumbnail_url' => 'nullable',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'nullable|string',
            'type_3d' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['thumbnail_url', 'tech_stack']);

        if ($request->hasFile('thumbnail_url')) {
            // Delete old image if it was a file
            if ($project->thumbnail_url && !\Str::startsWith($project->thumbnail_url, ['http://', 'https://'])) {
                if (\Storage::disk('public')->exists($project->thumbnail_url)) {
                    \Storage::disk('public')->delete($project->thumbnail_url);
                }
            }

            $path = $request->file('thumbnail_url')->store('projects', 'public');
            $data['thumbnail_url'] = $path;
        }
        elseif ($request->filled('thumbnail_url_text')) {
            // If a manual URL was provided in a separate field (we'll add this to the view)
            $data['thumbnail_url'] = $request->thumbnail_url_text;
        }

        $project->fill($data);
        $project->slug = Str::slug($request->title);

        if ($request->tech_stack) {
            $project->tech_stack = array_map('trim', explode(',', $request->tech_stack));
        }
        else {
            $project->tech_stack = [];
        }

        $project->save();

        return redirect()->route('admin.portfolio.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Delete thumbnail if it's a stored file
        if ($project->thumbnail_url && !\Str::startsWith($project->thumbnail_url, ['http://', 'https://'])) {
            if (\Storage::disk('public')->exists($project->thumbnail_url)) {
                \Storage::disk('public')->delete($project->thumbnail_url);
            }
        }

        $project->delete();
        return redirect()->route('admin.portfolio.projects.index')->with('success', 'Project deleted successfully.');
    }
}
