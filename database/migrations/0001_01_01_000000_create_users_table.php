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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('salutation')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->integer('gender')->nullable()->comment('1 => Female 2 => Male 3 => Other');
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('assembly_id')->nullable();
            $table->integer('religion_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('caste')->nullable();
            $table->integer('education_id')->nullable();
            $table->integer('profession_id')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('relationship_name')->nullable();
            $table->string('landline_number')->nullable();
            $table->integer('zila_id')->nullable();
            $table->integer('mandal_id')->nullable();
            $table->string('ward_name')->nullable();
            $table->integer('booth_id')->nullable();
            $table->string('referral_code')->nullable();
            $table->integer('referred_user_id')->nullable();
            $table->integer('is_details_filled')->default(0);
            $table->string('membership_number')->nullable();
            $table->integer('status')->default(1)->comment('0 => InActive 1 => Active');
            $table->string('image')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
