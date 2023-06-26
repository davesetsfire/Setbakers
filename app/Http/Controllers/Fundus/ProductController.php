<?php

namespace App\Http\Controllers\Fundus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryMapping;
use App\Models\ProductSearchKeyword;
use Auth;
use App\Models\FundusDetail;
use App\Models\ProductMedia;
use App\Models\Attribute;
use App\Models\ProductPrice;
use App\Traits\Products\MediaFunctions;
use App\Traits\Products\SearchFilters;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\StoreOrderProduct;
use App\Models\StoreOrderItem;
use App\Models\StoreOrder;
use App\Traits\Products\ValidateCsv;
use App\Exports\CreateProductCsvFileExport;
use Excel;

class ProductController extends Controller {

    use MediaFunctions,
        SearchFilters,
        ValidateCsv;

    public function __construct() {
        $this->middleware('checkProductAddLimit')->only(['create', 'store']);
    }

    public function index(Request $request) {
        $userId = \Auth::user()->id;

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        $topLevelCategories = ProductCategory::active()
                ->where('level', 1)
                ->orderBy('display_order', 'ASC')
                ->get();

        $selectedCategory = '';
        if (!empty($request->input('searched_category_id', ''))) {
            $productCategory = ProductCategory::where('id', $request->input('searched_category_id', ''))->with('parentcategory', 'parentcategory.parentcategory')->first();
            if (!empty($productCategory)) {
                $selectedCategory = $productCategory->parentcategory['parentcategory']['slug'] ?? $productCategory->parentcategory['slug'] ?? $productCategory->slug ?? '';
            }
        }

        $products = Product::select('products.id as product_main_id', 'products.created_at as product_created_at', 'products.*')
                ->where('products.is_active', 1)
                ->where('store_id', $fundusDetail->id);

        $products->join('product_category_mappings', 'products.id', 'product_category_mappings.product_id')
                ->join('product_categories', 'product_categories.id', 'product_category_mappings.category_id')
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

        $products->with('epocheText');

//        $products = $products
//                ->orderBy('epoche_data.option_value', 'ASC')
//                ->orderBy('products.year', 'ASC')
//                ->get();

        $products = $products->orderBy('products.id', 'DESC')->paginate(50);

        $productCounts = Product::where('is_active', 1)->where('store_id', $fundusDetail->id)->count();

        return view('fundus.products.index')
                        ->with('products', $products)
                        ->with('topLevelCategories', $topLevelCategories)
                        ->with('selectedCategory', $selectedCategory)
                        ->with('productCounts', $productCounts)
                        ->with('fundusDetail', $fundusDetail);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $attributes = [];
        $attributesObject = Attribute::with('attributeOptions')->get();

        foreach ($attributesObject as $attribute) {
            $attributes[$attribute->label] = $attribute['attributeOptions'];
        }

        $categoryWiseFields = config('product.fields');

        return view('fundus.products.create')
                        ->with('attributes', $attributes)
                        ->with('categoryWiseFields', $categoryWiseFields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request) {

        $userId = Auth::user()->id;
        $fundusDetail = FundusDetail::where('user_id', $userId)->first();

        $productSlug = str_slug($request->input('product_name') . '-' . $userId . time());

        $productMedias = [];
        $product = new Product();

        //565x300 png,jpg, jpeg, gif
        if ($request->hasFile('product_image')) {
            $addWatermark = $request->input('watermark', 'no');
            foreach ($request->file('product_image') as $key => $imageFileObject) {
                $imageData = $this->storeProductImage($imageFileObject, $productSlug, $key, $addWatermark);

                $productMedias[] = [
                    'file_name' => $imageData['thumbnailFileNameWithPath'],
                    'width' => $imageData['thumbnailWidth'],
                    'height' => $imageData['thumbnailHeight']
                ];

                if ($key == 0) {
                    $product->image = $imageData['thumbnailFileNameWithPath'];
                    $product->img_width = $imageData['thumbnailWidth'];
                    $product->img_height = $imageData['thumbnailHeight'];
                }
            }
        }

        try {
            DB::beginTransaction();

            $productLocatedAt = $request->input('location_at', '');
            $categorySlug = $request->input('product_category_slug', '');

            $product->name = $request->input('product_name');
            $product->description = $request->input('product_description');
            $product->keywords = $request->input('product_keywords');
            $product->slug = $productSlug;
            $product->epoche = $request->input('epoche') ?? 0;
            $product->year = $request->input('year') ?? 0;
            //if ($categorySlug != "dienstleistung" && $categorySlug != "grafik") {
            $product->quantity = $request->input('quantity') ?? 1;
            //}
            $product->color_id = $request->input('color') ?? 0;
            $product->style_id = $request->input('style', 0) ?? 0;
            $product->custom_price_available = $request->has('custom_price_available') ? 1 : 0;

            if ($request->input('replacement_value', '') != '') {
                $product->replacement_value = $request->input('replacement_value');
            }
            $product->length = $request->input('length', 0) ?? 0;
            $product->width = $request->input('width', 0) ?? 0;
            $product->height = $request->input('height', 0) ?? 0;

            if ($request->input('dimension_unit', '') != '') {
                $product->dimension_unit = $request->input('dimension_unit');
            }
            $product->graphic_form = $request->input('graphic_form', 0) ?? 0;
            $product->file_format = $request->input('file_format', 0) ?? 0;
            $product->copy_right = $request->input('copy_right', 0) ?? 0;
            $product->manufacturer_id = $request->input('manufacturer_id', 0) ?? 0;
            $product->manufacture_country = $request->input('manufacture_country', 0) ?? 0;

            $product->location_at = $productLocatedAt;
            if ($productLocatedAt == 'fundus') {
                $product->location = Auth::user()->fundusDetail['location'] ?? '';
                $product->geo_location = Auth::user()->fundusDetail['geo_location'] ?? NULL;
                $product->postal_code = Auth::user()->fundusDetail['postal_code'] ?? 0;
            } else {
                $product->location = $request->input('location', '') ?? '';
                if (!empty($request->input('geo_location', '') ?? '')) {
                    $product->geo_location = DB::raw('ST_GeomFromText(\'' . $request->input('geo_location') . '\')');
                }
                $product->postal_code = $request->input('postal_code', 0) ?? 0;
            }
            $product->is_active = 1;
            $product->store_id = $fundusDetail->id;
            $product->created_by = $userId;
            $product->updated_by = $userId;
            $product->save();

            $product->code = 'P' . str_pad($product->id, 9, '0', STR_PAD_LEFT);
            $product->save();

            foreach ($productMedias as $key => $productMedia) {
                $productMedia['product_id'] = $product->id;
                $productMedia['is_primary'] = ($key == 0) ? 1 : 0;
                ProductMedia::create($productMedia);
            }

            ProductCategoryMapping::create([
                'product_id' => $product->id,
                'category_id' => $request->input('category', 0)
            ]);

            $priceArray = $request->input('price', []);
            $durationArray = $request->input('duration', []);
            $this->insertProductPrice($priceArray, $durationArray, $product->id);
            /*
             * Store all search keywords for giving search options
             */
            $this->insertSearchKeywords($product->keywords);

            $orderItemId = $request->input('orditemid', '');
            if ($orderItemId != '') {

                $storeId = $fundusDetail->id ?? 0;
                //$orderItemId = $request->input('orditemid');
                $requestedItemCount = $request->input('requested_count', 1);
                $unitPrice = $request->input('unit_price', 0);

                $storeOrderItem = StoreOrderItem::where('id', $orderItemId)
                                ->whereHas('order', function ($query) use ($storeId) {
                                    $query->where('store_id', $storeId);
                                })->first();

                if (!empty($storeOrderItem)) {
                    StoreOrderProduct::create([
                        'order_item_id' => $storeOrderItem->id,
                        'order_id' => $storeOrderItem->order['id'],
                        'product_id' => $product->id,
                        'addon_product_id' => 0,
                        'quantity' => !empty($requestedItemCount) ? $requestedItemCount : 1,
                        'unit_price' => !empty($unitPrice) ? $unitPrice : 0,
                    ]);
                }

                DB::commit();
                //return view('fundus.products.message-to-opener');
                $response = ['status' => 'opener', 'message' => 'opener'];
                return response()->json($response, 200);
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());
//            return redirect()->route('fundus.index')
//                            ->with('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
            $request->session()->flash('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
            $response = ['status' => 'error', 'message' => 'error', 'route' => route('fundus.index')];
            return response()->json($response, 200);
        }

//        return redirect()->route('fundus.index')
//                        ->with('success_message', __('status_message.ARTICLE_CREATED'));

        $request->session()->flash('success_message', __('status_message.ARTICLE_CREATED'));
        $response = ['status' => 'success', 'message' => 'success', 'route' => route('fundus.index')];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::where('slug', $id)
                        ->where('store_id', $storeId)
                        ->with('productcategory.parentcategory.parentcategory',
                                'productMedia:id,product_id,file_name,is_primary',
                                'color',
                                'style',
                                'epocheText',
                                'prices:id,product_id,price,duration_text',
                                'graphicForm',
                                'manufacture',
                                'manufactureCountry',
                                'fileFormat',
                                'copyright'
                        )->first();
        if (!empty($product)) {
            $categoryWiseFields = config('product.fields');

            $storeOrders = StoreOrder::where('store_id', $storeId)
                            ->with(
                                    //'project',
                                    //'project.user',
                                    'orderItems',
                                    'orderItems.dateRanges',
                                    //'orderItems.orderProducts',
                            )->get();

            return view('fundus.products.show')
                            ->with('product', $product)
                            ->with('categoryWiseFields', $categoryWiseFields)
                            ->with('parentCategory', $product->top_category_slug)
                            ->with('storeOrders', $storeOrders);
        } else {
            return redirect()->route('fundus.index')
                            ->with('error_message', __('status_message.INVALID_ARTICLE'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $attributes = [];
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::selectRaw('cm_products.*, ST_AsText(geo_location) as geo_loc')->where('slug', $id)
                        ->where('store_id', $storeId)
                        ->with('productcategory', 'productMedia:id,product_id,file_name,is_primary', 'prices:id,product_id,price,duration_text')->first();

        if (!empty($product)) {

            $attributesObject = Attribute::with('attributeOptions')->get();

            foreach ($attributesObject as $attribute) {
                $attributes[$attribute->label] = $attribute['attributeOptions'];
            }

            $categoryWiseFields = config('product.fields');

            return view('fundus.products.edit')
                            ->with('product', $product)
                            ->with('attributes', $attributes)
                            ->with('categoryWiseFields', $categoryWiseFields);
        } else {
            return redirect()->route('fundus.index')
                            ->with('error_message', __('status_message.INVALID_ARTICLE'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id) {
        $userId = Auth::user()->id;

        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::where('slug', $id)
                        ->where('store_id', $storeId)->first();

        if (!empty($product)) {

            $productMedias = [];
            $productSlug = $product->slug;
            $addWatermark = $request->input('watermark', 'no');

            if ($request->hasFile('product_image')) {
                foreach ($request->file('product_image') as $key => $imageFileObject) {
                    $imageData = $this->storeProductImage($imageFileObject, $productSlug, $key, $addWatermark);

                    $productMedias[] = [
                        'file_name' => $imageData['thumbnailFileNameWithPath'],
                        'width' => $imageData['thumbnailWidth'],
                        'height' => $imageData['thumbnailHeight']
                    ];
                }
            }

            $updateProductMedias = [];
            if ($request->hasFile('primary_product_image')) {
                $imageData = $this->storeProductImage($request->file('primary_product_image'), $productSlug, '', $addWatermark);

                $updateProductMedias = [
                    'file_name' => $imageData['thumbnailFileNameWithPath'],
                    'width' => $imageData['thumbnailWidth'],
                    'height' => $imageData['thumbnailHeight']
                ];

                $product->image = $imageData['thumbnailFileNameWithPath'];
                $product->img_width = $imageData['thumbnailWidth'];
                $product->img_height = $imageData['thumbnailHeight'];
            }


            try {
                DB::beginTransaction();

                $productLocatedAt = $request->input('location_at', '');

                $product->name = $request->input('product_name');
                $product->description = $request->input('product_description');
                $product->keywords = $request->input('product_keywords');

                $product->epoche = $request->input('epoche') ?? 0;
                $product->year = $request->input('year') ?? 0;
                $product->quantity = $request->input('quantity') ?? 1;
                $product->color_id = $request->input('color') ?? 0;
                $product->style_id = $request->input('style', 0) ?? 0;
                $product->custom_price_available = $request->has('custom_price_available') ? 1 : 0;

                $replacementValue = $request->input('replacement_value', '');
                $product->replacement_value = $replacementValue != '' ? $replacementValue : 0;

                $product->length = $request->input('length', 0) ?? 0;
                $product->width = $request->input('width', 0) ?? 0;
                $product->height = $request->input('height', 0) ?? 0;

                if ($request->input('dimension_unit', '') != '') {
                    $product->dimension_unit = $request->input('dimension_unit');
                }
                $product->graphic_form = $request->input('graphic_form', 0) ?? 0;
                $product->file_format = $request->input('file_format', 0) ?? 0;
                $product->copy_right = $request->input('copy_right', 0) ?? 0;
                $product->manufacturer_id = $request->input('manufacturer_id', 0) ?? 0;
                $product->manufacture_country = $request->input('manufacture_country', 0) ?? 0;

                $product->location_at = $productLocatedAt;
                if ($productLocatedAt == 'fundus') {
                    $product->location = Auth::user()->fundusDetail['location'] ?? '';
                    $product->geo_location = Auth::user()->fundusDetail['geo_location'] ?? NULL;
                    $product->postal_code = Auth::user()->fundusDetail['postal_code'] ?? 0;
                } else {
                    $product->location = $request->input('location', '') ?? '';
                    if (!empty($request->input('geo_location', '') ?? '')) {
                        $product->geo_location = DB::raw('ST_GeomFromText(\'' . $request->input('geo_location') . '\')');
                    }
                    $product->postal_code = $request->input('postal_code', 0) ?? 0;
                }
                $product->updated_by = $userId;
                $product->save();

                $mediaRemovedIds = $request->input('current_selected_products');
                $arrayMediaIds = explode(',', $mediaRemovedIds);
                if (!empty($arrayMediaIds)) {
                    ProductMedia::where('product_id', $product->id)->whereIn('id', $arrayMediaIds)->delete();
                }

                foreach ($productMedias as $productMedia) {
                    $productMedia['product_id'] = $product->id;
                    ProductMedia::create($productMedia);
                }

                if (!empty($updateProductMedias)) {
                    ProductMedia::where('product_id', $product->id)->where('is_primary', 1)->update($updateProductMedias);
                }
                /*
                 * Store product category mappings
                 */
                ProductCategoryMapping::where('product_id', $product->id)->update([
                    'category_id' => $request->input('category', 0)
                ]);

                $this->updateProductPrice($request, $product->id);
                /*
                 * Store all search keywords for giving search options
                 */
                $this->insertSearchKeywords($product->product_keywords);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollBack();
                logger($e->getMessage());

//                return redirect()->route('fundus.index')
//                                ->with('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));

                $request->session()->flash('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
                $response = ['status' => 'error', 'message' => 'error', 'route' => route('fundus.index')];
                return response()->json($response, 200);
            }
//            return redirect()->route('fundus.index')
//                            ->with('success_message', __('status_message.ARTICLE_UPDATED'));

            $request->session()->flash('success_message', __('status_message.ARTICLE_UPDATED'));
            $response = ['status' => 'success', 'message' => 'success', 'route' => route('fundus.index')];
            return response()->json($response, 200);
        } else {
//            return redirect()->route('fundus.index')
//                            ->with('error_message', __('status_message.INVALID_ARTICLE'));

            $request->session()->flash('error_message', __('status_message.INVALID_ARTICLE'));
            $response = ['status' => 'error', 'message' => 'error', 'route' => route('fundus.index')];
            return response()->json($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $userId = \Auth::user()->id;
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::where('slug', $id)
                        ->where('store_id', $storeId)->first();

        if (!empty($product)) {
            try {
                DB::beginTransaction();
                ProductMedia::where('product_id', $product->id)->delete();
                ProductCategoryMapping::where('product_id', $product->id)->delete();
                $product->delete();

                DB::commit();
                return redirect()->route('fundus.index')
                                ->with('success_message', __('status_message.ARTICLE_DELETED'));
            } catch (QueryException $e) {
                DB::rollBack();
                logger($e->getMessage());

                return redirect()->route('fundus.index')
                                ->with('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
            }
        } else {
            return redirect()->route('fundus.index')
                            ->with('error_message', __('status_message.INVALID_ARTICLE'));
        }
    }

    public function updateProductCount(Request $request, $productSlug = "") {
        $userId = \Auth::user()->id;
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $product = Product::where('slug', $productSlug)
                        ->where('store_id', $storeId)->first();

        if (!empty($product)) {
            if ($request->input('action', '') == 'increment') {
                $product->increment('quantity');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_INCREMENT'), 'currentValue' => $product->quantity]);
            }
            if ($request->input('action', '') == 'decrement') {
                $product->decrement('quantity');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_DECREMENT'), 'currentValue' => $product->quantity]);
            }
        } else {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

    public function insertSearchKeywords($searchKeywords) {
        if (empty($searchKeywords)) {
            return true;
        }
        $searchKeywordsArray = array_unique(array_map('trim', explode(',', strtolower($searchKeywords))));

        $keywordExistIds = [];
        $searchKeywordObject = ProductSearchKeyword::select('id', 'keyword')->whereIn('keyword', $searchKeywordsArray)->get();
        if (count($searchKeywordObject) > 0) {
            $keywordExistIds = $searchKeywordObject->pluck('id');
            ProductSearchKeyword::whereIn('id', $keywordExistIds)->increment('counts');
        }
        $searchKeywordObject->transform(function ($item) {
            $item->keyword = strtolower($item->keyword);
            return $item;
        });

        $keywordsToInsert = [];
        foreach ($searchKeywordsArray as $keyword) {
            if (count($searchKeywordObject->where('keyword', strtolower($keyword))) == 0) {
                $keywordsToInsert[] = ['keyword' => $keyword];
            }
        }

        if (!empty($keywordsToInsert)) {
            ProductSearchKeyword::insert($keywordsToInsert);
        }

        return true;
    }

    public function insertProductPrice($priceArray, $durationArray, $productId) {
        foreach ($priceArray as $key => $priceItem) {
            if (!empty($priceItem)) {
                ProductPrice::create([
                    'product_id' => $productId,
                    'price' => $priceItem,
                    'duration_text' => $durationArray[$key] ?? 'Tag'
                ]);
            }
        }
    }

    public function updateProductPrice($request, $productId) {
        //dd($request->all());
        $priceRemovedIds = $request->input('current_selected_prices');
        $arrayPriceIds = explode(',', $priceRemovedIds);
        if (!empty($arrayPriceIds)) {
            ProductPrice::where('product_id', $productId)->whereIn('id', $arrayPriceIds)->delete();
        }

        $priceIndexArray = $request->input('price_index', []);
        $priceArray = $request->input('price', []);
        $durationArray = $request->input('duration', []);
        foreach ($priceIndexArray as $key => $priceIndexItem) {
            if (!empty($priceArray[$key])) {
                if (!empty($priceIndexItem)) {
                    $productPriceObject = ProductPrice::where('product_id', $productId)
                                    ->where('id', $priceIndexArray[$key])->first();
                    $productPriceObject->price = $priceArray[$key];
                    $productPriceObject->duration_text = $durationArray[$key] ?? 'Tag';
                    $productPriceObject->save();
                } else {
                    ProductPrice::create([
                        'product_id' => $productId,
                        'price' => $priceArray[$key],
                        'duration_text' => $durationArray[$key] ?? 'Tag'
                    ]);
                }
            } else {
                ProductPrice::where('product_id', $productId)
                        ->where('id', $priceIndexArray[$key])->delete();
            }
        }
    }

    public function bulkCreate(Request $request) {
        ini_set('max_execution_time', 300);
        $request->validate([
            'products_csv_file' => 'required|file|max:7168|mimes:csv,txt',
            'images_zip_file' => 'required|file|mimes:zip'
        ]);

        $fileContents = $request->file('products_csv_file')->get();
        list($csvData, $categoryName) = $this->getCsvAsArray($fileContents);
        $totalProductCounts = count($csvData);
        if ($totalProductCounts == 0) {
            throw ValidationException::withMessages([
                        'products_csv_file' => [__('bulk_upload.EMPTY_FILE')],
            ]);
        }

        if (empty($categoryName)) {
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('bulk_upload.INVALID_CSV_HEADERS')],
            ]);
        }

        //Category wise validation
        $rules = $this->bulkUploadValidationRules($categoryName);
        $validator = Validator::make($csvData, $rules);

        if ($validator->fails()) {
            //print_r($validator->errors());
            //exit();
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('bulk_upload.REQUIRED_VALIDATION_FAILED')],
            ]);
        }

        //check article allowed limit
        if (($this->allowedLimit() - $totalProductCounts) < 0) {
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('status_message.ARTICLE_UPLOAD_MAX_LIMIT_REACHED')],
            ]);
        }

        $productImages = [];
        $productImageKeys = $this->getProductImageKeys();
        foreach ($csvData as $csvDataItem) {
            foreach ($productImageKeys as $imageKey) {
                if (!empty($csvDataItem[$imageKey])) {
                    $productImages[] = $csvDataItem[$imageKey];
                }
            }
        }

        $imageFolderPath = $this->saveImageZipFile($request->file('images_zip_file'), $productImages);
        if (empty($imageFolderPath)) {
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('bulk_upload.UNABLE_TO_OPEN_ZIP')],
            ]);
        }

        $validationStatus = $this->validateProductMedia($imageFolderPath, $productImages);
        if (!$validationStatus) {
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('bulk_upload.IMAGE_VALIDATION_FAILED')],
            ]);
        }

