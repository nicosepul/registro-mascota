<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normaliza registros antiguos: cualquier edad negativa pasa a 0.
        DB::table('mascotas')->where('edad', '<', 0)->update(['edad' => 0]);

        // Intenta reforzar regla a nivel DB sin romper si ya existe una constraint similar.
        $driver = DB::getDriverName();
        try {
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement('ALTER TABLE mascotas ADD CONSTRAINT chk_mascotas_edad_no_negativa_20260331 CHECK (edad >= 0)');
            }
        } catch (Throwable $e) {
            // Ignorar si la constraint ya existe o el motor no soporta agregarla en este contexto.
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        try {
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement('ALTER TABLE mascotas DROP CONSTRAINT chk_mascotas_edad_no_negativa_20260331');
            }
        } catch (Throwable $e) {
            // No-op en rollback si no existe la constraint.
        }
    }
};
