<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
             $table->id();

            // Expense info
            $table->string('item');                 // Dell Laptop, Mac System
            $table->string('purchase_from');        // Amazon
            $table->date('purchase_date');

            // Relationships
            $table->foreignId('purchased_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Financial
            $table->decimal('amount', 12, 2);

            // Payment
            $table->enum('paid_by', ['cash', 'cheque', 'bank', 'other']);

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            // Optional notes
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('expense_reports');
    }
}
