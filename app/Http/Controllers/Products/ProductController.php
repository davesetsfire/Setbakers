<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OrderDetail;
use App\Models\Favourite;
use App\Models\FundusDetail;
use Auth;
use App\Traits\Products\SearchFilters;

class ProductController extends Controller {

    use SearchFilters;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function categoryPage(Request $request, $selectedCategory = "") {
        //print_r($request->all());
        $productCounts = 0;

        $topLevelCategories = ProductCategory::active()
                ->where('level', 1)
                ->orderBy('display_order', 'ASC')
                ->get();

        /*
         * 
         */

        $fundusDetail = FundusDetail::where('fundus_name', $selectedCategory)->first();
        //if (!empty($fundusDetail)) {
        $selectedCategory = "";
        if (!empty($request->input('searched_category_id', ''))) {
            $productCategory = ProductCategory::where('id', $request->input('searched_category_id', ''))->with('parentcategory', 'parentcategory.parentcategory')->first();
            if (!empty($productCategory)) {
                $selectedCategory = $productCategory->parentcategory['parentcategory']['slug'] ?? $productCategory->parentcategory['slug'] ?? $productCategory->slug ?? '';
            }
        }
//        } else {
//            if (empty($selectedCategory) || $selectedCategory == "default") {
//                $selectedCategory = $topLevelCategories[0]->slug;
//            }
//        }

        $productCount = Product::active()->count();
        $isLastBankPaymentPending = false;
        if (Auth::check()) {
            $lastOrder = OrderDetail::where('user_id', Auth::user()->id)->with('subscription')->latest()->first();

            if (!empty($lastOrder) && $lastOrder->payment_mode == 'bank' && $lastOrder->status == 'pending' && $lastOrder->subscription['account_type'] == 'project') {
                $isLastBankPaymentPending = true;
            }
        }
        $products = Product::select('products.id as product_main_id',
                        'products.created_at as product_created_at',
                        'products.*',
                        'fundus_details.fundus_name',
                        'fundus_details.location as fundus_location',
                        'fundus_details.postal_code as fundus_postal_code',
                        'fundus_details.logo_image_path',
                        'fundus_details.fundus_email',
                        'fundus_details.fundus_phone'
                )
                ->where('products.is_active', 1)
                ->where('fundus_details.is_paused', 0);

        $products->join('product_category_mappings', 'products.id', 'product_category_mappings.product_id')
                ->join('product_categories', 'product_categories.id', 'product_category_mappings.category_id')
                ->leftJoin('fundus_details', 'fundus_details.id', 'products.store_id')
                ->leftJoin('attribute_options as epoche_data', 'epoche_data.id', 'products.epoche');

        if (empty($request->input('searched_category_id', '')) && !empty($selectedCategory) && $selectedCategory != "default") {
            $searchedCategoryObject = ProductCategory::select('product_categories.id')
                    ->leftJoin('product_categories as child_level1', 'product_categories.parent_id', 'child_level1.id')
                    ->leftJoin('product_categories as child_level2', 'child_level1.parent_id', 'child_level2.id')
                    ->where('product_categories.slug', $selectedCategory)
                    ->orWhere('child_level1.slug', $selectedCategory)
                    ->orWhere('child_level2.slug', $selectedCategory)
                    ->get();

            if (!empty($searchedCategoryObject)) {
                $categoryIds = $searchedCategoryObject->pluck('id');
                $products->whereIn('product_categories.id', $categoryIds);
            }
        }

        $this->applySearchFilters($request, $products, $selectedCategory);

        if (!empty($fundusDetail)) {
            $products->where('store_id', $fundusDetail->id);
            $productCounts = Product::where('is_active', 1)->where('store_id', $fundusDetail->id)->count();
        }

        $products->with(['productcategory.parentcategory.parentcategory', 'productMedia' => function ($query) {
                $query->select('product_id', 'file_name')
                        ->where('is_primary', 0);
            },
            'color',
            'style',
            'epocheText',
            'prices:id,product_id,price,duration_text',
            'graphicForm',
            'manufacture',
            'manufactureCountry',
            'fileFormat',
            'copyright']);

        if (Auth::check()) {
            $products->with('bookmark');
        }

        $products = $products
                //->orderBy('epoche_data.option_value', 'ASC')
                //->orderBy('products.year', 'ASC')
                ->orderBy('products.id', 'DESC')
                ->paginate(config('app.pagination_per_page_limit'));

        /*
         * Displaying all records without store information to not logged in users login
         * Retriction condition commented below
         */
        /*
          if (!Auth::check() ||
          (Auth::check() && Auth::user()->account_type == 'fundus') ||
          (Auth::check() && Auth::user()->account_type == 'complete' &&
          Auth::user()->projectDetail['subscription_end_date'] < date('Y-m-d H:i:s'))) {

          $total = config('app.guest_product_view_limit');
          $perPage = config('app.guest_product_view_limit');

          $products = new \Illuminate\Pagination\LengthAwarePaginator(
          array_slice($products->items(), 0, $perPage),
          $products->total() < $total ? $products->total() : $total,
          $perPage
          );
          }
        */

        $favourites = Favourite::whereIn('user_id', [0, Auth::user()->id ?? 0])->oldest()->get(['id', 'name']);

        return view('products.category')
                        ->with('products', $products)
                        ->with('topLevelCategories', $topLevelCategories)
                        ->with('selectedCategory', $selectedCategory)
                        ->with('isLastBankPaymentPending', $isLastBankPaymentPending)
                        ->with('productCount', $productCount)
                        ->with('favourites', $favourites)
                        ->with('fundusDetail', $fundusDetail)
                        ->with('productCounts', $productCounts)
                        ->with('categoryWiseFields', config('product.fields'));
    }

}
