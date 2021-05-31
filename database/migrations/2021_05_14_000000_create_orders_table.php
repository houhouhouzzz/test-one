<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('order_no', '24')->unique()->comment('订单号');
            $table->decimal('total', 8,2)->default(0)->comment('总价');
            $table->unsignedTinyInteger('order_status')->default(1)->comment('订单状态');
            $table->unsignedTinyInteger('order_type')->default(1)->comment('订单类型:1:客户下单;2:自主下单');
            $table->string('order_source')->comment('订单来源');
            $table->string('currency_code')->default('币种');
            $table->string('customer_name')->comment('用户名称');
            $table->string('customer_phone')->comment('用户的电话');
            $table->string('customer_what_apps')->nullable()->comment('用户的what_app');
            $table->string('customer_note')->comment('用户留言');
            $table->string('note')->nullable()->comment('订单备注');
            $table->decimal('order_cost', 6, 2)->default(0)->comment('订单底价');
            $table->decimal('shipping_cost', 6, 2)->default(0)->comment('物流运费');
            $table->decimal('cod_cost', 6, 2)->default(0)->comment('cod费用');
            $table->tinyInteger('shipping_method_id')->default(1)->comment('物流公司');
            $table->tinyInteger('shipping_status')->default(1)->comment('物流状态');
            $table->dateTime('shipping_at')->nullable()->comment('物流状态');
            $table->string('tracking_number')->default('')->comment('物流单号');
            $table->string('ip');
            $table->string('ip_iso_code');
            $table->string('lat_lon')->nullable()->comment('经纬度');
            $table->string('country')->comment('国家');
            $table->string('state')->nullable()->comment('州');
            $table->string('district')->nullable()->comment('区域');
            $table->string('city')->nullable()->comment('城市');
            $table->string('address')->comment('地址');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE orders AUTO_INCREMENT=10000");

        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('sku_id');
            $table->integer('quantity');
            $table->decimal('price', 8,2)->default(0)->comment('订单商品单价');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_products');
    }
}
