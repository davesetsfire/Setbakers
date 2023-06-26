<?php

namespace App\Http\Controllers\Fundus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundusDetail;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use App\Models\StoreOrderItemDateRange;
use App\Models\StoreOrderProduct;
use App\Models\ProjectDetail;
use App\Models\StoreAddonProduct;
use App\Traits\Products\MediaFunctions;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Auth;
use PDF;
use App\Models\Product;

class InquiryController extends Controller {

    use MediaFunctions;

    public function index(Request $request) {
        $userId = \Auth::user()->id;
        $fundusDetail = FundusDetail::where('user_id', $userId)->first();

        $storeOrders = StoreOrder::where('store_id', $fundusDetail->id)
                        ->with(['project' => function ($query) {
                                $query->withTrashed();
                            },
                            'project.user',
                            'orderItems',
                            'orderItems.dateRanges',
                            'orderItems.orderProducts' => function ($query) {
                                $query->has('product')
                                ->orHas('addonProduct');
                            },
                            'orderItems.orderProducts.product',
                            'orderItems.orderProducts.product.productcategory.parentcategory.parentcategory',
                            'orderItems.orderProducts.product.productMedia:id,product_id,file_name,is_primary',
                            'orderItems.orderProducts.product.color',
                            'orderItems.orderProducts.product.style',
                            'orderItems.orderProducts.product.epocheText',
                            'orderItems.orderProducts.product.prices:id,product_id,price,duration_text',
                            'orderItems.orderProducts.addonProduct',
                        ])->latest()->get();

        return view('fundus.inquiries.index')->with('storeOrders', $storeOrders);
    }

