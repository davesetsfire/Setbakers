<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\FavouriteItem;
use App\Models\FavouriteDateRange;
use Illuminate\Validation\ValidationException;
use Auth;
use App\Models\ProjectDetail;
use App\Models\FavouriteStoreRentDateRange;

class ThemeController extends Controller {

    public function index(Request $request) {
        $userId = Auth::user()->id;
        $userFavourites = Favourite::whereIn('user_id', [0, $userId])
                        ->with([
                            'favouriteItems' => function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                                $query->has('product');
                            },
                            'favouriteItems.product',
                            'favouriteDateRanges',
                            'favouriteItems.product.productcategory.parentcategory.parentcategory', 'favouriteItems.product.productMedia' => function ($query) {
                                $query->select('product_id', 'file_name')
                                ->where('is_primary', 0);
                            },
                            'favouriteItems.product.color',
                            'favouriteItems.product.style',
                            'favouriteItems.product.epocheText',
                            'favouriteItems.product.prices:id,product_id,price,duration_text',
                            'favouriteItems.product.graphicForm',
                            'favouriteItems.product.manufacture',
                            'favouriteItems.product.manufactureCountry',
                            'favouriteItems.product.fileFormat',
                            'favouriteItems.product.copyright',
                            'favouriteItems.product.fundusDetail'
                        ])->oldest('start_date')->get();
        $productFavouriteList = [];
        foreach ($userFavourites as $favourites) {
            foreach ($favourites->favouriteItems as $favouriteItem) {
                $productFavouriteList[$favouriteItem['product_id']][] = $favouriteItem['favourite_id'];
            }
        }

