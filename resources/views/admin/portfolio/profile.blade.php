@extends('layouts.admin')

@section('header', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.portfolio.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                
                {{-- Hero Section --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Hero Section</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-4">
                            <div>
                                <label for="hero_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Title</label>
                                <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $profile->hero_title) }}" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('hero_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
                                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $profile->subtitle) }}" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <img id="image_preview" class="h-24 w-24 object-cover rounded-full border-2 border-indigo-500 shadow-md" 
                                         src="{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->hero_title) . '&background=6366f1&color=fff' }}" 
                                         alt="Profile photo">
                                </div>
                                <label class="block">
                                    <span class="sr-only">Choose profile photo</span>
                                    <input type="file" name="profile_image" id="profile_image" onchange="previewImage(event)"
                                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-indigo-50 file:text-indigo-700
                                                  hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-400">
                                </label>
                            </div>
                            @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

                {{-- About Section --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">About Section</h3>
                    
                    <div>
                        <label for="about_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">About Text</label>
                        <textarea name="about_text" id="about_text" rows="5" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('about_text', $profile->about_text) }}</textarea>
                    </div>

                    <div class="mt-4">
                        <label for="resume_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resume Link (URL)</label>
                        <input type="url" name="resume_url" id="resume_url" value="{{ old('resume_url', $profile->resume_url) }}" placeholder="https://..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Social Links --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Social Links</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LinkedIn URL</label>
                            <input type="url" name="social_links[linkedin]" id="social_linkedin" value="{{ old('social_links.linkedin', $profile->social_links['linkedin'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="social_github" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GitHub URL</label>
                            <input type="url" name="social_links[github]" id="social_github" value="{{ old('social_links.github', $profile->social_links['github'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Save Changes
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
