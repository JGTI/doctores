<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Relación con la tabla users
            $table->string('specialty')->nullable();
            $table->string('license_number')->nullable();
            $table->string('office_address')->nullable();
            $table->string('office_hours')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('additional_contact_info')->nullable();
            $table->string('state')->nullable(); // Estado de la República
            $table->timestamps();

            // Definir la relación con la tabla users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_details');
    }
}
