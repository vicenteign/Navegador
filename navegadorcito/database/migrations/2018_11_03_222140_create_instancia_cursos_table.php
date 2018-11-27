<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstanciaCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instancia_cursos', function (Blueprint $table) {
            $table->increments('id_instcurso');
            $table->integer('anyo')->unsigned();
            $table->integer('semestre')->unsigned();
            $table->string('sigla_curso');

            /* Foreign Key */
            $table->integer('curso_id_curso')->unsigned()->nullable();
            $table->foreign('curso_id_curso')->references('id_curso')->on('cursos')->onDelete('set null');
            $table->integer('id_profesor_fk')->unsigned()->nullable();
            $table->foreign('id_profesor_fk')->references('id_profesor')->on('profesores')->onDelete('set null');

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
        Schema::dropIfExists('instancia_cursos');
    }
}
