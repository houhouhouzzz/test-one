<?php

namespace App\Admin\Actions\Post\Product;


use App\Extensions\Util;
use App\Model\Product;
use Encore\Admin\Actions\RowAction;

class JumpCategoryUrl extends RowAction
{
    public $name = '列表链接';

    public function render()
    {
        $href = Util::to(sprintf( '/category/%s', Product::whereId( $this->getKey())->value('category_id')));
        return "<a href='{$href}' target='_blank'>{$this->name()}</a>";
    }

}