    public function downloadInquiry(Request $request) {
        $request->validate([
            'order_item_id' => 'required',
            'order_id' => 'required'
                ], [
            'order_item_id.required' => 'Please select atleast one project request.'
        ]);

        $userId = Auth::user()->id;
        $storeDetail = FundusDetail::where('user_id', $userId)->first();

        $storeOrder = StoreOrder::where('id', $request->input('order_id'))
                        ->where('store_id', $storeDetail->id)->first();

        $storeOrderItems = StoreOrderItem::where('order_id', $request->input('order_id'))
                        ->whereIn('id', $request->input('order_item_id'))
                        ->with([
                            'orderProducts' => function ($query) {
                                $query->has('product')
                                ->orHas('addonProduct');
                            },
                            'orderProducts.product',
                            'dateRanges',
                            'orderProducts.addonProduct'
                        ])->get();

        if (empty($storeDetail) || empty($storeOrder) || count($storeOrderItems) == 0) {
            throw ValidationException::withMessages([
                        'order_item_id' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }


        $projectDetail = ProjectDetail::where('id', $storeOrder->project_id)->withTrashed()->with('user')->first();

        $requestNumber = $storeOrder->order_number;
        $requestDate = $storeOrder->created_at->format('d.m.Y');

        $pdf = PDF::loadView('pdf.product-request-download', [
                    'storeOrderItems' => $storeOrderItems,
                    'storeName' => $storeDetail->fundus_name ?? '',
                    'requestNumber' => $requestNumber,
                    'requestDate' => $requestDate,
                    'projectDetail' => [
                        "projectName" => $projectDetail->project_name ?? '',
                        "projectCompany" => $projectDetail->company_name ?? '',
                        "projectAddressLine1" => $projectDetail->address_line_one ?? '',
                        "projectAddressLine2" => $projectDetail->address_line_two ?? '',
                        "projectEmailId" => $projectDetail->user['email'] ?? '',
                        "projectContactName" => $projectDetail->user['name'] ?? ''
                    ],
                    'displayImages' => false //disabled the gallery as per client's feedback
        ]);

        $pdf->getDomPDF()->setHttpContext(
                stream_context_create(
                        [
                            'ssl' => [
                                'allow_self_signed' => TRUE,
                                'verify_peer' => FALSE,
                                'verify_peer_name' => FALSE,
                            ]
                        ]
                )
        );

        return $pdf->download('Anfrage_' . $requestNumber . '.pdf');
    }

    public function downloadInquiryGallery(Request $request) {
        $request->validate([
            'order_item_id' => 'required',
            'order_id' => 'required'
                ], [
            'order_item_id.required' => 'Please select atleast one project request.'
        ]);

        $userId = Auth::user()->id;
        $storeDetail = FundusDetail::where('user_id', $userId)->first();

        $storeOrder = StoreOrder::where('id', $request->input('order_id'))
                        ->where('store_id', $storeDetail->id)->first();

        $storeOrderItems = StoreOrderItem::where('order_id', $request->input('order_id'))
                        ->whereIn('id', $request->input('order_item_id'))
                        ->with([
                            'orderProducts' => function ($query) {
                                $query->has('product')
                                ->orHas('addonProduct');
                            },
                            'orderProducts.product',
                            'dateRanges',
                            'orderProducts.addonProduct'
                        ])->get();

        if (empty($storeDetail) || empty($storeOrder) || count($storeOrderItems) == 0) {
            throw ValidationException::withMessages([
                        'order_item_id' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }


        $projectDetail = ProjectDetail::where('id', $storeOrder->project_id)->withTrashed()->with('user')->first();

        $requestNumber = $storeOrder->order_number;
        $requestDate = $storeOrder->created_at->format('d.m.Y');

        $pdf = PDF::loadView('pdf.product-request-download-gallery', [
                    'storeOrderItems' => $storeOrderItems,
                    'storeName' => $storeDetail->fundus_name ?? '',
                    'requestNumber' => $requestNumber,
                    'requestDate' => $requestDate,
                    'projectDetail' => [
                        "projectName" => $projectDetail->project_name,
                        "projectCompany" => $projectDetail->company_name,
                        "projectAddressLine1" => $projectDetail->address_line_one ?? '',
                        "projectAddressLine2" => $projectDetail->address_line_two ?? '',
                        "projectEmailId" => $projectDetail->user['email'],
                        "projectContactName" => $projectDetail->user['name']
                    ],
                    'displayImages' => true
        ]);

        $pdf->getDomPDF()->setHttpContext(
                stream_context_create(
                        [
                            'ssl' => [
                                'allow_self_signed' => TRUE,
                                'verify_peer' => FALSE,
                                'verify_peer_name' => FALSE,
                            ]
                        ]
                )
        );

        return $pdf->download('Artikelgalerie_Anfrage_' . $requestNumber . '.pdf');
    }

    public function updateProductCount(Request $request, $id = "") {
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $productItem = StoreOrderProduct::where('id', $id)->first();
        $storeOrder = StoreOrder::where('id', $productItem->order_id)->where('store_id', $storeId)->first();

        if (empty($storeOrder) || empty($productItem)) {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
        if (!empty($productItem)) {
            if ($request->input('action', '') == 'increment') {
                $productItem->increment('quantity');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_INCREMENT'), 'currentValue' => $productItem->quantity]);
            }
            if ($request->input('action', '') == 'decrement') {
                $productItem->decrement('quantity');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_DECREMENT'), 'currentValue' => $productItem->quantity]);
            }
        } else {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

    public function deleteOrderProduct(Request $request, $id = "") {
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $productItem = StoreOrderProduct::where('id', $id)->first();
        if (!empty($productItem)) {
            $storeOrder = StoreOrder::where('id', $productItem->order_id)->where('store_id', $storeId)->first();
        }

        if (empty($storeOrder) || empty($productItem)) {
            return redirect()->route('fundus.inquiries.index')
                            ->with('error_message', __('status_message.INVALID_ARTICLE'));
        }

        $orderItemId = $productItem->order_item_id;
        $orderId = $productItem->order_id;

        $productItemCount = StoreOrderProduct::where('order_item_id', $orderItemId)
                ->where('order_id', $orderId)
                ->with('product', 'addonProduct')
                ->where(function ($query) {
                    $query->has('product')
                    ->orHas('addonProduct');
                })
                ->count();

        $productItem->delete();
        if ($productItemCount == 1) {
            StoreOrderProduct::where('order_item_id', $orderItemId)
                    ->where('order_id', $orderId)->delete();

            $storeOrderItemCount = StoreOrderItem::where('order_id', $orderId)->count();
            StoreOrderItemDateRange::where('order_item_id', $orderItemId)->where('order_id', $orderId)->delete();
            StoreOrderItem::where('id', $orderItemId)->where('order_id', $orderId)->delete();
            if ($storeOrderItemCount == 1) {
                StoreOrder::where('id', $orderId)->delete();
            }
        }

        return redirect()->route('fundus.inquiries.index')
                        ->with('success_message', __('status_message.ARTICLE_DELETED'));
    }

    public function update(Request $request) {
        $request->validate([
            'product_item_id.*' => 'required|numeric',
            'requested_item_count.*' => 'required|numeric|gt:0',
            'unit_price.*' => 'required|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
        ]);

        $productItems = $request->input('product_item_id');
        $unitPrices = $request->input('unit_price');
        $requestedItemCounts = $request->input('requested_item_count');

        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $itemCounts = StoreOrderProduct::whereIn('id', $productItems)
                        ->whereHas('order', function ($query) use ($storeId) {
                            $query->where('store_id', $storeId);
                        })->count();

        if ($itemCounts != count($productItems)) {
            throw ValidationException::withMessages([
                        'error_msg' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        foreach ($productItems as $key => $productItemId) {
            StoreOrderProduct::where('id', $productItemId)->first()->update([
                'quantity' => $requestedItemCounts[$key],
                'unit_price' => $unitPrices[$key],
            ]);
        }

        $request->session()->flash('success_message', __('status_message.MOTIV_REQUEST_UPDATED'));

        $response = ['status' => 'success', 'message' => __('status_message.MOTIV_REQUEST_UPDATED')];
        return response()->json($response, 200);
    }

    public function createProduct(Request $request) {
        $request->validate([
            'product_order_item_id' => 'required',
            'product_image' => 'required|image|max:7168|mimes:jpeg,jpg,png,gif|dimensions:min_width=' . config('app.image_thumbnail_max_width') . ',min_height=' . config('app.image_thumbnail_max_height'),
            'product_name' => 'required',
            'product_description' => 'required',
            'requested_count' => 'required|numeric|gt:0',
            'product_unit_price' => 'required|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
            'replacement_value' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
            'price_per_day' => 'nullable|regex:/(\d*)(?:\.?)(\d+)(?:,\d+)?$/',
                ],
                [
                    'product_image.required' => 'Artikelbild muss hochgeladen werden',
                    'product_image.max' => 'Artikelbild darf maximal 7 MB groß sein.',
                    'product_image.dimensions' => 'Die Mindestgröße Deiner Fotos beträgt ' . config('app.image_thumbnail_max_width') . 'x' . config('app.image_thumbnail_max_height') . ' Pixel.'
                ]
        );

        $userId = Auth::user()->id;
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;
        $orderItemId = $request->input('product_order_item_id');

        $storeOrderItem = StoreOrderItem::where('id', $orderItemId)
                        ->whereHas('order', function ($query) use ($storeId) {
                            $query->where('store_id', $storeId);
                        })->first();

        if (empty($storeOrderItem)) {
            throw ValidationException::withMessages([
                        'error_msg' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        $imageData = [];
        if ($request->hasFile('product_image')) {
            $addWatermark = $request->input('watermark', 'no');
            $imageFileObject = $request->file('product_image');
            $key = 1;

            $productSlug = 'add-on-product-' . str_slug($request->input('product_name') . '-' . $userId . time());
            $imageData = $this->storeProductImage($imageFileObject, $productSlug, $key, $addWatermark);
        }

        $addonProductData = [
            'store_id' => $storeId,
            'name' => $request->input('product_name'),
            'description' => $request->input('product_description'),
            'image' => $imageData['thumbnailFileNameWithPath'] ?? '',
            'img_width' => $imageData['thumbnailWidth'] ?? 0,
            'img_height' => $imageData['thumbnailHeight'] ?? 0,
            'replacement_value' => $request->input('replacement_value'),
            //'price' => $request->input('price_per_day')
        ];

        try {
            DB::beginTransaction();
            $addonProduct = StoreAddonProduct::create($addonProductData);

            $unitPrice = $request->input('product_unit_price');
            $requestedItemCount = $request->input('requested_count');

            StoreOrderProduct::create([
                'order_item_id' => $storeOrderItem->id,
                'order_id' => $storeOrderItem->order['id'],
                'product_id' => 0,
                'addon_product_id' => $addonProduct->id,
                'quantity' => $requestedItemCount,
                'unit_price' => $unitPrice,
            ]);

            DB::commit();
            $request->session()->flash('success_message', __('status_message.MOTIV_REQUEST_ARTICLE_CREATED'));

            $response = ['status' => 'success', 'message' => __('status_message.MOTIV_REQUEST_ARTICLE_CREATED')];
            return response()->json($response, 200);
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw ValidationException::withMessages([
                        'error_msg' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }
    }

    public function addProduct(Request $request) {
        $request->validate([
            'requested_count' => 'required|integer',
            'store_order_item' => 'required',
            'order_product_slug' => 'required'
        ]);

        $slug = $request->input('order_product_slug');
        $storeId = Auth::user()->fundusDetail['id'] ?? 0;

        $product = Product::where('slug', $slug)
                ->where('store_id', $storeId)
                ->first();

        if (!empty($product)) {
            $orderItemId = $request->input('store_order_item', '');
            $requestedItemCount = $request->input('requested_count', 1);

            if ($orderItemId != '') {
                $storeOrderItem = StoreOrderItem::where('id', $orderItemId)
                                ->whereHas('order', function ($query) use ($storeId) {
                                    $query->where('store_id', $storeId);
                                })->first();

                if (!empty($storeOrderItem)) {
                    $storeOrderProductCount = StoreOrderProduct::where('order_item_id', $storeOrderItem->id)
                                    ->where('order_id', $storeOrderItem->order['id'])
                                    ->where('product_id', $product->id)->count();

                    if ($storeOrderProductCount == 0) {
                        StoreOrderProduct::create([
                            'order_item_id' => $storeOrderItem->id,
                            'order_id' => $storeOrderItem->order['id'],
                            'product_id' => $product->id,
                            'addon_product_id' => 0,
                            'quantity' => !empty($requestedItemCount) ? $requestedItemCount : 1,
                        ]);
                    } else {
                        // error response "Article already mapped with store request"
                        throw ValidationException::withMessages([
                                    'requested_count' => [__('status_message.ARTICLE_ALREADY_ADDED_IN_REQUEST')],
                        ]);
                    }
                }

                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_ADDED_IN_REQUEST')]);
            } else {
                // error response "Invalid order item selected, this order item does not exists"
                throw ValidationException::withMessages([
                            'requested_count' => [__('status_message.INVALID_REQUEST_NUMBER')],
                ]);
            }
        } else {
            // error response "Invalid article selected, this article does not exists"
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

    public function destroy(Request $request) {
        $request->validate([
            'order_item_id' => 'required',
            'order_id' => 'required'
                ], [
            'order_item_id.required' => 'Please select atleast one project request.'
        ]);

        $userId = Auth::user()->id;
        $storeDetail = FundusDetail::where('user_id', $userId)->first();

        $storeOrder = StoreOrder::where('id', $request->input('order_id'))
                        ->where('store_id', $storeDetail->id)->first();

        $storeOrderItems = StoreOrderItem::where('order_id', $request->input('order_id'))
                ->whereIn('id', $request->input('order_item_id'))
                ->get();

        if (empty($storeDetail) || empty($storeOrder) || count($storeOrderItems) == 0) {
            return redirect()->route('fundus.inquiries.index')
                            ->with('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
        }

        try {
            DB::beginTransaction();
            StoreOrderItemDateRange::whereIn('order_item_id', $request->input('order_item_id'))->delete();
            StoreOrderProduct::whereIn('order_item_id', $request->input('order_item_id'))->delete();

            StoreOrderItem::where('order_id', $request->input('order_id'))
                    ->whereIn('id', $request->input('order_item_id'))
                    ->delete();

            $orderItemCounts = StoreOrderItem::where('order_id', $request->input('order_id'))->count();
            if ($orderItemCounts == 0) {
                StoreOrder::where('id', $request->input('order_id'))->delete();
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());

            return redirect()->route('fundus.inquiries.index')
                            ->with('error_message', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
        }

        return redirect()->route('fundus.inquiries.index')
                        ->with('success_message', __('status_message.MOTIV_REQUEST_DELETED'));
    }

}
