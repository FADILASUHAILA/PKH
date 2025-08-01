
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
        Schema::create('bio_data', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->text('alamat')->nullable();
            $table->string('no_hp');
            $table->unsignedBigInteger('alternatif_id');
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('alternatif_id')
                ->references('id')
                ->on('alternatifs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_data');
    }
};
