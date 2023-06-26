<?php

namespace App\Traits\Products;

use App\Models\ProductCategory;

trait SearchFilters {

    public function applySearchFilters($request, &$products, $selectedCategory = "") {
        $categoryWiseFields = config('product.fields');
        $selectedCategory = !empty($selectedCategory) ? $selectedCategory : 'blank';
        
        if (!empty($request->input('searched_category_id', ''))) {
            $searchedCategoryObject = ProductCategory::select('product_categories.id')
                    ->leftJoin('product_categories as child_level1', 'product_categories.parent_id', 'child_level1.id')
                    ->leftJoin('product_categories as child_level2', 'child_level1.parent_id', 'child_level2.id')
                    ->where('product_categories.id', $request->input('searched_category_id'))
                    ->orWhere('child_level1.id', $request->input('searched_category_id'))
                    ->orWhere('child_level2.id', $request->input('searched_category_id'))
                    ->get();

            if (!empty($searchedCategoryObject)) {
                $categoryIds = $searchedCategoryObject->pluck('id');
                $products->whereIn('product_categories.id', $categoryIds);
            }

            //$products->where('product_categories.id', $request->input('searched_category_id'));
        }

        if (!empty($request->input('color', []))) {
            $products->whereIn('color_id', $request->input('color'));
        }

        if (!empty($request->input('epoche', []))) {
            $products->whereIn('epoche', $request->input('epoche'));
        }

        if (!empty($request->input('style', []))) {
            $products->whereIn('style_id', $request->input('style'));
        }

        if (!empty($request->input('file_format', []))) {
            $products->whereIn('file_format', $request->input('file_format'));
        }

        if (!empty($request->input('graphic_form', []))) {
            $products->whereIn('graphic_form', $request->input('graphic_form'));
        }

        if (!empty($request->input('copy_right', []))) {
            $products->whereIn('copy_right', $request->input('copy_right'));
        }

        if (!empty($request->input('manufacturer_id', []))) {
            $products->whereIn('manufacturer_id', $request->input('manufacturer_id'));
        }

        if (!empty($request->input('manufacture_country', []))) {
            $products->whereIn('manufacture_country', $request->input('manufacture_country'));
        }

        $minQuantity = $request->input('min_amount', '');
        if (!empty($minQuantity)) {
            $products->where('quantity', '>', $minQuantity);
        }

        if (!empty($request->input('search_text', ''))) {
            $searchText = $request->input('search_text');
            $searchKeywords = explode(' ', $searchText);
            $keywordList = '';
            foreach ($searchKeywords as $keyword) {
                $keywordList .= $keyword . ' ';
                $keywordList .= $keyword . '* ';
            }

            $products->selectRaw('MATCH(keywords, cm_products.name, cm_products.description) AGAINST(? IN BOOLEAN MODE) AS score', [$keywordList]);
            //$products->whereRaw('MATCH(keywords, cm_products.name, cm_products.description) AGAINST(? WITH QUERY EXPANSION)', $searchText);
            $products->whereRaw('MATCH(keywords, cm_products.name, cm_products.description) AGAINST(? IN BOOLEAN MODE) > 0', $keywordList);
            //$products->whereRaw('score > 0.5');
            $products->orderBy('score', 'DESC');
        }

        
        $location = $request->input('location', '');
        if (!empty($location) && in_array('location_at', $categoryWiseFields[$selectedCategory])) {
            $distance = $request->input('radius', 0);
            $distance = $distance == 0 ? 1 : $distance;
            if (is_numeric($location)) {
                $products->where('products.postal_code', $location);
            } else {
                if (!empty($request->input('geo_location', ''))) {
                    $products->whereRaw("ST_Distance_Sphere(cm_products.geo_location, ST_GeomFromText(?)) <= ?", [$request->input('geo_location'), $distance * 1000]);
                } else {
                    $products->where('products.location', $location);
                }
            }
        }
    }

}
