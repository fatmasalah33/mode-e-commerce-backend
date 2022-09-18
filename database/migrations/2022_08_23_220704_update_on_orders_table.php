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
            DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM('confirmed','shipped','cancelld','delivered', 'pending','not completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not completed';");
            $table->unsignedBigInteger('payment_id')->nullable()->change();
            $table->dropColumn('comment');
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
                    $table->string('comment');
                     $table->unsignedBigInteger('payment_id')->nullable(false)->change();
         });
    }
};
