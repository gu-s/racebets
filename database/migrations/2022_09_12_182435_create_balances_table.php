<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::statement("CREATE Table balances (
            id int NOT NULL AUTO_INCREMENT,
            customer_id int NOT NULL UNIQUE,
            amount int,
            deposit_amount int,
            bonus_amount int,
            PRIMARY KEY (id),
            FOREIGN KEY (customer_id) REFERENCES customers(id)
        )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TABLE balances");
    }
};
