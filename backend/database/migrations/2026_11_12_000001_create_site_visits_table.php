<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->date('visited_date')->index();
            $table->string('visitor_hash', 64)->index();
            $table->string('path', 255)->default('/');
            $table->string('referrer_host', 150)->nullable();
            $table->string('device_type', 20)->default('desktop');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
