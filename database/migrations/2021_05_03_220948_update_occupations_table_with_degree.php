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
            $table->foreignId('company_id')->default(1)
                ->constrained('companies')
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
            $table->dropForeign('occupations_degree_id_foreign');
            $table->dropForeign('occupations_company_id_foreign');
        });
    }
}
