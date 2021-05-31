<?php


namespace App\Admin\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class OnceDefaultShipping extends BatchAction
{
    protected $action;

    public function __construct($action = 4)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {
    console.log($.admin.grid.selected(),'{$this->resource}/release', {$this->action});
    var ids = $.admin.grid.selected();
    console.log(ids.join(','));
    $.ajax({
        method: 'post',
        url: '{$this->resource}/once-default-shipping',
        data: {
            _token:LA.token,
            ids: ids,
            action: {$this->action}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
            window.open("{$this->resource}/once-default-shipping-export?ids=" + ids.join(','));
        }
    });
});

EOT;

    }
}