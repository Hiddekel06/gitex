<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter la colonne 'completed' à la table users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('completed')->default(false);
        });
    }

    /**
     * Supprimer la colonne 'completed' si rollback
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('completed');
        });
    }
};
