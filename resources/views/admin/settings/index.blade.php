@extends('layouts.admin')

@section('header', 'Global Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
        <p class="text-gray-600 dark:text-gray-400 mb-6">Manage global application settings. These changes affect the entire system.</p>
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 dark:bg-gray-50 bg-gray-750 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Registration</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Allow new users to register an account.</p>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="user_registration" id="user_registration" class="sr-only peer" {{ ($settings['user_registration'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 dark:bg-gray-50 bg-gray-750 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Email Verification</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Require users to verify their email address before accessing the dashboard.</p>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_verification" id="email_verification" class="sr-only peer" {{ ($settings['email_verification'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
