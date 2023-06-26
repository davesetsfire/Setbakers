<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use App\Models\ProjectDetail;
use App\Models\FundusDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function showRegistrationForm() {
        return redirect()->route('index');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        $rules = [
            'account_type' => 'required',
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['max:100'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:users'],
            //'email' => ['required', 'string', 'email', 'max:190', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        if ($data['account_type'] == 'complete') {
            $rules = array_merge($rules, [
                // Project Validations
                'project_name' => 'required_if:account_type,complete|nullable|string|max:190',
                'company_name' => ['required_if:project_company_account,firma_checked', 'nullable', 'string', 'max:200'],
                'ust_id' => ['nullable', 'string', 'max:50'],
                'house_number' => ['required_if:account_type,complete', 'nullable', 'string', 'max:100'],
                'street' => ['required_if:account_type,complete', 'nullable', 'string', 'max:200'],
                'postal_code' => ['required_if:account_type,complete', 'nullable', 'string', 'max:20'],
                'location' => ['required_if:account_type,complete', 'nullable', 'string', 'max:200'],
                'country' => ['required_if:account_type,complete', 'nullable', 'string', 'max:100'],
            ]);
        }

        if ($data['account_type'] == 'fundus' || (!empty($data['add_fundus_store']) && $data['add_fundus_store'] == 'yes')) {
            $rules = array_merge($rules, [
                // Fundus Validations
                'fundus_name' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:190', 'unique:fundus_details'],
                //'fundus_name' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:190', 'unique:fundus_details,fundus_name,NULL,id,deleted_at,NULL'],
                'fundus_email' => ['required_if:account_type,fundus', 'nullable', 'string', 'email', 'max:190'],
                'fundus_owner_first_name' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:100'],
                'fundus_owner_last_name' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:100'],
                'fundus_company_name' => ['required_if:fundus_company_account,fundusdaten_firma_checked', 'nullable', 'string', 'max:200'],
                'fundus_ust_id' => ['nullable', 'string', 'max:50'],
                'fundus_website' => ['nullable', 'string', 'max:100'],
                'fundus_house_number' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:100'],
                'fundus_street' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:200'],
                'fundus_postal_code' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:20'],
                'fundus_location' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:200'],
                'fundus_country' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:100'],
                    //'description' => ['required_if:account_type,fundus', 'nullable', 'string', 'max:400'],
            ]);
        }

        $messages = ['email.unique' => 'Diese E-Mail Adresse wird bereits verwendet'];
        return Validator::make($data, $rules, $messages);
    }

    public function register(Request $request) {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //Disable auto login after registration
        //$this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse(['status' => 'success', 'redirectTo' => $this->redirectPath()], 200) : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        try {
            DB::beginTransaction();
            $user = User::create([
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                        'account_type' => $data['account_type'],
                        'phone_number' => $data['phone_number'] ?? '',
            ]);

            if ($data['account_type'] == 'complete') {
                $isCompany = $data['project_company_account'] == 'firma_checked' ? 1 : 0;

                if ($isCompany == 0) {
                    $data['company_name'] = '';
                    $data['ust_id'] = '';
                }

                ProjectDetail::create([
                    'user_id' => $user->id,
                    'project_name' => $data['project_name'],
                    'description' => '',
                    'company_name' => $data['company_name'] ?? '',
                    'ust_id' => $data['ust_id'] ?? '',
                    'is_company' => $isCompany,
                    'house_number' => $data['house_number'],
                    'street' => $data['street'],
                    'postal_code' => $data['postal_code'],
                    'location' => $data['location'],
                    'country' => $data['country'],
                ]);
            }

            if ($data['account_type'] == 'fundus' || (!empty($data['add_fundus_store']) && $data['add_fundus_store'] == 'yes')) {
                $isCompany = $data['fundus_company_account'] == 'fundusdaten_firma_checked' ? 1 : 0;

                if ($isCompany == 0) {
                    $data['fundus_company_name'] = '';
                    $data['fundus_ust_id'] = '';
                }

                FundusDetail::create([
                    'user_id' => $user->id,
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
                ]);
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw ValidationException::withMessages([
                        'email' => [trans('status_message.UNABLE_TO_PROCCESS_REQUEST')],
            ]);
        }

        return $user;
    }

}
