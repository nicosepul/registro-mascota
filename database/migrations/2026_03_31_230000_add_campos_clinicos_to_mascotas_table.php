<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->string('especie', 50)->nullable()->after('nombre');
            $table->enum('sexo', ['Macho', 'Hembra'])->nullable()->after('especie');
            $table->date('fecha_nacimiento')->nullable()->after('sexo');
            $table->decimal('peso', 6, 2)->nullable()->after('fecha_nacimiento');
            $table->string('color', 80)->nullable()->after('peso');
            $table->string('procedencia', 120)->nullable()->after('color');
            $table->text('senales_particulares')->nullable()->after('edad');
        });
    }

    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropColumn([
                'especie',
                'sexo',
                'fecha_nacimiento',
                'peso',
                'color',
                'procedencia',
                'senales_particulares',
            ]);
        });
    }
};
