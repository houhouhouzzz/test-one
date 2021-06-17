<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title', 256)->comment('标题');
            $table->string('product_no', 32)->comment('货号');
            $table->integer('category_id')->default(0)->comment('分类id');
            $table->tinyInteger('is_ele')->default(0)->comment('是否带电');
            $table->tinyInteger('in_list')->default(0)->comment('是否在列表中');
            $table->string('ocean_number')->nullable()->default('')->comment('海关编码');
            $table->string('description')->nullable();
            $table->decimal('sa_price', 6,2)->nullable()->default(null);
            $table->decimal('ae_price', 6,2)->nullable()->default(null);
            $table->decimal('qa_price', 6,2)->nullable()->default(null);
            $table->decimal('kw_price', 6,2)->nullable()->default(null);
            $table->decimal('bh_price', 6,2)->nullable()->default(null);
            $table->decimal('om_price', 6,2)->nullable()->default(null);
            $table->decimal('cost', 6,2)->default(0)->comment('采购价');
            $table->string('pictures', 1024)->comment('主图');
            $table->unsignedSmallInteger('main_option')->default(0)->comment('主属性');
            $table->tinyInteger('video_position')->default(5)->comment('主图');
            $table->string('video_link')->default('')->comment('主图');
            $table->integer('weight', false, true)->default(0)->comment('重量(g)');

            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name')->comment('供应商链接');
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->integer('category_id')->comment('分类id');
            $table->integer('product_id')->comment('商品id');
        });

        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('product_id')->default(0)->comment('商品id');
            $table->string('link', '1024')->nullable()->default('')->comment('供应商链接');
            $table->string('note')->nullable()->default('')->comment('采购备注');
            $table->timestamps();
        });

        Schema::create('skus', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('product_id')->default(0)->comment('商品id');
            $table->string('sku')->comment('sku');
            $table->string('image')->comment('图片');
            $table->unsignedSmallInteger('main_option')->default(0)->comment('主选项id');
            $table->string('main_option_value')->default('')->comment('主选项值');
            $table->tinyInteger('status')->default(1)->comment('sku 状态');
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name')->comment('选项的 值');
            $table->timestamps();
        });

        Schema::create('product_options', function (Blueprint $table) {
            $table->integer('product_id')->default(0)->comment('sku id');
            $table->integer('option_id')->comment('选项的 id');
        });

        Schema::create('sku_options', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('sku_id')->default(0)->comment('sku id');
            $table->integer('option_id')->comment('选项的 id');
            $table->string('option_value')->comment('选项的值');
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_suppliers');
        Schema::dropIfExists('skus');
        Schema::dropIfExists('options');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('sku_options');
    }
}
