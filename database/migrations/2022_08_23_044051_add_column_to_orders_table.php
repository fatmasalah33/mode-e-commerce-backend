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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address_street');
            $table->dropColumn('address_city');
            $table->dropColumn('address_state');
            $table->dropColumn('phone');
            $table->dropColumn('name');
            $table->foreignId('buyeraddresse_id')->constrained()->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['buyeraddresse_id']);
            $table->string('name') ;
            $table->string('phone') ;
            $table->string('address_state');
            $table->string('address_city');
            $table->string('address_street');
        });
    }
};
