<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Attribute;

class SearchFilterComposer {

    public function __construct() {
        
    }

    public function compose(View $view) {
        $attributes = [];
        $attributesObject = Attribute::with('attributeOptions')->get();

        foreach ($attributesObject as $attribute) {
            $attributes[$attribute->label] = $attribute['attributeOptions'];
        }


        $view->with('attributes', $attributes);
    }

}
