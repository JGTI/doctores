<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneAndEmailToPatientInformationTable extends Migration
{
    public function up()
    {
        Schema::table('patient_information', function (Blueprint $table) {
            $table->string('phone')->nullable(); // Teléfono del paciente, puede ser nulo
            $table->string('email')->nullable(); // Correo electrónico del paciente, puede ser nulo
        });
    }

    public function down()
    {
        Schema::table('patient_information', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('email');
        });
    }
}
