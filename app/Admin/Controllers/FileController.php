<?php

namespace App\Admin\Controllers;

use App\Model\Category;
use App\Model\Image;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FileController extends AdminController
{
    public function upload(){

        $file = \request()->file('file');

        // 判断图片类型及尺寸
        $extension = $file->guessExtension();
        $size = $file->getClientSize();

        if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return $this->returnValidateError('extension', '文件格式不支持');
        }

        if ($size > (5 * 1024 * 1024)) {
            return $this->returnValidateError('size', '文件不能超过5M');
        }

        // 提取名字中字母 数字 下划线
        $original_extension = $file->getClientOriginalExtension();
        $uniq_name = uniqid() . "." . $original_extension;


        $md5  = md5_file($file->getPathName());
        $sha1 = sha1_file($file->getPathName());

        $data = Image::where('md5', $md5)->where('sha1', $sha1)->first();
        if( ! $data ){
            $path = $file->storeAS('', $uniq_name, "admin");
            $data = Image::create([
                'path' => $uniq_name,
                'md5' => $md5,
                'sha1' => $sha1
            ]);
        }

        return ['path' => $data->path];

    }
}
