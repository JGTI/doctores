<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientInformationTable extends Migration
{
    public function up()
    {
        Schema::create('patient_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id'); // Doctor a cargo
            $table->string('patient_name'); // Nombre del paciente
            $table->string('curp')->nullable(); // CURP del paciente, puede ser nulo
            $table->string('rfc')->nullable(); // RFC del paciente, puede ser nulo
            $table->string('address')->nullable(); // Dirección del paciente
            $table->date('dob')->nullable(); // Fecha de nacimiento
            $table->string('gender')->nullable(); // Género
            $table->text('medical_history')->nullable(); // Historial médico
            $table->text('allergies')->nullable(); // Alergias
            $table->text('current_medication')->nullable(); // Medicamentos actuales
            $table->timestamps();

            // Llave foránea que refiere al doctor
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_information');
    }
}
