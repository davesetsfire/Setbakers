<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Models\ProductCategory;

class CategoryComposer {

    public function __construct() {
        
    }

    public function compose(View $view) {
        $categories = ProductCategory::active()
                ->orderBy('display_order', 'ASC')
                ->get();

        $categoryList = [];
        $globalCategoryMaster = [];
        foreach ($categories as $category) {
            $globalCategoryMaster['slug'][$category->slug] = $category->id;
            $globalCategoryMaster['id'][$category->id] = $category;
            $categoryList[$category->level][$category->parent_id][$category->slug] = $category;
        }

        $globalRouteCategoryId = 0;
        $routeCategory = request()->route('category');
        
        if (Route::currentRouteNamed('index') && $routeCategory == "") {
            //$routeCategory = 'default';
        }
        
        if ($routeCategory != '') {
            if ($routeCategory == 'default') {
                $globalRouteCategoryId = 1;
            } else {
                $globalRouteCategoryId = $globalCategoryMaster['slug'][$routeCategory] ?? 0;
            }
        }


        $view->with('categoryList', $categoryList)
                ->with('globalCategoryMaster', $globalCategoryMaster)
                ->with('globalRouteCategoryId', $globalRouteCategoryId);
    }

}
