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
        Schema::create('assembly_constituencies', function (Blueprint $table) {
            $table->id();
            $table->integer('state_id');
            $table->string('name');
            $table->integer('number');
            $table->integer('status')->default(1)->comment('0 => InActive 1 => Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_constituencies');
    }
};
