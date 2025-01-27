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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('parent_id')->nullable()->after('id');
            $table->integer('relationship_id')->nullable()->after('parent_id');
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('blood_group')->nullable()->after('gender');
            $table->dropColumn('caste');
            $table->integer('caste_id')->nullable()->after('category_id');
            $table->integer('color_id')->nullable()->after('profession_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumns(['parent_id', 'first_name', 'last_name', 'blood_group', 'color_id']);
            $table->dropColumn('caste_id');
            $table->string('caste')->nullable()->after('category_id');
        });
    }
};
