<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('hero_top_text', 100)->default('WELCOME TO MY');
            $table->string('hero_bottom_text', 100)->default('PORTOFOLIO');
            $table->string('greeting', 80)->default("Hi, I'm");
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name', 180);
            $table->text('bio');
            $table->string('resume_url', 500)->nullable();
            $table->string('email', 150);
            $table->string('location', 150);
            $table->string('avatar', 500)->default('/profile.png');
            $table->string('avatar_reveal', 500)->default('/PhotoBlackPanther.png');
            $table->string('contact_photo', 500)->default('/profile.jpg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_profiles');
    }
};
