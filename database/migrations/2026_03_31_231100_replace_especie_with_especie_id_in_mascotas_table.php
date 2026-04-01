<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            if (!Schema::hasColumn('mascotas', 'especie_id')) {
                $table->foreignId('especie_id')->nullable()->after('nombre')->constrained('especies')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('mascotas', 'especie')) {
            $especies = DB::table('mascotas')
                ->whereNotNull('especie')
                ->where('especie', '!=', '')
                ->distinct()
                ->pluck('especie');

            foreach ($especies as $nombre) {
                DB::table('especies')->updateOrInsert(['nombre' => $nombre], ['updated_at' => now(), 'created_at' => now()]);
            }

            $mascotas = DB::table('mascotas')->select('id', 'especie')->get();
            foreach ($mascotas as $mascota) {
                if (!$mascota->especie) {
                    continue;
                }

                $especie = DB::table('especies')->where('nombre', $mascota->especie)->first();
                if ($especie) {
                    DB::table('mascotas')->where('id', $mascota->id)->update(['especie_id' => $especie->id]);
                }
            }

            Schema::table('mascotas', function (Blueprint $table) {
                $table->dropColumn('especie');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('mascotas', 'especie')) {
            Schema::table('mascotas', function (Blueprint $table) {
                $table->string('especie', 50)->nullable()->after('nombre');
            });
        }

        $mascotas = DB::table('mascotas')->select('id', 'especie_id')->get();
        foreach ($mascotas as $mascota) {
            if (!$mascota->especie_id) {
                continue;
            }

            $especie = DB::table('especies')->where('id', $mascota->especie_id)->first();
            if ($especie) {
                DB::table('mascotas')->where('id', $mascota->id)->update(['especie' => $especie->nombre]);
            }
        }

        Schema::table('mascotas', function (Blueprint $table) {
            if (Schema::hasColumn('mascotas', 'especie_id')) {
                $table->dropConstrainedForeignId('especie_id');
            }
        });
    }
};