        [$allProducts, $allMedias] = $this->prepareProductData($csvData, $imageFolderPath, $categoryName);

        //Category wise insert
        try {
            DB::beginTransaction();
            foreach ($allProducts as $productData) {
                $product = Product::create((collect($productData)->only(['code', 'name', 'description', 'keywords', 'slug',
                                    'image', 'img_width', 'img_height', 'epoche', 'year',
                                    'quantity', 'color_id', 'style_id', 'replacement_value', 'length',
                                    'width', 'height', 'dimension_unit', 'graphic_form', 'file_format', 'copy_right',
                                    'manufacturer_id', 'manufacture_country', 'location_at', 'location',
                                    'postal_code', 'is_active', 'store_id', 'created_by', 'updated_by'])
                                )->toArray()
                );
                $product->code = 'P' . str_pad($product->id, 9, '0', STR_PAD_LEFT);
                $product->save();

                foreach ($productData['productMedias'] as $key => $productMedia) {
                    $productMedia['product_id'] = $product->id;
                    $productMedia['is_primary'] = ($key == 0) ? 1 : 0;
                    ProductMedia::create($productMedia);
                }

                ProductCategoryMapping::create([
                    'product_id' => $product->id,
                    'category_id' => $productData['category'] ?? 0
                ]);

                $priceArray = $productData['price'] ?? [];
                $durationArray = [];
                $this->insertProductPrice($priceArray, $durationArray, $product->id);
                /*
                 * Store all search keywords for giving search options
                 */
                $this->insertSearchKeywords($product->keywords);
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());
            throw ValidationException::withMessages([
                        'bulk-upload-error' => [__('bulk_upload.UNABLE_TO_PROCESS')],
            ]);
        }

        $response = ['status' => 'success', 'message' => __('bulk_upload.SUCCESS')];

        return response()->json($response, 200);
    }

    public function downloadDocument(Request $request, $documentType = "") {
        if ($documentType == 'manual') {
            return response()->download(public_path('assets/docs/SetBakers-Anleitung.docx'), 'SetBakers-Anleitung.docx');
        } else if ($documentType == 'masters') {
            return response()->download(public_path('assets/docs/SetBakers-Legende.xlsx'), 'SetBakers-Legende.xlsx');
        } else if (in_array($documentType, ['Requisiten-und-Einrichtung-CSV', 'Grafik-CSV', 'Dienstleistung-CSV', 'Fahrzeuge-CSV'])) {
            $fileName = $documentType . '.csv';
            $storedCsvFile = public_path('assets/docs/' . $fileName);

            return Excel::download(
                            new CreateProductCsvFileExport($storedCsvFile), $fileName, \Maatwebsite\Excel\Excel::CSV
            );
        }
    }

}
