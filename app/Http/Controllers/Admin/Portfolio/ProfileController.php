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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about_text' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'social_links' => 'nullable|array',
        ]);

        $profile = PortfolioProfile::firstOrFail();
        
        $data = $request->except('profile_image');

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($profile->profile_image && \Storage::disk('public')->exists($profile->profile_image)) {
                \Storage::disk('public')->delete($profile->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile', 'public');
            $data['profile_image'] = $path;
        }

        $profile->update($data);

        return redirect()->route('admin.portfolio.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
