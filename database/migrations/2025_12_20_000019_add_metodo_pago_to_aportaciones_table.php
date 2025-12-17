<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aportaciones', function (Blueprint $table) {
            $table->string('metodo_pago')->nullable()->after('id_transaccion_pago');
        });
    }

    public function down(): void
    {
        Schema::table('aportaciones', function (Blueprint $table) {
            $table->dropColumn('metodo_pago');
        });
    }
};
