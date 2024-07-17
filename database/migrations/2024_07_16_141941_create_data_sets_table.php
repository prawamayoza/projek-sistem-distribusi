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
        Schema::create('data_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribusi_id');
            $table->unsignedBigInteger('from_customer');
            $table->unsignedBigInteger('to_customer');
            $table->float('distance');
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('distribusi_id')->references('id')->on('distribusis')->onDelete('cascade');
            $table->foreign('from_customer')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->foreign('to_customer')->references('id')->on('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sets');
    }
};
