@extends('layouts.admin')

@section('header', 'Add New Project')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        <form action="{{ route('admin.portfolio.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                
                {{-- Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="type_3d" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">3D Object Type</label>
                        <select name="type_3d" id="type_3d" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="planet_blue">Blue Planet</option>
                            <option value="planet_red">Red Planet</option>
                            <option value="planet_ring">Ringed Planet</option>
                            <option value="monolith">Monolith</option>
                            <option value="star_cluster">Star Cluster</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">This determines the 3D model shown in the scene.</p>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

                {{-- Image / Thumbnail Section --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Project Thumbnail</label>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                    <img id="image_preview" class="h-24 w-40 object-cover rounded shadow-sm" 
                                         src="https://via.placeholder.com/400x225?text=Preview" 
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
                            <input type="text" name="thumbnail_url" id="thumbnail_url_manual" value="{{ old('thumbnail_url') }}" placeholder="https://..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">If both are provided, the uploaded file takes precedence.</p>
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
                        <input type="url" name="project_url" id="project_url" value="{{ old('project_url') }}" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="github_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GitHub URL</label>
                        <input type="url" name="github_url" id="github_url" value="{{ old('github_url') }}" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Tech Stack --}}
                <div>
                    <label for="tech_stack" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tech Stack (Comma Separated)</label>
                    <input type="text" name="tech_stack" id="tech_stack" value="{{ old('tech_stack') }}" placeholder="Laravel, Vue, Tailwind"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                 <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.portfolio.projects.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-3 hover:bg-gray-300 transition">Cancel</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Create Project
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
