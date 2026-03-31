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
        Schema::create('duenos', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 12)->unique();
            $table->string('nombre', 80);
            $table->string('apellido', 80);
            $table->string('telefono', 12);
            $table->string('direccion', 150);
            $table->timestamps();
        });

        $driver = DB::getDriverName();

        // Enforce data format at DB layer for supported drivers.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE duenos ADD CONSTRAINT chk_duenos_rut_format CHECK (rut REGEXP '^[0-9]{7,8}[0-9Kk]$')");
            DB::statement("ALTER TABLE duenos ADD CONSTRAINT chk_duenos_telefono_format CHECK (telefono REGEXP '^(\\+56|56)?9[0-9]{8}$')");
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE duenos ADD CONSTRAINT chk_duenos_rut_format CHECK (rut ~ '^[0-9]{7,8}[0-9Kk]$')");
            DB::statement("ALTER TABLE duenos ADD CONSTRAINT chk_duenos_telefono_format CHECK (telefono ~ '^(\\+56|56)?9[0-9]{8}$')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duenos');
    }
};
