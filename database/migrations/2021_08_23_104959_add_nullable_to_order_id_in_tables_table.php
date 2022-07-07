<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToOrderIdInTablesTable extends Migration
{
    // CHANGING DB TO POSTGRES FOR PRODUCTION
    //protected $connection = 'pgsql';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('tables', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->integer('order_id')->change();
        });
    }
}