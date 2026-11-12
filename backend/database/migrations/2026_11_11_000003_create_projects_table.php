<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('tab', 20)->index(); // website | aplikasi | desain
            $table->string('title', 160);
            $table->string('category', 120);
            $table->text('description');
            $table->text('long_description')->nullable();
            $table->json('tags')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('img', 500);
            $table->string('img_object_position', 40)->default('50% 50%');
            $table->boolean('featured')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
