<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name')->comment('仓库名称');
            $table->timestamps();
        });

        Schema::create('sku_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('warehouse_id')->comment('仓库id');
            $table->integer('sku_id')->comment('sku id');
            $table->tinyInteger('inventory')->default(0)->comment('库存');
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sku_id')->comment('sku id');
            $table->tinyInteger('status')->default(1)->comment('采购状态');
            $table->unsignedInteger('quantity')->default(0)->comment('采购数量');
            $table->decimal('total', 6, 2)->default(0)->comment('采购价格');
            $table->string('image_url')->default('')->comment('图片路径');
            $table->string('third_purchase_number')->nullable()->comment('第三方采购单号');
            $table->string('tracking_company')->nullable()->comment('物流公司');
            $table->string('tracking_number')->nullable()->comment('物流单号');
            $table->string('note')->nullable()->comment('物流单号');
            $table->timestamps();
        });

//        Schema::

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('sku_inventories');
        Schema::dropIfExists('purchases');
    }
}
