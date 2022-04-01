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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('medico_id')->unsigned()->nullable();
            $table->bigInteger('paciente_id')->unsigned()->nullable();
            $table->dateTime('data');
            $table->timestamps();

            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultas');
    }
};
