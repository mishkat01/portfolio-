@extends('layouts.admin')

@section('header', 'Admins Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-600 dark:text-gray-400">Manage administrator accounts and role assignments.</p>
    <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
        + Create Admin
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($admins as $admin)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $admin->email }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex flex-wrap gap-1">
                        @foreach($admin->roles as $role)
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full dark:bg-indigo-900 dark:text-indigo-200">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                    @if($admin->id !== auth('admin')->id())
                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ml-2">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
