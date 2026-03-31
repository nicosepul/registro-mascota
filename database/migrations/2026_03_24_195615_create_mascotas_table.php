<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dueno_id')->constrained('duenos')->onDelete('cascade');
            $table->foreignId('raza_id')->constrained('razas')->onDelete('restrict');
            $table->string('nombre', 80);
            $table->unsignedTinyInteger('edad');
            $table->timestamps();
        });

        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'pgsql') {
            DB::statement('ALTER TABLE mascotas ADD CONSTRAINT chk_mascotas_edad_rango CHECK (edad BETWEEN 0 AND 40)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
