<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('username');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('contact')->unique();
                $table->string('gender')->enum('Female', 'Male', '');
                $table->timestamp('dob');
                $table->integer('points')->default(0);
                $table->integer('m_score')->default(0);
                $table->integer('r_score')->default(0);
                $table->integer('f_score')->default(0);
                $table->string('c_segment')->default("NA");
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
};
