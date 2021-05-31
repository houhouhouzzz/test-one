<?php

namespace App\Admin\Actions\Post\Product;


use App\Extensions\Util;
use Encore\Admin\Actions\RowAction;

class JumpProductUrl extends RowAction
{
    public $name = '详情页面链接';

    public function render()
    {
        $href = Util::to(sprintf( '/list/product/%s', $this->getKey()));
        return "<a href='{$href}' target='_blank'>{$this->name()}</a>";
    }

}