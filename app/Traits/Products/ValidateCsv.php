<?php

namespace App\Traits\Products;

use Illuminate\Validation\Rule;
use App\Models\Attribute;
use App\Models\ProductCategory;
use App\Models\FundusDetail;
use App\Models\Product;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Traits\Products\MediaFunctions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use finfo;

trait ValidateCsv {

    use MediaFunctions;

    public function getCsvAsArray($fileContent, $keyField = null) {
        $fileContent = ltrim($fileContent, "\xEF\xBB\xBF");
        $rows = array_map('str_getcsv', explode(PHP_EOL, $fileContent));
        $rowKeys = array_shift($rows);

        $categoryName = $this->identifyCategory($rowKeys);
        $categories = __('csv_category_headers');
        if (isset($categories[$categoryName])) {
            foreach ($rowKeys as $csvFieldKey => $csvFieldName) {
                if (isset($categories[$categoryName][$csvFieldName])) {
                    $rowKeys[$csvFieldKey] = $categories[$categoryName][$csvFieldName];
                }
            }
        }

        $headerFieldCounts = count($rowKeys);
        $formattedData = [];
        foreach ($rows as $row) {
            if (count($row) == $headerFieldCounts) {
                $associatedRowData = array_combine($rowKeys, $row);
                $formattedData[] = $associatedRowData;
            }
        }

        return [$formattedData, $categoryName];
    }

    public function identifyCategory($csvHeaderFields) {
        $categories = __('csv_category_headers');
        foreach ($categories as $categoryKey => $categoryHeaders) {
            $difference = array_diff($csvHeaderFields, array_keys($categoryHeaders));
            if (count($difference) == 0) {
                return $categoryKey;
            }
        }
        return '';
    }

    public function allowedLimit() {
        $userId = \Auth::user()->id;

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        $productCounts = Product::where('store_id', $fundusDetail->id)->count();
        $maxAllowedCount = $fundusDetail->product_upload_limit ?? config('app.max_articles_fundus');
        if ($productCounts >= $maxAllowedCount) {
            return 0;
        } else {
            return $maxAllowedCount - $productCounts;
        }
    }

    public function bulkUploadValidationRules($categoryName) {
        $options = [];
        $subCategory1 = [];
        $subCategory2 = [];

        $subCategoryObject1 = ProductCategory::active()
                        ->where('level', 2)
                        ->whereHas('parentcategory', function ($query) use ($categoryName) {
                            $query->where('slug', $categoryName);
                        })->get('name');

        $subCategoryObject2 = ProductCategory::active()
                        ->where('level', 3)
                        ->whereHas('parentcategory', function ($query) use ($categoryName) {
                            $query->whereHas('parentcategory', function ($query) use ($categoryName) {
                                $query->where('slug', $categoryName);
                            });
                        })->get('name');

        foreach ($subCategoryObject1 as $subCategory) {
            $subCategory1[] = $subCategory->name;
        }
        foreach ($subCategoryObject2 as $subCategory) {
            $subCategory2[] = $subCategory->name;
        }

        $attributes = Attribute::with('attributeOptions')->get();

        foreach ($attributes as $attribute) {
            foreach ($attribute->attributeOptions as $attributeOptions)
                $options[$attribute->label][] = $attributeOptions['option_display'];
        }

        $validations = [
            '*.product_image1' => 'required',
            //'product_image.*' => 'image|max:7168|mimes:jpeg,jpg,png,gif|dimensions:min_width=' . config('app.image_thumbnail_max_width') . ',min_height=' . config('app.image_thumbnail_max_height'),
            '*.subcategory1' => ['required', Rule::in($subCategory1)],
            '*.product_name' => 'required|max:150',
            '*.product_description' => 'required',
            //'*.product_keywords' => 'required',
            '*.watermark' => ['nullable', Rule::in(['yes', 'no'])],
        ];

        if ($categoryName == 'requisiten-und-einrichtung') {
            $validations = array_merge($validations, [
                '*.subcategory2' => ['required', Rule::in($subCategory2)],
                '*.quantity' => 'required|regex:/(\d*)$/',
                '*.price' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.replacement_value' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.epoche' => ['required', Rule::in($options['epoche'])],
                '*.style' => ['nullable', Rule::in($options['style'])],
                '*.color' => ['nullable', Rule::in($options['color'])],
                '*.location' => 'required',
                '*.postal_code' => 'required|numeric',
                '*.length' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.width' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.height' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.dimension_unit' => ['nullable', Rule::in(['mm', 'cm', 'm'])]
            ]);
        } else if ($categoryName == 'grafik') {
            $validations = array_merge($validations, [
                '*.graphic_form' => ['required', Rule::in($options['graphic_form'])],
                '*.price' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.copy_right' => ['required', Rule::in($options['copy_right'])],
                '*.epoche' => ['required', Rule::in($options['epoche'])],
                '*.color' => ['nullable', Rule::in($options['color'])],
                '*.length' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.width' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.height' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.dimension_unit' => ['nullable', Rule::in(['mm', 'cm', 'm'])]
            ]);
        } else if ($categoryName == 'dienstleistung') {
            $validations = array_merge($validations, [
                '*.location' => 'required',
                '*.postal_code' => 'required|numeric'
            ]);
        } else if ($categoryName == 'fahrzeuge') {
            $validations = array_merge($validations, [
                '*.subcategory2' => ['required', Rule::in($subCategory2)],
                '*.quantity' => 'required|regex:/(\d*)$/',
                '*.manufacturer_id' => ['required', Rule::in($options['manufacture'])],
                '*.manufacture_country' => ['nullable', Rule::in($options['manufacture_country'])],
                '*.price' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.replacement_value' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.epoche' => ['required', Rule::in($options['epoche'])],
                '*.color' => ['nullable', Rule::in($options['color'])],
                '*.location' => 'required',
                '*.postal_code' => 'required|numeric',
                '*.length' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.width' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.height' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                '*.dimension_unit' => ['nullable', Rule::in(['mm', 'cm', 'm'])]
            ]);
        }

        return $validations;
    }

