<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdAndStatusToProvidersTable extends Migration
{
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->after('id'); // Relación con el doctor
            $table->boolean('status')->default(true)->after('email'); // Columna de activo/inactivo

            // Llave foránea con la tabla de doctores (users)
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
            $table->dropColumn('status');
        });
    }
}
