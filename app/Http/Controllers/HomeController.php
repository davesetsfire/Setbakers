<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OrderDetail;
use App\Models\Favourite;
use Auth;
use App\Traits\Products\SearchFilters;
use App\Models\FeaturedProduct;

class HomeController extends Controller {

    use SearchFilters;

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index(Request $request, $selectedCategory = "") {
//        if (\Auth::check()) {
//            return redirect()->route('product.category', ['category' => 'default']);
//        }

        $featuredProducts = FeaturedProduct::with([
                            'product',
                            'product.productcategory.parentcategory.parentcategory',
                            'product.productMedia:id,product_id,file_name,is_primary',
                            'product.color',
                            'product.style',
                            'product.epocheText',
                            'product.prices:id,product_id,price,duration_text',
                            'product.graphicForm',
                            'product.manufacture',
                            'product.manufactureCountry',
                            'product.fileFormat',
                            'product.copyright',
                            'product.fundusDetail'
                        ])
                        ->whereHas('product', function ($query) {
                            $query->where('is_active', 1);
                            $query->has('fundusDetail');
                        })->get();

        $displayLoginPopup = $request->input('displayLoginPopup', false);

        $topLevelCategories = ProductCategory::active()
                ->where('level', 1)
                ->orderBy('display_order', 'ASC')
                ->get();

        $productList = [];
        $categorySlugArray = [];

        foreach ($topLevelCategories as $category) {
            $categorySlug = $category->slug;
            $categorySlugArray[] = $category->slug;
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
                    ->join('fundus_details', 'fundus_details.id', 'products.store_id')
                    ->leftJoin('attribute_options as epoche_data', 'epoche_data.id', 'products.epoche');

            if (!empty($categorySlug) && $categorySlug != "default") {
                $searchedCategoryObject = ProductCategory::select('product_categories.id')
                        ->leftJoin('product_categories as child_level1', 'product_categories.parent_id', 'child_level1.id')
                        ->leftJoin('product_categories as child_level2', 'child_level1.parent_id', 'child_level2.id')
                        ->where('product_categories.slug', $categorySlug)
                        ->orWhere('child_level1.slug', $categorySlug)
                        ->orWhere('child_level2.slug', $categorySlug)
                        ->get();

                if (!empty($searchedCategoryObject)) {
                    $categoryIds = $searchedCategoryObject->pluck('id');
                    $products->whereIn('product_categories.id', $categoryIds);
                }
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

            $products = $products->orderBy('products.id', 'DESC')->limit(20)->get();
            $productList[$category->id] = $products;
        }

//        if (empty($selectedCategory) || $selectedCategory == "default" || !in_array($selectedCategory, $categorySlugArray)) {
//            $selectedCategory = $topLevelCategories[0]->slug;
//        }

        $selectedCategory = "";
        if (!empty($request->input('searched_category_id', ''))) {
            $productCategory = ProductCategory::where('id', $request->input('searched_category_id', ''))->with('parentcategory', 'parentcategory.parentcategory')->first();
            if (!empty($productCategory)) {
                $selectedCategory = $productCategory->parentcategory['parentcategory']['slug'] ?? $productCategory->parentcategory['slug'] ?? $productCategory->slug ?? '';
            }
        }
        
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

        return view('index')
                        ->with('topLevelCategories', $topLevelCategories)
                        ->with('productList', $productList)
                        ->with('displayLoginPopup', $displayLoginPopup)
                        ->with('products', $products)
                        ->with('selectedCategory', $selectedCategory)
                        ->with('isLastBankPaymentPending', $isLastBankPaymentPending)
                        ->with('productCount', $productCount)
                        ->with('favourites', $favourites)
                        ->with('categoryWiseFields', config('product.fields'))
                        ->with('featuredProducts', $featuredProducts);
    }

    public function login(Request $request) {
        $request->request->add(['displayLoginPopup' => true]);
        return $this->index($request);
    }

}
