<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Get the first profile or create a default one
        $profile = PortfolioProfile::firstOrCreate(
            ['id' => 1],
            ['hero_title' => 'Welcome', 'subtitle' => 'Creative Developer']
        );

        return view('admin.portfolio.profile', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'social_links' => 'nullable|array',
        ]);

        $profile = PortfolioProfile::firstOrFail();
        
        $profile->update($request->all());

        return redirect()->route('admin.portfolio.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
