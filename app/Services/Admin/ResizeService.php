<?php

namespace App\Services\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ResizeService
{
    // 文件临时存储位置
    // public/tmp_resize/
    public $temp_path = 'tmp_resize/';

    public function resize(UploadedFile $file)
    {
        $image = Image::make($file->getPathname());
        $extension = $file->getClientOriginalExtension();
        $tmp_file_name = Str::random(12) . '.' . $extension;
        if (! is_dir($path = public_path('static/uploads/images/'))) {
            mkdir($path, 0777, true);
        }
        $real_path = public_path('static/uploads/images/' . $tmp_file_name);
        $image->resize(round($image->width()*3/4), round($image->height()*3/4))->save($real_path);
        // 释放内存
        $image->destroy();
        $image = Image::make($real_path);
        return new UploadedFile(
            $real_path,
            $tmp_file_name,
            $image->mime(),
            $image->filesize(),
            null,
            true
        );
    }

    public function remove()
    {
        if (is_dir($path =public_path('static/uploads/'))) {
            $p = scandir($path);
            foreach ($p as $val) {
                //排除目录中的.和..
                if ($val != "." && $val != "..") {
                    //如果是目录则递归子目录，继续操作
                    if (is_dir($path . $val)) {
                        //目录清空后删除空文件夹
                        @rmdir($path . $val . '/');
                    } else {
                        //如果是文件直接删除
                        unlink($path . $val);
                    }
                }
            }
        }
    }
}
