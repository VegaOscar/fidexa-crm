<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('canjes', function (Blueprint $table) {
        $table->text('detalle')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('canjes', function (Blueprint $table) {
        $table->text('detalle')->nullable(false)->change();
    });
}

};
