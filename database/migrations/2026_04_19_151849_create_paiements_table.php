<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rendezvous_id');
            $table->foreign('rendezvous_id')
                  ->references('id')
                  ->on('rendezvous')
                  ->onDelete('cascade');
            $table->float('montant');
            $table->date('date');
            $table->string('methode')->default('especes');
            $table->enum('statut', ['en_attente', 'paye', 'annule'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};