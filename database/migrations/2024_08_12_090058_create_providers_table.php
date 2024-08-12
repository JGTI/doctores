<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del proveedor
            $table->string('contact_name')->nullable(); // Nombre del contacto principal
            $table->string('phone')->nullable(); // Teléfono
            $table->string('email')->nullable(); // Correo electrónico
            $table->text('address')->nullable(); // Dirección
            $table->string('rfc')->nullable(); // RFC
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
