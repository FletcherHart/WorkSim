<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('money')->default(0);
            $table->unsignedTinyInteger('intelligence')->default(1);
            $table->unsignedTinyInteger('fitness')->default(1);
            $table->unsignedTinyInteger('charisma')->default(1);
            $table->unsignedTinyInteger('current_energy')->default(20);
            $table->unsignedTinyInteger('max_energy')->default(20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['money', 'intelligence', 'fitness', 'charisma', 'current_energy', 'max_energy']);
        });
    }
}
