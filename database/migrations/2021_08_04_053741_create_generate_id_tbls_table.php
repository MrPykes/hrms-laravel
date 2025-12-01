<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateIdTblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ensure `rec_id` column exists on users before creating the trigger
        if (!Schema::hasColumn('employees', 'id')) {
            Schema::table('employees', function (Blueprint $table) {
                // Make it nullable here to avoid breaking existing inserts; application code
                // can enforce non-null later or fill values via the trigger.
                $table->string('company_id')->nullable()->after('name');
            });
        }

        DB::unprepared('
            CREATE TRIGGER id_store BEFORE INSERT ON employees FOR EACH ROW
            BEGIN
                INSERT INTO sequence_tbls VALUES (NULL);
                SET NEW.company_id = CONCAT("CREO_", LPAD(LAST_INSERT_ID(), 10, "0"));
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::unprepared('DROP TRIGGER "id_store"');
         // Note: we intentionally do not drop the `rec_id` column here in case other
         // migrations or data depend on it. Remove the column manually if desired.
    }
}
