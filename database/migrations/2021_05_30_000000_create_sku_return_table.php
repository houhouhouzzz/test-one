<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sku_id')->comment('sku id');
            $table->string('country_code')->comment('国家 code');
            $table->smallInteger('shipping_method_id')->comment('物流商id');
            $table->string('origin_tracking_number')->comment('运单号');
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
        Schema::dropIfExists('sku_returns');
    }
}
