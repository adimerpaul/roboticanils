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
        Schema::create('cupos', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->nullable();
            $table->string('nombres')->nullable();
            $table->string('carrera')->nullable();
            $table->string('foto')->nullable()->default("avatar.png");
            $table->string('celular')->nullable();
            $table->string('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('codigo')->nullable();
            $table->boolean('credencial')->nullable()->default(false);
            $table->boolean('folder')->nullable()->default(false);
            $table->boolean('barbijo')->nullable()->default(false);
            $table->boolean('certificado')->nullable()->default(false);
            $table->string('tipo')->default('PARTICIPANTE');
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
        Schema::dropIfExists('cupos');
    }
};
