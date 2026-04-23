<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->enum('statut', [
                'en_attente','accepte','refuse','termine','annule'
            ])->default('en_attente')->change();
        });
    }
    public function down(): void {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->enum('statut', [
                'en_attente','confirme','annule','termine'
            ])->default('en_attente')->change();
        });
    }
};