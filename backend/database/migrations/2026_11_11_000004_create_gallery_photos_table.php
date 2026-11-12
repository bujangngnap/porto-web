<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->string('category', 100);
            $table->string('src', 500);
            $table->string('aspect_class', 40)->default('aspect-[4/5]');
            $table->float('width_scale')->default(1);
            $table->string('object_position', 40)->default('50% 50%');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
