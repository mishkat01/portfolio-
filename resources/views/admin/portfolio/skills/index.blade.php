@extends('layouts.admin')

@section('header', 'Skills')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">All Skills</h3>
            <a href="{{ route('admin.portfolio.skills.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                + Add Skill
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($skills as $skill)
            <div class="border dark:border-gray-700 rounded-lg p-4 flex justify-between items-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1" style="background-color: {{ $skill->color }}"></div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-white">{{ $skill->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $skill->proficiency }}% Proficiency</p>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('admin.portfolio.skills.edit', $skill) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                    <form action="{{ route('admin.portfolio.skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                No skills found. Start adding some!
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
