<?php

namespace App\Traits\Products;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait MediaFunctions {

    public function createFileName($productSlug, $imageFileExtension, $key) {
        if (empty($key)) {
            $key = random_str(6);
        }
        return $productSlug . '-' . time() . $key . '.' . $imageFileExtension;
    }

    public function createFolderName() {
        return 'products/' . date('Y/m/d');
    }

    public function getOriginalFileName($imageFileName) {
        return 'org-' . $imageFileName;
    }

    public function getThumbnailFileName($imageFileName) {
        return 't-' . $imageFileName;
    }

    public function storeProductImage($imageFileObject, $productSlug, $key = '', $addWatermark = '') {
        $imageFileExtension = strtolower($imageFileObject->getClientOriginalExtension());

        $imageFileName = $this->createFileName($productSlug, $imageFileExtension, $key);
        $folderName = $this->createFolderName();
        $originalFileNameWithPath = $folderName . '/' . $this->getOriginalFileName($imageFileName);
        $thumbnailFileNameWithPath = $folderName . '/' . $this->getThumbnailFileName($imageFileName);

//        $thumnailImageObject = resizeImage($imageFileObject, config('app.image_thumbnail_max_width'), config('app.image_thumbnail_max_height'), $imageFileExtension);
//        $imageFileObject->storeAs('', $originalFileNameWithPath, 'public');
//        saveImage($thumnailImageObject, $thumbnailFileNameWithPath, $imageFileExtension);

        Storage::disk(config('app.storage_disk'))->put($originalFileNameWithPath, file_get_contents($imageFileObject));

        $img = Image::make($imageFileObject)->orientate();

        $img->resize(config('app.image_thumbnail_max_width'), config('app.image_thumbnail_max_height'), function ($constraint) {
            $constraint->aspectRatio();
            //$constraint->upsize();
        });

        if ($addWatermark == "yes") {            
            $watermark = Image::make(public_path('assets/images/watermark.png'));
            $img->insert($watermark);
        }

        $width = $img->width();
        $height = $img->height();
        $thumbNail = $img->stream()->detach();

        Storage::disk(config('app.storage_disk'))->put($thumbnailFileNameWithPath, $thumbNail);

        return [
            'originalFileNameWithPath' => $originalFileNameWithPath,
            'thumbnailFileNameWithPath' => $thumbnailFileNameWithPath,
            'thumbnailWidth' => $width,
            'thumbnailHeight' => $height
        ];
    }

}
