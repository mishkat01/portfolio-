@extends('layouts.admin')

@section('header', 'Edit Project: ' . $project->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        <form action="{{ route('admin.portfolio.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                
                {{-- Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="type_3d" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">3D Object Type</label>
                        <select name="type_3d" id="type_3d" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="planet_blue" {{ old('type_3d', $project->type_3d) == 'planet_blue' ? 'selected' : '' }}>Blue Planet</option>
                            <option value="planet_red" {{ old('type_3d', $project->type_3d) == 'planet_red' ? 'selected' : '' }}>Red Planet</option>
                             <option value="planet_ring" {{ old('type_3d', $project->type_3d) == 'planet_ring' ? 'selected' : '' }}>Ringed Planet</option>
                            <option value="monolith" {{ old('type_3d', $project->type_3d) == 'monolith' ? 'selected' : '' }}>Monolith</option>
                            <option value="star_cluster" {{ old('type_3d', $project->type_3d) == 'star_cluster' ? 'selected' : '' }}>Star Cluster</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                </div>

                {{-- Image / Thumbnail Section --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Project Thumbnail</label>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                    <img id="image_preview" class="h-24 w-40 object-cover rounded shadow-sm" 
                                         src="{{ $project->thumbnail_url ? (Str::startsWith($project->thumbnail_url, ['http://', 'https://']) ? $project->thumbnail_url : asset('storage/' . $project->thumbnail_url)) : 'https://via.placeholder.com/400x225?text=No+Image' }}" 
                                         alt="Thumbnail preview">
                                </div>
                                <label class="block">
                                    <span class="sr-only">Choose thumbnail photo</span>
                                    <input type="file" name="thumbnail_url" id="thumbnail_url" onchange="previewImage(event)"
                                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-indigo-50 file:text-indigo-700
                                                  hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-400">
                                </label>
                            </div>
                            @error('thumbnail_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="thumbnail_url_manual" class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Or Use External URL</label>
                            <input type="text" name="thumbnail_url_text" id="thumbnail_url_manual" value="{{ old('thumbnail_url_text', (Str::startsWith($project->thumbnail_url, ['http://', 'https://']) ? $project->thumbnail_url : '')) }}" placeholder="https://..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Leave empty if you've uploaded a file. Manual URL takes precedence if no file is selected.</p>
                        </div>
                     </div>
                </div>

                <script>
                    function previewImage(event) {
                        const input = event.target;
                        const preview = document.getElementById('image_preview');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                </script>

                {{-- URLs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live Demo URL</label>
                        <input type="url" name="project_url" id="project_url" value="{{ old('project_url', $project->project_url) }}" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="github_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GitHub URL</label>
                        <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $project->github_url) }}" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Tech Stack --}}
                <div>
                    <label for="tech_stack" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tech Stack (Comma Separated)</label>
                    <input type="text" name="tech_stack" id="tech_stack" value="{{ old('tech_stack', implode(', ', $project->tech_stack ?? [])) }}" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                 <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order) }}" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.portfolio.projects.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-3 hover:bg-gray-300 transition">Cancel</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Update Project
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
