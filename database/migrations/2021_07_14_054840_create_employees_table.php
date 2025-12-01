<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('company_id')->nullable();
                $table->string('name', 100);
                $table->string('email', 255);
                $table->string('phone', 50)->nullable();
                $table->string('address')->nullable();
                $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
                $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
                $table->text('company')->nullable();
                $table->text('gender')->nullable();
                $table->date('birth_date')->nullable();
                $table->date('join_date')->nullable();
                $table->date('probation_end')->nullable();
                $table->date('regularization_date')->nullable();
                $table->date('training_date')->nullable();
                $table->string('avatar', 255)->nullable();
                $table->decimal('salary', 10, 2)->comment('Base monthly salary')->nullable();
                $table->string('sss_number', 50)->nullable();
                $table->string('philhealth_number', 50)->nullable();
                $table->string('pagibig_number', 50)->nullable();
                $table->string('tin_number', 50)->nullable();
                $table->timestamps();
            });
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
