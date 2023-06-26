<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class UpdateProductRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        $storeId = \Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::where('slug', $this->route('fundu'))
                        ->where('store_id', $storeId)->first();

        return empty($product) ? false : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $validations = [
            'product_image.*' => 'sometimes|image|max:7168|mimes:jpeg,jpg,png,gif|dimensions:min_width=' . config('app.image_thumbnail_max_width') . ',min_height=' . config('app.image_thumbnail_max_height'),
            'primary_product_image' => 'sometimes|image|max:7168|mimes:jpeg,jpg,png,gif|dimensions:min_width=' . config('app.image_thumbnail_max_width') . ',min_height=' . config('app.image_thumbnail_max_height'),
            'category' => 'required',
            'product_name' => 'required',
            'product_description' => 'required',
            //'product_keywords' => 'required',
        ];

        $mainCategory = $this->input('product_category_slug');
        if ($mainCategory == 'requisiten-und-einrichtung') {
            $validations = array_merge($validations, [
                'quantity' => 'required|numeric|gt:0',
                'price.*' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',  //(\d*)(?:\.?)(\d+),(\d+)?
                'duration.*' => 'nullable|distinct',
                'replacement_value' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/', //^\d{1,3}(?:\.\d{3})*(?:,\d+)?$
                'epoche' => 'required',
                'style' => 'nullable|numeric',
                'color' => 'nullable|numeric',
                'location_at' => 'required',
                'location' => 'required_if:location_at,others',
                'postal_code' => 'required_if:location_at,others'
            ]);
        } else if ($mainCategory == 'grafik') {
            $validations = array_merge($validations, [
                'graphic_form' => 'required|numeric|gt:0',
                'price.*' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                'duration.*' => 'nullable|distinct',
                //'file_format' => 'required|numeric|gt:0',
                'copy_right' => 'required|numeric|gt:0',
                'epoche' => 'required',
                'color' => 'nullable|numeric'
            ]);
        } else if ($mainCategory == 'dienstleistung') {
            $validations = array_merge($validations, [
                'location_at' => 'required',
                'location' => 'required_if:location_at,others',
                'postal_code' => 'required_if:location_at,others'
            ]);
        } else if ($mainCategory == 'fahrzeuge') {
            $validations = array_merge($validations, [
                'quantity' => 'required|numeric|gt:0',
                'manufacturer_id' => 'required|numeric|gt:0',
                'manufacture_country' => 'nullable|numeric',
                'price.*' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                'duration.*' => 'nullable|distinct',
                'replacement_value' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                'epoche' => 'required',
                'color' => 'nullable|numeric',
                'location_at' => 'required',
                'location' => 'required_if:location_at,others',
                'postal_code' => 'required_if:location_at,others'
            ]);
        }

        return $validations;
    }

    public function messages() {
        return [
            'product_image.required' => 'Artikelbild muss hochgeladen werden',
            'product_image.*.max' => 'Artikelbild darf maximal 7 MB groß sein.',
            'primary_product_image.max' => 'Artikelbild darf maximal 7 MB groß sein.',
            'primary_product_image.dimensions' => 'Die Mindestgröße Deiner Fotos beträgt ' . config('app.image_thumbnail_max_width') . 'x' . config('app.image_thumbnail_max_height') . ' Pixel.',
            'product_image.*.dimensions' => 'Die Mindestgröße Deiner Fotos beträgt ' . config('app.image_thumbnail_max_width') . 'x' . config('app.image_thumbnail_max_height') . ' Pixel.',
            'location.required_if' => 'Ort muss ausgefüllt werden.',
            'postal_code.required_if' => 'Postleitzahl muss ausgefüllt werden.'
        ];
    }

}
