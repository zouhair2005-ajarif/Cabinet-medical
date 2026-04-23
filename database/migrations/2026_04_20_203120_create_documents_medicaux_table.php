<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents_medicaux', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_medical_id');
            $table->foreign('dossier_medical_id')
                  ->references('id')->on('dossiers_medicaux')
                  ->onDelete('cascade');
            $table->string('nom');
            $table->string('fichier');
            $table->string('type')->default('pdf');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('documents_medicaux');
    }
};