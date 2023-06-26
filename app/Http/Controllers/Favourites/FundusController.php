<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\FavouriteItem;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use App\Models\StoreOrderProduct;
use App\Models\StoreOrderItemDateRange;
use App\Models\ProjectDetail;
use App\Models\FundusDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Auth;
use PDF;
use Mail;
use Carbon;
use App\Models\FavouriteStoreChangeRequest;
use App\Models\FavouriteStoreRentDateRange;
use App\Models\FavouriteDateRange;
use App\Models\FavouriteItemStoreMessage;

class FundusController extends Controller {

    public function index(Request $request) {
        $userId = Auth::user()->id;
        $userFavourites = FavouriteItem::where('user_id', $userId)
                ->with([
                    'product', 'store',
                    'favourite' => function ($query) {
                        $query->oldest('start_date');
                    },
                    'favourite.favouriteDateRanges',
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
                ->has('product')
                ->has('store')
                //->orderBy('favourite_id')
                ->get();

        $productFavouriteList = [];
        $storeMaster = [];
        $favouriteMaster = [];
        $favouritesByFundus = [];
        $bookmarkExists = 0;

        //dd($userFavourites->toArray());
        $favouriteIds = [];
        foreach ($userFavourites as $item) {
            if (isset($item->favourite['user_id']) && $item->favourite['user_id'] != 0) {
                $storeMaster[$item->store_id] = $item->store;
                $favouriteMaster[$item->favourite_id] = $item->favourite;

                $favouritesByFundus[$item->store_id][$item->favourite_id][] = $item;
            } else {
                $bookmarkExists = 1;
            }

            $productFavouriteList[$item->product_id][] = $item->favourite_id;
            $favouriteIds[$item->favourite_id] = 1;
        }

        $favouriteStoreChangeRequests = [];
        $favouriteChangeRequests = FavouriteStoreChangeRequest::where('user_id', $userId)->get();
        foreach ($favouriteChangeRequests as $favouriteChangeRequest) {
            $favouriteStoreChangeRequests[$favouriteChangeRequest->store_id][$favouriteChangeRequest->favourite_id] = $favouriteChangeRequest->status;
        }

        $favouriteStoreDateRanges = [];
        if (!empty($favouriteIds)) {
            $favouriteStoreDateRangeObject = FavouriteStoreRentDateRange::whereIn('favourite_id', array_keys($favouriteIds))->get();
            foreach ($favouriteStoreDateRangeObject as $favouriteStoreDateRangeItem) {
                $favouriteStoreDateRanges[$favouriteStoreDateRangeItem->store_id][$favouriteStoreDateRangeItem->favourite_date_range_id] = $favouriteStoreDateRangeItem;
            }
        }

        //dd($userFavourites->toArray());
        return view('favourites.fundus')
                        ->with('favouritesByFundus', $favouritesByFundus)
                        ->with('storeMaster', $storeMaster)
                        ->with('favouriteMaster', $favouriteMaster)
                        ->with('bookmarkExists', $bookmarkExists)
                        ->with('showBookmarkSection', false)
                        ->with('productFavouriteList', $productFavouriteList)
                        ->with('favouriteStoreChangeRequests', $favouriteStoreChangeRequests)
                        ->with('favouriteStoreDateRanges', $favouriteStoreDateRanges);
    }

    public function generateStoreOrder(Request $request) {
        $request->validate([
            'favourite_id' => 'required',
            'store_id' => 'required'
                ], [
            'favourite_id.required' => 'Bitte wähle mindestens ein Motiv aus.'
        ]);

        $userId = \Auth::user()->id;
        $favouriteIds = $request->input('favourite_id');
        $storeId = $request->input('store_id');
        $finalSubmit = $request->input('final_submit', "no");
        $projectDetail = ProjectDetail::where('user_id', $userId)->first();
        $storeDetail = FundusDetail::where('id', $storeId)->first();

        $favourites = Favourite::whereIn('id', $favouriteIds)
                ->where('user_id', $userId)->with(
                        [
                            'favouriteItems' => function ($query) use ($storeId) {
                                $query->where('store_id', $storeId);
                            },
                            'favouriteDateRanges',
                            'favouriteDateRanges.favouriteStoreRentDateRange' => function ($query) use ($storeId) {
                                $query->where('store_id', $storeId);
                            }
                        ])
                ->oldest('start_date')
                ->get();

        if (empty($storeDetail) || empty($favourites)) {
            throw ValidationException::withMessages([
                        'favourite_id' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        $storeMessage = FavouriteItemStoreMessage::where('user_id', $userId)
                        ->where('store_id', $storeId)->value('message');

        if ($finalSubmit != "yes") {
            $validateStatus = $this->validateAllStoreRequestData($favouriteIds, $storeId, $favourites, $storeDetail);
            //if (!$validateStatus) {
                $favouritePopupData = $this->getStoreRequestPopupData($favourites);

                throw ValidationException::withMessages([
                            'validateStatus' => $validateStatus,
                            'dateRanges' => $favouritePopupData,
                            'storeMessage' => $storeMessage
                ]);
            //}
        }

        $storeMessageInput = $request->input('storeMessage', '');

        try {
            DB::beginTransaction();
            $storeOrder = StoreOrder::create([
                        'project_id' => $projectDetail->id,
                        'store_id' => $storeId
            ]);

            $requestNumber = 'SET' . str_pad($storeOrder->id, 7, '0', STR_PAD_LEFT);
            $requestDate = date('d.m.Y');
            $storeOrder->order_number = $requestNumber;
            $storeOrder->save();

            foreach ($favourites as $favourite) {
                $storeOrderItem = StoreOrderItem::create([
                            'order_id' => $storeOrder->id,
                            'favourite_id' => $favourite->id,
                            'rent_start_date' => $favourite->start_date,
                            'rent_end_date' => $favourite->end_date,
                            'favourite_name' => $favourite->name
                ]);

                foreach ($favourite->favouriteDateRanges as $favouriteDateRanges) {
                    $pickupDate = $favouriteDateRanges['start_date'];
                    $returnDate = $favouriteDateRanges['end_date'];
                    $isActive = 1;
                    foreach ($favouriteDateRanges['favouriteStoreRentDateRange'] as $dateRange) {
                        $isActive = $dateRange['is_active'];
                        $pickupDate = $dateRange['pickup_date'] ?? $pickupDate;
                        $returnDate = $dateRange['return_date'] ?? $returnDate;
                    }
                    if ($isActive == 1) {
                        StoreOrderItemDateRange::create([
                            'order_item_id' => $storeOrderItem->id,
                            'order_id' => $storeOrder->id,
                            'start_date' => $pickupDate,
                            'end_date' => $returnDate
                        ]);
                    }
                }

                foreach ($favourite['favouriteItems'] as $favouriteProduct) {
                    $storeOrderProduct = StoreOrderProduct::create([
                                'order_item_id' => $storeOrderItem->id,
                                'order_id' => $storeOrder->id,
                                'product_id' => $favouriteProduct['product_id'],
                                'quantity' => $favouriteProduct['requested_count']
                    ]);
                }
                //$favourite->request_to_store = 1;
                //$favourite->save();

                FavouriteStoreRentDateRange::where('favourite_id', $favourite->id)
                        ->where('store_id', $storeId)
                        ->update(['favourite_date_change_flag' => 0]);

                FavouriteStoreChangeRequest::updateOrCreate(
                        [
                            'favourite_id' => $favourite->id,
                            'store_id' => $storeId,
                            'user_id' => $userId
                        ],
                        [
                            'status' => 1,
                            'request_sent_at' => date('Y-m-d H:i:s')
                        ]
                );
            }

            FavouriteItemStoreMessage::where('user_id', $userId)
                    ->where('store_id', $storeId)->delete();

            $emailToProjectData["email"] = \Auth::user()->email;
            $emailToProjectData["name"] = \Auth::user()->name;
            $emailToProjectData["subject"] = "${requestNumber} Du hast eine neue Anfrage über SetBakers gesendet!";
            $emailToProjectData["requestNumber"] = $requestNumber;
            $emailToProjectData["storeName"] = $storeDetail->fundus_name ?? '';
            $emailToProjectData["storeMessage"] = $storeMessageInput ?? '';

            $emailToStoreData["email"] = $storeDetail->fundus_email;
            $emailToStoreData["name"] = $storeDetail->fundus_owner_name;
            $emailToStoreData["subject"] = "${requestNumber} Du hast eine neue Anfrage über SetBakers erhalten!";
            $emailToStoreData["requestNumber"] = $requestNumber;
            $emailToStoreData["projectName"] = $projectDetail->project_name;
            $emailToStoreData["projectCompany"] = $projectDetail->company_name;
            $emailToStoreData["projectAddressLine1"] = $projectDetail->address_line_one ?? '';
            $emailToStoreData["projectAddressLine2"] = $projectDetail->address_line_two ?? '';
            $emailToStoreData["projectEmailId"] = \Auth::user()->email;
            $emailToStoreData["projectContactName"] = \Auth::user()->name;
            $emailToStoreData["storeMessage"] = $storeMessageInput ?? '';

            $requestData = $this->getProductRequestData($request);
            $storeMaster = $requestData['storeMaster'];
            $favouriteMaster = $requestData['favouriteMaster'];
            $favouritesByFundus = $requestData['favouritesByFundus'];

            $pdf = PDF::loadView('pdf.product-request-attachment', [
                        'favouritesByFundus' => $favouritesByFundus,
                        'storeMaster' => $storeMaster,
                        'favouriteMaster' => $favouriteMaster,
                        'favouriteDateMaster' => $requestData['favouriteDateMaster'],
                        'storeName' => $storeDetail->fundus_name ?? '',
                        'requestNumber' => $requestNumber,
                        'requestDate' => $requestDate,
                        'projectDetail' => [
                            "projectName" => $projectDetail->project_name,
                            "projectCompany" => $projectDetail->company_name,
                            "projectAddressLine1" => $projectDetail->address_line_one ?? '',
                            "projectAddressLine2" => $projectDetail->address_line_two ?? '',
                            "projectEmailId" => \Auth::user()->email,
                            "projectContactName" => \Auth::user()->name
                        ],
                        'displayImages' => false
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

            Mail::send('emails.article-request-mail-to-store', $emailToStoreData, function ($message)use ($emailToStoreData, $pdf) {
                $message->to($emailToStoreData["email"], $emailToStoreData["name"])
                        ->subject($emailToStoreData["subject"])
                        ->attachData($pdf->output(), "Anfrage_" . $emailToStoreData["requestNumber"] . ".pdf");
            });

            $pdf = PDF::loadView('pdf.product-request-attachment', [
                        'favouritesByFundus' => $favouritesByFundus,
                        'storeMaster' => $storeMaster,
                        'favouriteMaster' => $favouriteMaster,
                        'favouriteDateMaster' => $requestData['favouriteDateMaster'],
                        'storeName' => $storeDetail->fundus_name ?? '',
                        'requestNumber' => $requestNumber,
                        'requestDate' => $requestDate,
                        'projectDetail' => [
                            "projectName" => $projectDetail->project_name,
                            "projectCompany" => $projectDetail->company_name,
                            "projectAddressLine1" => $projectDetail->address_line_one ?? '',
                            "projectAddressLine2" => $projectDetail->address_line_two ?? '',
                            "projectEmailId" => \Auth::user()->email,
                            "projectContactName" => \Auth::user()->name
                        ],
                        'displayImages' => false
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

            Mail::send('emails.article-request-mail-to-project', $emailToProjectData, function ($message)use ($emailToProjectData, $pdf) {
                $message->to($emailToProjectData["email"], $emailToProjectData["name"])
                        ->subject($emailToProjectData["subject"])
                        ->attachData($pdf->output(), "Anfrage_" . $emailToProjectData["requestNumber"] . ".pdf");
            });

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw ValidationException::withMessages([
                        'favourite_id' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        $request->session()->flash('success_message', __('status_message.REQUEST_STORE_ITEMS'));
        $response = ['status' => 'success', 'message' => 'success'];
        return response()->json($response, 200);
//        return redirect()->route('favourites.fundus')
//                        ->with('success_message', __('status_message.REQUEST_STORE_ITEMS'));
    }

    public function downloadFavourite(Request $request, $storeId = "") {
        $request->validate([
            'favourite_id' => 'required',
            'store_id' => 'required'
                ], [
            'favourite_id.required' => 'Bitte wähle mindestens ein Motiv aus.'
        ]);

        if (!empty($storeId)) {
            $userId = Auth::user()->id;

            $requestData = $this->getProductRequestData($request);
            if (!empty($requestData)) {

                $storeMaster = $requestData['storeMaster'];
                $favouriteMaster = $requestData['favouriteMaster'];
                $favouritesByFundus = $requestData['favouritesByFundus'];

                $pdf = PDF::loadView('pdf.product-list-download', [
                            'favouritesByFundus' => $favouritesByFundus,
                            'storeMaster' => $storeMaster,
                            'favouriteMaster' => $favouriteMaster,
                            'favouriteDateMaster' => $requestData['favouriteDateMaster'],
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

                return $pdf->download('Artikelliste.pdf');

//                return view('pdf.product-list-download')
//                                ->with('favouritesByFundus', $favouritesByFundus)
//                                ->with('storeMaster', $storeMaster)
//                                ->with('favouriteMaster', $favouriteMaster);
            }
        }
    }

    public function getProductRequestData($request) {
        $requestData = [];
        $userId = Auth::user()->id;
        $storeId = $request->input('store_id');
        $userFavourites = FavouriteItem::where('user_id', $userId)
                ->where('store_id', $storeId)
                ->whereIn('favourite_id', $request->input('favourite_id'))
                ->whereHas('product')
                ->with(['product', 'store', 'favourite', 'favourite.favouriteDateRanges',
                    'favourite.favouriteDateRanges.favouriteStoreRentDateRange',
                    'product.productcategory.parentcategory.parentcategory',
                    'product.productMedia:id,product_id,file_name,is_primary',
                    'product.color',
                    'product.style',
                    'product.epocheText',
                    'product.prices:id,product_id,price,duration_text'])
                ->orderBy('favourite_id')
                ->get();

        if (!empty($userFavourites)) {

            $storeMaster = [];
            $favouriteMaster = [];
            $favouritesByFundus = [];
            $favouriteDateMaster = [];

            foreach ($userFavourites as $item) {
                $storeMaster[$item->store_id] = $item->store;
                $favouriteMaster[$item->favourite_id] = $item->favourite;

                $favouritesByFundus[$item->store_id][$item->favourite_id][] = $item;
            }

            foreach ($favouritesByFundus as $storeId => $stores) {
                foreach ($stores as $favouriteId => $favouriteItems) {
                    if ($favouriteMaster[$favouriteId]->user_id != 0) {
                        $totalDays = 0;
                        foreach ($favouriteMaster[$favouriteId]['favouriteDateRanges'] as $favouriteDateRanges) {
                            $pickupDate = $favouriteDateRanges['start_date'];
                            $returnDate = $favouriteDateRanges['end_date'];
                            $isActive = 1;
                            foreach ($favouriteDateRanges['favouriteStoreRentDateRange'] as $key => $dateRange) {
                                $isActive = $dateRange['is_active'];
                                if ($dateRange['is_active'] == 1) {
                                    $pickupDate = $dateRange['pickup_date'] ?? $pickupDate;
                                    $returnDate = $dateRange['return_date'] ?? $returnDate;
                                }
                            }
                            if ($isActive == 1) {
                                $totalDays += Carbon\Carbon::parse($returnDate)->diffInDays($pickupDate) + 1;
                                if ($pickupDate->format('d.m.Y') == $returnDate->format('d.m.Y')) {
                                    $favouriteDateMaster[$favouriteId]['date_range'][] = $pickupDate->format('d.m.Y');
                                } else {
                                    $favouriteDateMaster[$favouriteId]['date_range'][] = $pickupDate->format('d.m.Y') . ' - ' . $returnDate->format('d.m.Y');
                                }
                            }
                        }
                        $favouriteDateMaster[$favouriteId]['days'] = $totalDays;
                    }
                }
            }

            $requestData['storeMaster'] = $storeMaster;
            $requestData['favouriteMaster'] = $favouriteMaster;
            $requestData['favouritesByFundus'] = $favouritesByFundus;
            $requestData['favouriteDateMaster'] = $favouriteDateMaster;
        }

        return $requestData;
    }

    public function updateProductCount(Request $request, $id = "") {
        $productItem = FavouriteItem::where('id', $id)->where('user_id', Auth::user()->id)->first();

        if (!empty($productItem)) {
            if ($request->input('action', '') == 'increment') {
                $productItem->increment('requested_count');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_INCREMENT'), 'currentValue' => $productItem->requested_count]);
            }
            if ($request->input('action', '') == 'decrement') {
                $productItem->decrement('requested_count');
                return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_COUNT_DECREMENT'), 'currentValue' => $productItem->requested_count]);
            }
        } else {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

    public function changeFavourite(Request $request) {
        $request->validate([
            'selected_favourite_id' => 'nullable',
            'favourite_item_id' => 'required'
                ], [
            'selected_favourite_id.required' => 'Bitte wähle mindestens ein Motiv aus.'
        ]);

        $favouriteIds = $request->input('selected_favourite_id');
        $requestedItemCounts = $request->input('requested_item_count');
        $currentEditMode = $request->input('current_edit_mode', 'theme');

        $productItem = FavouriteItem::where('id', $request->input('favourite_item_id'))->where('user_id', Auth::user()->id)->first();

        if (empty($productItem)) {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }

        $marklistFavouriteId = Favourite::where('user_id', 0)->value('id');

        //No favourite selected
        if (empty($favouriteIds)) {
            /** Remove Article from the current favourite  of current user * */
//            FavouriteItem::where('favourite_id', $productItem->favourite_id)
//                    ->where('product_id', $productItem->product_id)
//                    ->where('user_id', Auth::user()->id)->delete();

            /** Remove Article from the all favourites of current user * */
            $queryFavouriteItem = FavouriteItem::where('product_id', $productItem->product_id)
                    ->where('user_id', Auth::user()->id);
            if (!empty($currentEditMode) && $currentEditMode == 'fundus') {
                $queryFavouriteItem->where('favourite_id', '!=', $marklistFavouriteId);
            }
            $queryFavouriteItem->get()->each->delete();

            //$this->updateRequestToStoreFlag($productItem->favourite_id);

            $response = ['status' => 'success', 'message' => 'success'];
            return response()->json($response, 200);
        }

        $favourite = Favourite::whereIn('id', $favouriteIds)->whereIn('user_id', [0, Auth::user()->id])->get();

        if (empty($favourite) || count($favourite) < count($favouriteIds)) {
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }

        if ($requestedItemCounts == 0) {
            FavouriteItem::where('favourite_id', $productItem->favourite_id)
                    ->where('product_id', $productItem->product_id)
                    ->where('user_id', Auth::user()->id)->get()->each->delete();
        } else {
//            $productItemAlreadyMapped = FavouriteItem::whereIn('favourite_id', $favouriteIds)
//                            ->where('product_id', $productItem->product_id)
//                            ->where('user_id', Auth::user()->id)->get();

            $productItemAlreadyMapped = FavouriteItem::where('product_id', $productItem->product_id)
                            ->where('user_id', Auth::user()->id)->get();

//        if (!empty($productItemAlreadyMapped) && count($favourite) == count($productItemAlreadyMapped)) {
//            throw ValidationException::withMessages([
//                        'requested_count' => [__('status_message.ARTICLE_ALREADY_MAPPED')],
//            ]);
//        }

            $mappedProductItems = [];
            foreach ($productItemAlreadyMapped as $mappedItem) {
                $mappedProductItems[$mappedItem->favourite_id] = $mappedItem;
            }

            foreach ($favouriteIds as $favouriteId) {
                if (isset($mappedProductItems[$favouriteId])) {
                    if ($mappedProductItems[$favouriteId]->id == $productItem->id) {
                        $favouriteItemObject = FavouriteItem::where('id', $mappedProductItems[$favouriteId]->id)->first();
                        if (!empty($favouriteItemObject)) {
                            $favouriteItemObject->requested_count = $requestedItemCounts;
                            $favouriteItemObject->update();
                        }
                    }
                } else {
                    FavouriteItem::create([
                        'user_id' => Auth::user()->id,
                        'favourite_id' => $favouriteId,
                        'store_id' => $productItem->store_id,
                        'product_id' => $productItem->product_id,
                        'requested_count' => 1
                            //'requested_count' => $requestedItemCounts
                    ]);
                }
            }

            $removeFavouriteItems = array_diff(array_keys($mappedProductItems), $favouriteIds);
            if (count($removeFavouriteItems) > 0) {
                if (!empty($currentEditMode) && $currentEditMode == 'fundus') {
                    $removeFavouriteItems = array_diff($removeFavouriteItems, [$marklistFavouriteId]);
                }

                if (count($removeFavouriteItems) > 0) {
                    FavouriteItem::whereIn('favourite_id', $removeFavouriteItems)
                            ->where('product_id', $productItem->product_id)
                            ->where('user_id', Auth::user()->id)->get()->each->delete();
                }
            }
        }

        $response = ['status' => 'success', 'message' => 'success'];
        return response()->json($response, 200);
    }

    public function changeRentDate(Request $request) {
        $request->validate([
            'pickup_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'favourite_date_id' => 'required',
            'favourite_store_id' => 'required',
                ], [
        ]);

        $userId = Auth::user()->id;
        $favouriteDateId = $request->input('favourite_date_id');
        $storeId = $request->input('favourite_store_id');
        $pickupDate = $request->input('pickup_date');
        $returnDate = $request->input('return_date');
        $activateStatus = $request->input('active_status');
        $dateChanged = false;

        if (empty($pickupDate) && empty($returnDate) && empty($activateStatus)) {
            throw ValidationException::withMessages([
                        'error' => [__('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        $favouriteDateRange = FavouriteDateRange::where('id', $favouriteDateId)
                ->with(['favourite' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }])
                ->first();

        if (empty($favouriteDateRange->favourite)) {
            throw ValidationException::withMessages([
                        'error' => [__('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        if (!empty($pickupDate) && strtotime($pickupDate) > strtotime($favouriteDateRange->end_date)) {
            throw ValidationException::withMessages([
                        'pickup_date' => [__('custom_validation.STORE_PICKUP_DATE_LESS_THAN_SHOOTING_END_DATE')],
            ]);
        }

        if (!empty($activateStatus) && $activateStatus == 'no') {
            $totalFavouriteDateRangeCounts = FavouriteDateRange::where('favourite_id', $favouriteDateRange->favourite_id)->count();
            $totalActiveFavouriteStoreRentDateRangeCounts = FavouriteStoreRentDateRange::where('favourite_id', $favouriteDateRange->favourite_id)
                            ->where('store_id', $storeId)
                            ->where('is_active', 0)->count();
            if ($totalActiveFavouriteStoreRentDateRangeCounts >= ($totalFavouriteDateRangeCounts - 1)) {
                throw ValidationException::withMessages([
                            'active_status' => [__('custom_validation.STORE_RENT_ONE_SHOOTING_PERIOD_MANDATORY')],
                ]);
            }
        }

        $storeRentDateRange = FavouriteStoreRentDateRange::where('favourite_date_range_id', $favouriteDateId)
                ->where('store_id', $storeId)
                ->with(['favourite' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }])
                ->first();

        if (!empty($returnDate) && !empty($storeRentDateRange->pickup_date) && strtotime($returnDate) <= strtotime($storeRentDateRange->pickup_date)) {
            throw ValidationException::withMessages([
                        'return_date' => [__('custom_validation.STORE_RETURN_DATE_GREATER_THAN_SHOOTING_END_DATE')],
            ]);
        }

        if (empty($storeRentDateRange->favourite)) {
            $storeRentDateRange = new FavouriteStoreRentDateRange();
            $storeRentDateRange->favourite_date_range_id = $favouriteDateId;
            $storeRentDateRange->favourite_id = $favouriteDateRange->favourite_id;
            $storeRentDateRange->store_id = $storeId;
        }

        if (!empty($pickupDate)) {
            $storeRentDateRange->pickup_date = $pickupDate;
            $storeRentDateRange->favourite_date_change_flag = 0;
            $dateChanged = true;
        }

        if (!empty($returnDate)) {
            $storeRentDateRange->return_date = $returnDate;
            $storeRentDateRange->favourite_date_change_flag = 0;
            $dateChanged = true;
        }

        if (!empty($activateStatus)) {
            $storeRentDateRange->is_active = $activateStatus == 'yes' ? 1 : 0;
        }

        $storeRentDateRange->save();

        if ($dateChanged) {
            FavouriteStoreChangeRequest::where('favourite_id', $favouriteDateRange->favourite_id)
                    ->where('user_id', $userId)
                    ->where('status', 1)
                    ->update(['status' => 2]);
        }

        $response = ['status' => 'success', 'message' => 'success', 'is_active' => $storeRentDateRange->is_active];
        return response()->json($response, 200);
    }

    public function storeMessage(Request $request) {
        $message = $request->input('message');
        $storeId = $request->input('store_id');
        $message = $message ?? '';

        FavouriteItemStoreMessage::updateOrCreate(
                ['user_id' => Auth::user()->id, 'store_id' => $storeId],
                ['message' => $message]
        );

        $response = ['status' => 'success', 'message' => 'success'];
        return response()->json($response, 200);
    }

    private function validateAllStoreRequestData($favouriteIds, $storeId, $favourites, $storeDetail) {
        $validateStatus = true;
        if (count($favourites) > 0) {
            foreach ($favourites as $favouriteItem) {
                if (!empty($favouriteItem->favouriteDateRanges)) {
                    foreach ($favouriteItem->favouriteDateRanges as $favouriteDateRangeItem) {
                        if (empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0])) {
                            $validateStatus = false;
                            break;
                        }
                        if ($favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['is_active'] == 1 &&
                                (empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['pickup_date']) ||
                                empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['return_date']) ||
                                $favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['favourite_date_change_flag'] == 1)) {
                            $validateStatus = false;
                            break;
                        }
                    }
                } else {
                    $validateStatus = false;
                    break;
                }
            }
        } else {
            $validateStatus = false;
        }

        return $validateStatus;
    }

    private function getStoreRequestPopupData($favourites) {
        $storeRequestPopupData = [];
        if (count($favourites) > 0) {
            foreach ($favourites as $favouriteItem) {
                $storeRequestRow = ['id' => $favouriteItem->id, 'name' => $favouriteItem->name];
                if (!empty($favouriteItem->favouriteDateRanges)) {
                    foreach ($favouriteItem->favouriteDateRanges as $favouriteDateRangeItem) {
                        $shootingPeriod = '';
                        if ($favouriteDateRangeItem['start_date'] == $favouriteDateRangeItem['end_date']) {
                            $shootingPeriod = $favouriteDateRangeItem['start_date']->format('d.m.Y');
                        } else {
                            $shootingPeriod = $favouriteDateRangeItem['start_date']->format('d.m.Y') . ' - ' . $favouriteDateRangeItem['end_date']->format('d.m.Y');
                        }
                        $favouriteDateRangeArray = Array(
                            'dateRangeId' => $favouriteDateRangeItem->id,
                            'shooting_period' => $shootingPeriod,
                            'favourite_date_change_flag' => 0,
                            'is_active' => 1
                        );
                        if (!empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0])) {
                            $favouriteDateRangeArray['pickup_date'] = !empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['pickup_date']) ?
                                    $favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['pickup_date']->format('d.m.Y') : '';
                            $favouriteDateRangeArray['return_date'] = !empty($favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['return_date']) ?
                                    $favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['return_date']->format('d.m.Y') : '';
                            $favouriteDateRangeArray['favourite_date_change_flag'] = $favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['favourite_date_change_flag'] ?? '';
                            $favouriteDateRangeArray['is_active'] = $favouriteDateRangeItem['favouriteStoreRentDateRange'][0]['is_active'] ?? '';
                        }
                        if ($favouriteDateRangeArray['is_active'] == 1 && ($favouriteDateRangeArray['favourite_date_change_flag'] == 1 || empty($favouriteDateRangeArray['pickup_date']) || empty($favouriteDateRangeArray['return_date']))) {
                            $storeRequestRow['favouriteDateRanges'][] = $favouriteDateRangeArray;
                        }
                    }
                    $storeRequestPopupData[] = $storeRequestRow;
                }
            }
        }

        return $storeRequestPopupData;
    }

}
