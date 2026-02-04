@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Stat Card 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Admins</h4>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Admin::count() }}</div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Users</h4>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</div> <!-- Placeholder until User model populated -->
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Welcome, {{ Auth::guard('admin')->user()->name }}!</h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            You are logged in as <span class="font-semibold">{{ Auth::guard('admin')->user()->roles->first()->name ?? 'Admin' }}</span>.
        </p>
    </div>
    <div class="p-6">
        <p class="text-gray-700 dark:text-gray-300">
            This is your admin dashboard. You can manage users, roles, and other settings from here.
        </p>

        <div class="mt-6">
            <h4 class="font-medium text-gray-900 dark:text-white mb-4">Your Permissions:</h4>
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach(Auth::guard('admin')->user()->roles->first()->permissions as $permission)
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full border border-gray-200 dark:border-gray-600">
                        {{ $permission->name }}
                    </span>
                @endforeach
            </div>

            @if(Auth::guard('admin')->user()->hasRole('super-admin'))
            <h4 class="font-medium text-gray-900 dark:text-white mb-4">Quick Actions (Super Admin):</h4>
            <div class="flex gap-4">
                <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Add New Admin
                </a>
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Add New Role
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
