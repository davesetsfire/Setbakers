<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundusDetail;
use App\Models\ProjectDetail;
use App\Models\FundusActivityLog;
use App\Models\ProjectActivityLog;
use App\Models\User;
use App\Http\Requests\ProfilePostRequest;
use App\Http\Requests\ProfileStoreRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Auth;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Traits\Paypal\PaypalFunctions;
use App\Traits\Notifications\EmailNotifications;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProjectCancelSubscriptionNotification;
use App\Models\OrderDetail;
use App\Models\SubscriptionHistory;
use Session;

class ProfileController extends Controller {

    use PaypalFunctions,
        EmailNotifications;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStoreRequest $request) {

        $userId = \Auth::user()->id;
        if ($request->input('upgrade') == 'project') {
            $projectData = $request->only(['project_name', 'company_name', 'ust_id', 'house_number',
                'street', 'postal_code', 'location', 'country', 'project_company_account']);

            $isCompany = $projectData['project_company_account'] == 'firma_checked' ? 1 : 0;

            unset($projectData['project_company_account']);
            $projectData['is_company'] = $isCompany;

            if ($isCompany) {
                $projectData['company_name'] = $projectData['company_name'] ?? '';
                $projectData['ust_id'] = $projectData['ust_id'] ?? '';
            } else {
                $projectData['company_name'] = '';
                $projectData['ust_id'] = '';
            }

            ProjectDetail::updateOrCreate(['user_id' => $userId], $projectData);

            User::where('id', $userId)->update(['account_type' => 'complete']);
            $request->session()->flash('showPaymentPopup', 'true');
            $response = ['status' => 'success', 'message' => 'upgraded to complete account'];

            return response()->json($response, 200);
        } else if ($request->input('upgrade') == 'new-project') {
            $projectData = $request->only(['project_name', 'company_name', 'ust_id', 'house_number',
                'street', 'postal_code', 'location', 'country', 'project_company_account']);

            $isCompany = $projectData['project_company_account'] == 'firma_checked' ? 1 : 0;

            unset($projectData['project_company_account']);
            $projectData['is_company'] = $isCompany;

            if ($isCompany) {
                $projectData['company_name'] = $projectData['company_name'] ?? '';
                $projectData['ust_id'] = $projectData['ust_id'] ?? '';
            } else {
                $projectData['company_name'] = '';
                $projectData['ust_id'] = '';
            }

            $projectData['user_id'] = $userId;
            ProjectDetail::where('user_id', $userId)->delete();
            ProjectDetail::create($projectData);

            $request->session()->flash('showPaymentPopup', 'true');
            $response = ['status' => 'success', 'message' => 'New project has been created'];

            return response()->json($response, 200);
        } else if ($request->input('upgrade') == 'store') {

            $data = $request->only(['fundus_name', 'fundus_email', 'fundus_phone', 'fundus_owner_first_name',
                'fundus_owner_last_name', 'fundus_company_name', 'fundus_ust_id', 'fundus_website', 'fundus_house_number',
                'fundus_street', 'fundus_postal_code', 'fundus_location', 'fundus_geo_location', 'fundus_country', 'fundus_company_account']);

            $isCompany = $data['fundus_company_account'] == 'fundusdaten_firma_checked' ? 1 : 0;

            if ($isCompany == 0) {
                $data['fundus_company_name'] = '';
                $data['fundus_ust_id'] = '';
            }

            $fundusData = [
                'fundus_name' => $data['fundus_name'],
                'fundus_email' => $data['fundus_email'],
                'fundus_phone' => $data['fundus_phone'] ?? '',
                'owner_first_name' => $data['fundus_owner_first_name'],
                'owner_last_name' => $data['fundus_owner_last_name'],
                'description' => $data['fundus_description'] ?? '',
                'company_name' => $data['fundus_company_name'] ?? '',
                'ust_id' => $data['fundus_ust_id'] ?? '',
                'is_company' => $isCompany,
                'website' => $data['fundus_website'],
                'house_number' => $data['fundus_house_number'],
                'street' => $data['fundus_street'],
                'postal_code' => $data['fundus_postal_code'],
                'location' => $data['fundus_location'],
                'geo_location' => !empty($data['fundus_geo_location']) ? DB::raw('ST_GeomFromText(\'' . $data['fundus_geo_location'] . '\')') : NULL,
                'country' => $data['fundus_country'],
            ];

            FundusDetail::updateOrCreate(['user_id' => $userId], $fundusData);

            $storeData["email"] = \Auth::user()->email;
            $storeData["name"] = \Auth::user()->name;
            $storeData["subject"] = "Dein Funduskonto";

            Mail::send('emails.setup-store-account', $storeData, function ($message) use ($storeData) {
                $message->to($storeData["email"], $storeData["name"])
                        ->subject($storeData["subject"]);
            });

            $response = ['status' => 'success', 'message' => 'upgraded to store account'];

            return response()->json($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $userId = \Auth::user()->id;

        $projectDetail = ProjectDetail::where('user_id', $userId)->with('subscription')->first();
        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        $fundusSubsEndDate = '';
        $currentPackage = $fundusDetail->package_type ?? '';

        if (!empty($fundusDetail->subscription_end_date)) {
            $fundusSubsEndDate = strtotime($fundusDetail->subscription_end_date);
            if (date('Y-m-d', $fundusSubsEndDate) > date('Y-m-d')) {
                $fundusSubsEndDate = date('d.m.Y', $fundusSubsEndDate);
            } else {
                $fundusSubsEndDate = date('d.m.Y');
            }
        }

        return view('users.show', compact(['projectDetail', 'fundusDetail', 'fundusSubsEndDate', 'currentPackage']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $userId = \Auth::user()->id;

        $projectDetail = ProjectDetail::where('user_id', $userId)->first();
        $fundusDetail = FundusDetail::selectRaw('cm_fundus_details.*, ST_AsText(geo_location) as geo_loc')->where('user_id', $userId)->first();

        return view('users.edit')->with('projectDetail', $projectDetail)
                        ->with('fundusDetail', $fundusDetail);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfilePostRequest $request, $id) {
        $userId = \Auth::user()->id;
        $userData = $request->only(['first_name', 'last_name', 'phone_number']);

        if ($id == 'project') {
            $projectData = $request->only(['project_name', 'company_name', 'ust_id', 'house_number',
                'street', 'postal_code', 'location', 'country']);

            User::where('id', $userId)->update([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone_number' => $userData['phone_number'] ?? ''
            ]);

            $projectData['company_name'] = $projectData['company_name'] ?? '';
            $projectData['ust_id'] = $projectData['ust_id'] ?? '';
            ProjectDetail::updateOrCreate(['user_id' => $userId], $projectData);
        }

        if ($id == 'fundus') {
            $fundusData = $request->only(['fundus_name', 'fundus_email', 'fundus_phone', 'owner_first_name',
                'owner_last_name', 'description', 'company_name', 'ust_id', 'website', 'house_number',
                'street', 'postal_code', 'location', 'geo_location', 'country', 'logo_image_path']);

            $thumbnailSize = ['width' => 200, 'height' => 200];
            if ($request->hasFile('fundus_profile_picture')) {
                $imageFileObject = $request->file('fundus_profile_picture');
                $imageFileExtension = strtolower($imageFileObject->extension());
                $imageFileName = 'logo-fundus-' . $userId . '-' . time() . '.' . $imageFileExtension;
                $folderName = 'logos/' . date('Y/m/d');

                //$imageFileObject->storeAs('', $folderName . '/' . 'org_' . $imageFileName, 's3');

                Storage::disk(config('app.storage_disk'))->put(
                        $folderName . '/' . 'org_' . $imageFileName,
                        $imageFileObject
                );

                $img = Image::make($imageFileObject)->orientate();
                $img->resize($thumbnailSize['width'], $thumbnailSize['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $thumbNail = $img->stream()->detach();

                Storage::disk(config('app.storage_disk'))->put(
                        $folderName . '/' . $imageFileName,
                        $thumbNail
                );

                $fundusData['logo_image_path'] = $folderName . '/' . $imageFileName;
            }

            $fundusData['geo_location'] = !empty($fundusData['geo_location']) ? DB::raw('ST_GeomFromText(\'' . $fundusData['geo_location'] . '\')') : NULL;
            $fundusData['ust_id'] = $fundusData['ust_id'] ?? '';
            FundusDetail::updateOrCreate(['user_id' => $userId], $fundusData);
        }

        return redirect()->route('data.show', [0]);
    }

    public function pauseFundus(Request $request) {
        $userId = \Auth::user()->id;

        $pausedTill = $request->input('pause_till');
        $pausedTillDate = $request->input('pause_till_date');

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        if (!empty($fundusDetail)) {
            $fundusDetail->is_paused = 1;
            $fundusDetail->paused_at = date('Y-m-d H:i:s');
            if ($pausedTill == 'definite') {
                $fundusDetail->paused_till_date = $pausedTillDate;
            }
            $fundusDetail->save();

            FundusActivityLog::create([
                'fundus_id' => $fundusDetail->id,
                'activity' => 'paused',
                'paused_till_date' => $fundusDetail->paused_till_date ?? null,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);
        }
        return redirect()->route('data.show', [0])
                        ->with('fundus_success', __('status_message.FUNDUS_ACCOUNT_DEACTIVATED'));
    }

    public function unpauseFundus(Request $request) {
        $userId = \Auth::user()->id;

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        if (!empty($fundusDetail)) {
            FundusActivityLog::create([
                'fundus_id' => $fundusDetail->id,
                'activity' => 'unpaused',
                'paused_till_date' => $fundusDetail->paused_till_date ?? null,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);

            $fundusDetail->is_paused = 0;
            $fundusDetail->paused_at = null;
            $fundusDetail->paused_till_date = null;
            $fundusDetail->save();
        }
        return redirect()->route('data.show', [0])
                        ->with('fundus_success', __('status_message.FUNDUS_ACCOUNT_ACTIVATED'));
    }

    public function pauseProject(Request $request) {
        $userId = \Auth::user()->id;

        $projectDetail = ProjectDetail::where('user_id', $userId)->first();
        if (!empty($projectDetail)) {

            if (!empty($projectDetail->paypal_subscription_id)) {
                $cancelStatus = $this->suspandPaypalSubscription($projectDetail->paypal_subscription_id, 'Cancelling project subscription');
                if ($cancelStatus) {
                    //$projectDetail->paypal_subscription_id = '';//I-DLGR87DAWMB1
                } else {
                    return redirect()->route('data.show', [0])
                                    ->with('project_error', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
                }
            }

//            $subscriptionHistory = SubscriptionHistory::where('user_id', $userId)
//                            ->where('start_date', '<=', date('Y-m-d H:i:s'))
//                            ->where('end_date', '>=', date('Y-m-d H:i:s'))
//                            ->with(['subscription' => function ($query) {
//                                    $query->where('account_type', 'project');
//                                }]
//                            )->latest()->first();
//
//            if (!empty($subscriptionHistory) && !empty($subscriptionHistory->subscription['id']) && $subscriptionHistory->subscription['type'] == 'onetime') {
//                $projectDetail->paypal_subscription_id = '';
//                $projectDetail->subscription_id = $subscriptionHistory->subscription_id;
//                $projectDetail->is_subscription_paused = 0;
//                $projectDetail->save();
//            } else {
//                $projectDetail->is_subscription_paused = 1;
//                $projectDetail->save();
//            }

            $projectDetail->is_subscription_paused = 1;
            $projectDetail->save();

            //Send subscription cancellation email to user
            $emailData['subscriptionEndDate'] = !empty($projectDetail->subscription_end_date) ? date('d.m.Y', strtotime($projectDetail->subscription_end_date)) : '';
            Notification::send(Auth::user(), new ProjectCancelSubscriptionNotification(Auth::user()->name, $emailData));

            ProjectActivityLog::create([
                'project_id' => $projectDetail->id,
                'activity' => 'paused',
                'subscription_end_date' => $projectDetail->subscription_end_date ?? null,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);
        }
        return redirect()->route('data.show', [0])
                        ->with('project_success', __('status_message.PROJECT_ACCOUNT_DEACTIVATED'));
    }

    public function unpauseProject(Request $request) {
        $userId = \Auth::user()->id;

        $projectDetail = ProjectDetail::where('user_id', $userId)->first();

        if (!empty($projectDetail->subscription_end_date) &&
                $projectDetail->subscription_end_date > date('Y-m-d H:i:s') &&
                isset($projectDetail->is_subscription_paused) &&
                $projectDetail->is_subscription_paused) {

            ProjectActivityLog::create([
                'project_id' => $projectDetail->id,
                'activity' => 'unpaused',
                'subscription_end_date' => $projectDetail->subscription_end_date ?? null,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);

            if (!empty($projectDetail->paypal_subscription_id)) {
                $activateStatus = $this->activatePaypalSubscription($projectDetail->paypal_subscription_id, 'Activating project subscription');
                if (!$activateStatus) {
                    return redirect()->route('data.show', [0])
                                    ->with('project_error', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
                }
            }

            $projectDetail->is_subscription_paused = 0;
            $projectDetail->save();

            Session::flash('showInformationModal', 'true');
            Session::flash('modalHeading', __('status_message.PROJECT_ACCOUNT_ACTIVATED_HEADING'));
            Session::flash('modalMessage', __('status_message.PROJECT_ACCOUNT_ACTIVATED'));

            return redirect()->route('data.show', [0]);
            //->with('project_success', __('status_message.PROJECT_ACCOUNT_ACTIVATED'));
        } else {
            return redirect()->route('data.show', [0])
                            ->with('project_error', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $response = [];
        $request->validate([
            'account_password' => 'required|string|min:8|max:50',
        ]);

        $userId = \Auth::user()->id;
        $userObject = User::where('id', $userId)->first();
        $emailData = [];

        if (!empty($userObject) && Hash::check($request->account_password, $userObject->password)) {
            $this->cancelAllSubscriptions($userId);

            try {
                DB::beginTransaction();

                $fundusDetails = FundusDetail::where('user_id', $userId)->first();
                if (!empty($fundusDetails) && $fundusDetails->package_type == 'infinite') {
                    $emailData['name'] = $userObject->first_name;
                    $emailData['email'] = $userObject->email;
                }

                if ($userObject->account_type == 'fundus') {
                    $paypalOrdersCount = OrderDetail::where('user_id', $userId)->where('payment_mode', 'paypal')->where('status', 'success')->count();
                    $bankOrdersCount = OrderDetail::where('user_id', $userId)->where('payment_mode', 'bank')->count();
                    if ($paypalOrdersCount == 0 && $bankOrdersCount == 0) {
                        //$fundusDetails = FundusDetail::where('user_id', $userId)->first();
                        Product::where('store_id', $fundusDetails->id)->forceDelete();
                        $fundusDetails->forceDelete();
                        $userObject->forceDelete();
                    } else {
                        //$fundusDetails = FundusDetail::where('user_id', $userId)->first();

                        if (!empty($fundusDetails)) {
                            Product::where('store_id', $fundusDetails->id)->delete();
                            $fundusDetails->delete();
                        }
                        $userObject->first_name = '#######';
                        $userObject->last_name = '#######';
                        $userObject->email = Hash::make(time() . $userObject->email);
                        $userObject->phone_number = '#######';
                        $userObject->deleted_at = Carbon::now();
                        $userObject->save();
                    }
                } else {
                    $paypalOrdersCount = OrderDetail::where('user_id', $userId)->where('payment_mode', 'paypal')->where('status', 'success')->count();
                    $bankOrdersCount = OrderDetail::where('user_id', $userId)->where('payment_mode', 'bank')->count();
                    $favouriteIds = [];
                    $favourites = \App\Models\Favourite::where('user_id', $userId)->get();
                    if (count($favourites)) {
                        $favouriteIds = $favourites->pluck('id')->toArray();
                    }
                    if ($paypalOrdersCount == 0 && $bankOrdersCount == 0) {
                        if (!empty($favouriteIds)) {
                            \App\Models\FavouriteDateRange::whereIn('favourite_id', $favouriteIds)->forceDelete();
                        }
                        \App\Models\FavouriteStoreChangeRequest::where('user_id', $userId)->forceDelete();
                        \App\Models\FavouriteItem::where('user_id', $userId)->forceDelete();
                        \App\Models\Favourite::where('user_id', $userId)->forceDelete();

                        ProjectDetail::where('user_id', $userId)->forceDelete();
                        //$fundusDetails = FundusDetail::where('user_id', $userId)->first();

                        if (!empty($fundusDetails)) {
                            Product::where('store_id', $fundusDetails->id)->forceDelete();
                            $fundusDetails->forceDelete();
                        }
                        $userObject->forceDelete();
                    } else {
                        if (!empty($favouriteIds)) {
                            \App\Models\FavouriteDateRange::whereIn('favourite_id', $favouriteIds)->delete();
                        }
                        \App\Models\FavouriteStoreChangeRequest::where('user_id', $userId)->delete();
                        \App\Models\FavouriteItem::where('user_id', $userId)->delete();
                        \App\Models\Favourite::where('user_id', $userId)->delete();

                        ProjectDetail::where('user_id', $userId)->delete();
                        //$fundusDetails = FundusDetail::where('user_id', $userId)->first();

                        if (!empty($fundusDetails)) {
                            Product::where('store_id', $fundusDetails->id)->delete();
                            $fundusDetails->delete();
                        }
                        $userObject->first_name = '#######';
                        $userObject->last_name = '#######';
                        $userObject->email = Hash::make(time() . $userObject->email);
                        $userObject->phone_number = '#######';
                        $userObject->deleted_at = Carbon::now();
                        $userObject->save();
                    }
                }
                Auth::guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $response = ['status' => 'success', 'message' => __('status_message.USER_ACCOUNT_DELETED')];

                DB::commit();
                if (!empty($emailData)) {
                    $this->sendInfiniteAccountDeleteEmail($emailData);
                }
            } catch (QueryException $e) {
                DB::rollBack();
                logger($e->getMessage());

                throw ValidationException::withMessages([
                            'account_password' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                        'account_password' => [trans('auth.password')],
            ]);
        }

        return response()->json($response, 200);
    }

}
