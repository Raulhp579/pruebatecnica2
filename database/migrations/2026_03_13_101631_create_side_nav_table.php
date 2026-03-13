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
        Schema::create('side_nav', function (Blueprint $table) {
            $table->id();
            $table->text("text")->nullable();
            $table->text("header")->nullable();
            $table->text("url")->nullable();
            $table->text('icon')->nullable();
            $table->text('id_html')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('side_nav');
    }
};
