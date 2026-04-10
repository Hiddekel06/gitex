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
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('questionnaire_id')
                ->nullable()
                ->after('id')
                ->constrained('questionnaires')
                ->nullOnDelete();

            $table->string('section')->nullable()->after('intitule');
            $table->unsignedInteger('ordre')->default(0)->after('section');
            $table->boolean('is_required')->default(true)->after('ordre');
            $table->json('options_json')->nullable()->after('is_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['questionnaire_id']);
            $table->dropColumn([
                'questionnaire_id',
                'section',
                'ordre',
                'is_required',
                'options_json',
            ]);
        });
    }
};
