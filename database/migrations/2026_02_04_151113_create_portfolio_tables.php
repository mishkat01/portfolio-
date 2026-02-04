<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('portfolio_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Welcome to My Universe');
            $table->string('subtitle')->nullable();
            $table->text('about_text')->nullable();
            $table->string('resume_url')->nullable();
            $table->json('social_links')->nullable(); // {github: 'url', linkedin: 'url'}
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('project_url')->nullable();
            $table->string('github_url')->nullable();
            $table->json('tech_stack')->nullable(); // ['Laravel', 'Vue', 'AWS']
            $table->string('type_3d')->default('planet_blue'); // planet_red, monolith, star, etc.
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('proficiency')->default(50); // 0-100
            $table->string('color')->default('#ffffff');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('portfolio_profiles');
    }
};
