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
        Schema::table('books', function (Blueprint $table) {
            // Verificando se a coluna cover_image não existe antes de adicioná-la
            if (!Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable(); // A coluna pode ser nula, caso o livro não tenha imagem
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Removendo a coluna cover_image
            $table->dropColumn('cover_image');
        });
    }
};
