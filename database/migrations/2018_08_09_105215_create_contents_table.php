<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link', 255);
            $table->integer('page_number')->unsigned();
            $table->integer('x')->unsigned();
            $table->integer('y')->unsigned();
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('ebook_id')->unsigned();
            $table->integer('link_type_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('ebook_id')
                    ->references('id')
                    ->on('ebooks')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                    
            $table->foreign('link_type_id')
                    ->references('id')
                    ->on('link_types')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
