<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('column_id');
            $table->foreign('column_id')->references('id')->on('columns')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->unsignedBigInteger('row_id');
            $table->foreign('row_id')->references('id')->on('rows')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->tinyInteger('booking_status')->comment('1-Booked, 0-Not Booked')->default(0);
            $table->tinyInteger('status')->comment('1-Enable, 0-Disable')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
