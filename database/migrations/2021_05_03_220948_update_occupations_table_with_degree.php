<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOccupationsTableWithDegree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('occupations', function (Blueprint $table) {
            $table->foreignId('degree_id')->nullable()
                ->constrained('degrees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('occupations', function (Blueprint $table) {
            $table->dropColumn(['degree_id']);
        });
    }
}
