<?php

namespace App\Admin\Actions\Post;

use App\Model\WhatApp;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Support\Collection;

class WhatAppMultiClose extends BatchAction
{
    public $name = '批量关闭';

    public function handle(Collection $collections)
    {
        $what_app_ids = $collections->map->id;

        WhatApp::whereIn('id', $what_app_ids)->update(['status'=>WhatApp::STATUS_CLOSE]);

        return $this->response()->success('Success message...')->refresh();
    }

}