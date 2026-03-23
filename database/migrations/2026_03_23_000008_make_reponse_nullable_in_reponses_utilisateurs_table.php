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
        Schema::table('reponses_utilisateurs', function (Blueprint $table) {
            $table->text('reponse')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reponses_utilisateurs', function (Blueprint $table) {
            $table->text('reponse')->nullable(false)->change();
        });
    }
};