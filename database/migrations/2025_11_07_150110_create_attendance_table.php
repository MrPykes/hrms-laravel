<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();

            // Foreign key to employee
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // Date of the attendance
            $table->date('attendance_date');

            // Punch in/out times
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();

            // Hours computed
            $table->decimal('late_in', 5, 2)->nullable()->comment('Total late hours');
            $table->time('early_out')->nullable()->comment('Total early out hours');
            $table->decimal('production_hours', 5, 2)->nullable()->comment('Total working hours');
            $table->decimal('break_hours', 5, 2)->nullable()->comment('Total break hours');
            $table->decimal('overtime_hours', 5, 2)->nullable()->comment('Total overtime hours');

            // Optional daily summary
            $table->string('status', 50)->nullable()->comment('e.g., Present, Absent, Late');

            // Timestamps
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
        Schema::dropIfExists('attendance');
    }
}
