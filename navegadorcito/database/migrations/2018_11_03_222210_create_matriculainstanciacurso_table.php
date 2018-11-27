<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatriculainstanciacursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculainstanciacurso', function (Blueprint $table) {
            $table->increments('id_matrinstcurso');
            $table->integer('anyo');
            $table->integer('semestre');
            $table->string('rut');
            $table->string('estado')->default('Valida')->nullable();
            $table->string('sigla')->nullable();

            /* Foreign Key */
            $table->integer('alumnos_id_alumno')->unsigned()->nullable();
            $table->integer('estadomatricula_id')->unsigned()->nullable();
            $table->integer('instancia_cursos_id_instcurso')->unsigned()->nullable();
            $table->foreign('alumnos_id_alumno')->references('id_alumno')->on('alumnos')->onDelete('cascade');
            $table->foreign('estadomatricula_id')->references('id')->on('estadomatricula')->onDelete('set null');
            $table->foreign('instancia_cursos_id_instcurso')->references('id_instcurso')->on('instancia_cursos')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matriculainstanciacurso');
    }
}
