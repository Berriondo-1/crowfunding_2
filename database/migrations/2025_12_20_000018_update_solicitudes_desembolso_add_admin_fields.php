<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('solicitudes_desembolso', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitudes_desembolso', 'estado_admin')) {
                $table->string('estado_admin', 32)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('solicitudes_desembolso', 'justificacion_admin')) {
                $table->text('justificacion_admin')->nullable()->after('estado_admin');
            }
        });
    }

    public function down(): void {
        Schema::table('solicitudes_desembolso', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_desembolso', 'justificacion_admin')) {
                $table->dropColumn('justificacion_admin');
            }
            if (Schema::hasColumn('solicitudes_desembolso', 'estado_admin')) {
                $table->dropColumn('estado_admin');
            }
        });
    }
};
