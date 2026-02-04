<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'subtitle',
        'about_text',
        'resume_url',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