    public function saveImageZipFile($fileObject) {
        $userId = \Auth::user()->id;
        $zip = new ZipArchive();
        $storageDestinationPath = '';
        $status = $zip->open($fileObject->getRealPath());
        if ($status !== true) {
            //throw new \Exception($status);
            return $storageDestinationPath;
        } else {
            $storageDestinationPath = storage_path("app/uploads/unzip/" . $userId . '_' . time() . '/');

            if (!\File::exists($storageDestinationPath)) {
                \File::makeDirectory($storageDestinationPath, 0755, true);
            }
            $dir = $zip->getNameIndex(0);
            $zip->extractTo($storageDestinationPath);
            $zip->close();

            $subDirectory = '';
            $splitPosition = strrpos($dir, "/");
            if ($splitPosition !== false) {
                $subDirectory = substr($dir, 0, $splitPosition) . "/";
                $storageDestinationPath .= ($subDirectory != "/") ? $subDirectory : '';
            }
            //echo $dir . ' ---- ' . $splitPosition . ' ----- ' . $subDirectory;
            //echo $storageDestinationPath;
            ////exit();

            return $storageDestinationPath;
        }
    }

    public function validateProductMedia($imageFolderPath, $productImages) {
        $totalImages = count($productImages);
        $validImageCounts = 0;
        foreach ($productImages as $productImage) {
            if (File::exists($imageFolderPath . $productImage)) {
                [$width, $height] = getimagesize($imageFolderPath . $productImage);
                if ($width >= config('app.image_thumbnail_max_width') && $height >= config('app.image_thumbnail_max_height')) {
                    $validImageCounts += 1;
                }
            }
        }
        return $totalImages == $validImageCounts;
    }

