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
        Schema::table('copouns', function (Blueprint $table) {
            $table->enum('status',['available','notavailable'])->default('available');
            $table->date('end_at')->default(now()->addMonth());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('copouns', function (Blueprint $table) {
            $table->dropColumn('end_at');
            $table->dropColumn('status');
        });
    }
};
