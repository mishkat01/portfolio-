<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_url',
        'project_url',
        'github_url',
        'tech_stack',
        'type_3d',
        'sort_order',
    ];

    protected $casts = [
        'tech_stack' => 'array',
    ];
}
