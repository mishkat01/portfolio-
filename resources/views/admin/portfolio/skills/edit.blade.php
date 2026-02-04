@extends('layouts.admin')

@section('header', 'Edit Skill: ' . $skill->name)

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        <form action="{{ route('admin.portfolio.skills.update', $skill) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $skill->name) }}" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="proficiency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proficiency (0-100)</label>
                    <input type="number" name="proficiency" id="proficiency" value="{{ old('proficiency', $skill->proficiency) }}" min="0" max="100" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color (Hex)</label>
                    <div class="flex">
                        <input type="color" id="color" name="color" value="{{ old('color', $skill->color) }}" class="h-10 w-10 border border-gray-300 rounded mr-2 p-1">
                        <input type="text" name="color" value="{{ old('color', $skill->color) }}" class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.portfolio.skills.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-3 hover:bg-gray-300 transition">Cancel</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Update Skill
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