    public function prepareProductData($productCsvData, $imageFolderPath, $categoryName) {
        $allProducts = [];
        $allMedias = [];

        $options = [];
        $subCategory1 = [];
        $subCategory2 = [];

        $subCategoryObject1 = ProductCategory::active()
                        ->where('level', 2)
                        ->whereHas('parentcategory', function ($query) use ($categoryName) {
                            $query->where('slug', $categoryName);
                        })->get(['id', 'name']);

        $subCategoryObject2 = ProductCategory::active()
                        ->where('level', 3)
                        ->whereHas('parentcategory', function ($query) use ($categoryName) {
                            $query->whereHas('parentcategory', function ($query) use ($categoryName) {
                                $query->where('slug', $categoryName);
                            });
                        })->get(['id', 'name']);

        foreach ($subCategoryObject1 as $subCategory) {
            $subCategory1[$subCategory->name] = $subCategory->id;
        }
        foreach ($subCategoryObject2 as $subCategory) {
            $subCategory2[$subCategory->name] = $subCategory->id;
        }

        $attributes = Attribute::with('attributeOptions')->get();

        foreach ($attributes as $attribute) {
            foreach ($attribute->attributeOptions as $attributeOptions)
                $options[$attribute->label][$attributeOptions['option_display']] = $attributeOptions['id'];
        }



        $userId = \Auth::user()->id;
        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        $productImageKeys = $this->getProductImageKeys();

        foreach ($productCsvData as $indexKey => $productData) {
            $productSlug = str_slug($productData['product_name'] . '-' . $userId . time() . $indexKey);
            $productMedias = [];
            $product = [];

            $addWatermark = $productData['watermark'] ?? 'no';
            foreach ($productImageKeys as $key => $productImageKey) {
                if (!empty($productData[$productImageKey])) {
                    $imageFileObject = $this->createFileObject($imageFolderPath . $productData[$productImageKey]);
                    $imageData = $this->storeProductImage($imageFileObject, $productSlug, $key, $addWatermark);

                    $productMedias[] = [
                        'file_name' => $imageData['thumbnailFileNameWithPath'],
                        'width' => $imageData['thumbnailWidth'],
                        'height' => $imageData['thumbnailHeight']
                    ];

                    if ($key == 0) {
                        $product['image'] = $imageData['thumbnailFileNameWithPath'];
                        $product['img_width'] = $imageData['thumbnailWidth'];
                        $product['img_height'] = $imageData['thumbnailHeight'];
                    }
                }
            }

            $productLocatedAt = 'others';

            $product['productMedias'] = $productMedias;

            $product['name'] = $productData['product_name'];
            $product['description'] = $productData['product_description'];
            $product['keywords'] = $productData['product_keywords'] ?? '';
            $product['slug'] = $productSlug;
            $product['epoche'] = $options['epoche'][$productData['epoche'] ?? ''] ?? 0;
            $product['year'] = $productData['year'] ?? 0;
            $product['quantity'] = $productData['quantity'] ?? 1;
            $product['color_id'] = $options['color'][$productData['color'] ?? ''] ?? 0;
            $product['style_id'] = $options['style'][$productData['style'] ?? ''] ?? 0;

            if ($productData['replacement_value'] ?? '' != '') {
                $product['replacement_value'] = $productData['replacement_value'];
            }

            $product['length'] = $productData['length'] ?? 0;
            $product['width'] = $productData['width'] ?? 0;
            $product['height'] = $productData['height'] ?? 0;

            if ($productData['dimension_unit'] ?? '' != '') {
                $product['dimension_unit'] = $productData['dimension_unit'];
            }

            $product['graphic_form'] = $options['graphic_form'][$productData['graphic_form'] ?? ''] ?? 0;
            $product['file_format'] = $options['file_format'][$productData['file_format'] ?? ''] ?? 0;
            $product['copy_right'] = $options['copy_right'][$productData['copy_right'] ?? ''] ?? 0;
            $product['manufacturer_id'] = $options['manufacturer_id'][$productData['manufacturer_id'] ?? ''] ?? 0;
            $product['manufacture_country'] = $options['manufacture_country'][$productData['manufacture_country'] ?? ''] ?? 0;

            $product['location_at'] = $productLocatedAt;
            $product['location'] = $productData['location'] ?? '';
            $product['postal_code'] = $productData['postal_code'] ?? 0;

            $product['is_active'] = 1;
            $product['store_id'] = $fundusDetail->id;
            $product['created_by'] = $userId;
            $product['updated_by'] = $userId;

            $product['category'] = $subCategory2[$productData['subcategory2'] ?? ''] ?? $subCategory1[$productData['subcategory1'] ?? ''];

            if (isset($productData['price'])) {
                $product['price'] = [$productData['price']];
            }

            $allProducts[] = $product;
            $allMedias[] = $productMedias;
        }
        return [$allProducts, $allMedias];
    }

    public function getProductImageKeys() {
        return $productImageKeys = ['product_image1', 'product_image2', 'product_image3', 'product_image4', 'product_image5'];
    }

    public function createFileObject($imageFileNameWithPath) {
        $pathInformation = pathinfo($imageFileNameWithPath);

        $newPath = $pathInformation['dirname'] . '/tmp-files/';
        if (!is_dir($newPath)) {
            mkdir($newPath, 0755);
        }

        $newUrl = $newPath . $pathInformation['basename'];
        copy($imageFileNameWithPath, $newUrl);
        $imageInformation = getimagesize($newUrl);

        $file = new UploadedFile(
                $newUrl,
                $pathInformation['basename'],
                $imageInformation['mime'],
                filesize($imageFileNameWithPath),
                true,
                TRUE
        );

        return $file;
    }

}
