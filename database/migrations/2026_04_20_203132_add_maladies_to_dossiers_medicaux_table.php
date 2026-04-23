<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('dossiers_medicaux', function (Blueprint $table) {
            $table->text('maladies_chroniques')->nullable()->after('allergies');
            $table->text('diagnostics')->nullable()->after('maladies_chroniques');
        });
    }
    public function down(): void {
        Schema::table('dossiers_medicaux', function (Blueprint $table) {
            $table->dropColumn(['maladies_chroniques', 'diagnostics']);
        });
    }
};