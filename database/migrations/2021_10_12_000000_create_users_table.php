<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            $table->string('username');
            // $table->string('name');
            $table->string('email');
            // $table->string('rec_id');
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->string('role');
            $table->foreignId('role_id')->nullable()->constrained('role_type_users')->onDelete('cascade');
            $table->foreignId('status_id')->nullable()->constrained('user_types')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
