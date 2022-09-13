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
        DB::statement("CREATE Table customers (
            id int NOT NULL AUTO_INCREMENT,
            last_name varchar(255) NOT NULL,
            first_name varchar(255) NOT NULL,
            gender char(1),
            country varchar(255) NOT NULL,
            email varchar(255) NOT NULL UNIQUE,
            bonus int,
            PRIMARY KEY (id)
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
        DB::statement("DROP TABLE customers");
    }
};
