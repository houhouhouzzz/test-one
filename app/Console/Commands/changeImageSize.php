<?php

namespace App\Console\Commands;

use App\Model\Image;
use App\Services\Admin\ResizeService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class changeImageSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-image-size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置图片尺寸';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const PER_PAGE = 50;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $total = Image::count();
        $times = ceil($total / self::PER_PAGE);
        $query = Image::query();
        for($i = 0; $i<$times; $i++){
            $images = $query->skip($i * self::PER_PAGE)->limit(self::PER_PAGE)->get();
            foreach ($images as $image){
                $extension_arr = explode( '.' , $image->path);
                $extension = $extension_arr[count($extension_arr)-1];
                if( in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])){
                    $real_path = public_path('static/uploads/' .  $image->path);
                    $origin_image = \Intervention\Image\ImageManagerStatic::make($real_path);
                    $origin_upload_file = new UploadedFile(
                        $real_path,
                        $image->path,
                        $origin_image->mime(),
                        $origin_image->filesize(),
                        null,
                        true
                    );
                    $resize_upload_file = app( ResizeService::class)->resize($origin_upload_file);
                    $path = $resize_upload_file->storeAS('', $image->path, "admin");
                }
            }
        }
        $this->info('finish');
    }
}
