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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->integer('min');
            $table->integer('max');
            $table->boolean('buy_sell'); // 0 => buy & 1 => sell
            $table->string('tags');

            $table->string('theMethod');
            $table->string('note');
            $table->double('price');

            $table->timestamps();

            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();

        });
    }


    public function down()
    {
        Schema::dropIfExists('ads');
    }
};