        return view('favourites.theme')
                        ->with('userFavourites', $userFavourites)
                        ->with('showBookmarkSection', false)
                        ->with('productFavouriteList', $productFavouriteList);
    }

    public function store(Request $request) {
        $response = [];
        $userId = Auth::user()->id;
        $projectId = ProjectDetail::where('user_id', $userId)->value('id');

        $request->validate([
            'name' => 'required|string|min:1|max:50',
            'start_date.*' => 'required|date|before_or_equal:end_date.*',
            'end_date.*' => 'required|date|after_or_equal:start_date.*',
                ], [
            'start_date.*.before_or_equal' => 'Anfangsdatum muss vor Enddatum liegen.',
            'end_date.*.after_or_equal' => 'Enddatum muss nach dem Anfangsdatum liegen.'
        ]);

        $inputData = $request->all();

        $dateRanges = [];
        foreach ($inputData['start_date'] as $key => $value) {
            $dateRanges[] = [
                'strtotime' => strtotime($inputData['start_date'][$key]),
                'start_date' => date('Y-m-d', strtotime($inputData['start_date'][$key])),
                'end_date' => date('Y-m-d', strtotime($inputData['end_date'][$key])),
            ];
        }

        $dateKeys = array_column($dateRanges, 'strtotime');
        array_multisort($dateKeys, SORT_ASC, $dateRanges);

        $favourite = Favourite::create([
                    'user_id' => $userId,
                    'project_id' => $projectId,
                    'name' => $request->input('name'),
                    'start_date' => $dateRanges[0]['start_date'],
                    'end_date' => $dateRanges[0]['end_date']
        ]);

        foreach ($dateRanges as $dateRange) {
            FavouriteDateRange::create([
                'favourite_id' => $favourite->id,
                'start_date' => $dateRange['start_date'],
                'end_date' => $dateRange['end_date'],
            ]);
        }
        $request->session()->flash('success_message', __('status_message.MOTIV_CREATED'));

        $response = ['status' => 'success', 'message' => __('status_message.MOTIV_CREATED')];
        return response()->json($response, 200);

        //return redirect()->route('favourites.theme')->with('success_message', __('status_message.MOTIV_CREATED'));
    }

    public function update(Request $request, $id) {
        $userId = Auth::user()->id;

        $favourite = Favourite::where('id', $id)
                ->where('user_id', $userId)
                ->first();

        if (!empty($favourite)) {

            $request->validate([
                'name' => 'required|string|min:1|max:50',
                'range_id' => 'required',
                'start_date.*' => 'required|date|before_or_equal:end_date.*',
                'end_date.*' => 'required|date|after_or_equal:start_date.*',
                    ], [
                'start_date.*.before_or_equal' => 'Anfangsdatum muss vor Enddatum liegen.',
                'end_date.*.after_or_equal' => 'Enddatum muss nach dem Anfangsdatum liegen.'
            ]);

            $inputData = $request->all();

            $dateRanges = [];
            foreach ($inputData['start_date'] as $key => $value) {
                $dateRanges[] = [
                    'strtotime' => strtotime($inputData['start_date'][$key]),
                    'range_id' => $inputData['range_id'][$key],
                    'start_date' => date('Y-m-d', strtotime($inputData['start_date'][$key])),
                    'end_date' => date('Y-m-d', strtotime($inputData['end_date'][$key])),
                ];
            }

            $dateKeys = array_column($dateRanges, 'strtotime');
            array_multisort($dateKeys, SORT_ASC, $dateRanges);

            $favourite->update([
                'name' => $request->input('name'),
                'start_date' => $dateRanges[0]['start_date'],
                'end_date' => $dateRanges[0]['end_date']
            ]);

            $currentDateRanges = FavouriteDateRange::where('favourite_id', $favourite->id)->get();
            $currentDateRangeIds = [];
            $newDateRangeIds = [];

            foreach ($currentDateRanges as $currentDateRange) {
                $currentDateRangeIds[$currentDateRange->id] = strtotime($currentDateRange->start_date) . '_' . strtotime($currentDateRange->end_date);
            }

            foreach ($dateRanges as $dateRange) {
                if (isset($currentDateRangeIds[$dateRange['range_id']])) {
                    $dateRangeKey = strtotime($dateRange['start_date']) . '_' . strtotime($dateRange['end_date']);
                    if ($dateRangeKey != $currentDateRangeIds[$dateRange['range_id']]) {
                        FavouriteDateRange::where('id', $dateRange['range_id'])
                                ->where('favourite_id', $favourite->id)
                                ->update([
                                    'start_date' => $dateRange['start_date'],
                                    'end_date' => $dateRange['end_date'],
                        ]);
                        FavouriteStoreRentDateRange::where('favourite_date_range_id', $dateRange['range_id'])
                                ->where('favourite_id', $favourite->id)
                                ->update(['favourite_date_change_flag' => 1]);
                    }
                } else {
                    FavouriteDateRange::create([
                        'favourite_id' => $favourite->id,
                        'start_date' => $dateRange['start_date'],
                        'end_date' => $dateRange['end_date'],
                    ]);
                }
                $newDateRangeIds[] = $dateRange['range_id'];
            }

            foreach ($currentDateRangeIds as $currentDateRangeId => $currentDateRange) {
                if (!in_array($currentDateRangeId, array_values($newDateRangeIds))) {
                    FavouriteDateRange::where('favourite_id', $favourite->id)
                            ->where('id', $currentDateRangeId)->get()->each->delete();
                    FavouriteStoreRentDateRange::where('favourite_id', $favourite->id)
                            ->where('favourite_date_range_id', $currentDateRangeId)->delete();
                }
            }

            $request->session()->flash('success_message', __('status_message.MOTIV_UPDATED'));

            $response = ['status' => 'success', 'message' => __('status_message.MOTIV_UPDATED')];
            return response()->json($response, 200);
        } else {
            throw ValidationException::withMessages([
                        'name' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }
    }

    public function destroy(Request $request, $id) {
        $userId = Auth::user()->id;

        $favourite = Favourite::where('id', $id)
                ->where('user_id', $userId)
                ->first();

        if (!empty($favourite)) {
            FavouriteDateRange::where('favourite_id', $favourite->id)->delete();
            $favourite->delete();
        } else {
            return redirect()->route('favourites.theme');
        }

        return redirect()->route('favourites.theme')
                        ->with('success_message', __('status_message.MOTIV_DELETED'));
    }

}
