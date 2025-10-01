<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face', function (Blueprint $table) {
            $table->bigIncrements('face_id');
            $table->bigInteger('face_user_id');
            $table->string('face_image');
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->text('comparison_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face');
    }
};
