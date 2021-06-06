<?php

namespace App\Admin\Actions\Post\Gift;

use App\Extensions\Util;
use Encore\Admin\Actions\RowAction;

class GiftEdit extends RowAction
{
    public $name = '编辑';

    /**
     * Render row action.
     *
     * @return string
     */
    public function render()
    {
        $href = Util::to(sprintf( '/admin/gifts/%s/edit', $this->getKey()));
        return "<a href='{$href}' target='_blank'>{$this->name()}</a>";
    }

}