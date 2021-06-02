<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_services', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name')->comment('标题');
            $table->text('content')->comment('页面内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->unsignedTinyInteger('sort')->default(5)->comment('排序值');
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
        Schema::dropIfExists('term_services');
    }
}
