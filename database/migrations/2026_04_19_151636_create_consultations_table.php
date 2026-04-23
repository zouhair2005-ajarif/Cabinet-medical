<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            // On référence manuellement la table 'rendezvous'
            $table->unsignedBigInteger('rendezvous_id');
            $table->foreign('rendezvous_id')
                  ->references('id')
                  ->on('rendezvous')
                  ->onDelete('cascade');
            // On référence manuellement la table 'dossiers_medicaux'
            $table->unsignedBigInteger('dossier_medical_id');
            $table->foreign('dossier_medical_id')
                  ->references('id')
                  ->on('dossiers_medicaux')
                  ->onDelete('cascade');
            $table->text('compte_rendu')->nullable();
            $table->text('observations')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};