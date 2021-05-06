<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupationRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupation_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occupation_id')
                ->constrained('occupations')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('intelligence')->default(0);
            $table->unsignedTinyInteger('fitness')->default(0);
            $table->unsignedTinyInteger('charisma')->default(0);
            $table->timestamps();
            $table->unique(['occupation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('occupation_requirements');
    }
}
