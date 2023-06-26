<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileStoreRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        if ($this->input('upgrade') == 'project' || $this->input('upgrade') == 'new-project') {
            return [
                'project_name' => ['required', 'string', 'max:190'],
                'company_name' => ['required_if:project_company_account,firma_checked', 'nullable', 'string', 'max:200'],
                'ust_id' => ['nullable', 'string', 'max:50'],
                'house_number' => ['required', 'string', 'max:100'],
                'street' => ['required', 'string', 'max:200'],
                'postal_code' => ['required', 'string', 'max:20'],
                'location' => ['required', 'string', 'max:200'],
                'country' => ['required', 'string', 'max:100']
            ];
        } else if ($this->input('upgrade') == 'store') {
            return [
                'fundus_name' => ['required', 'string', 'max:190', 'unique:fundus_details'],
                'fundus_email' => ['required', 'string', 'email', 'max:190'],
                'fundus_phone' => ['sometimes', 'max:25'],
                'fundus_owner_first_name' => ['required', 'string', 'max:100'],
                'fundus_owner_last_name' => ['required', 'string', 'max:100'],
                'fundus_company_name' => ['required_if:fundus_company_account,fundusdaten_firma_checked', 'nullable', 'string', 'max:200'],
                'fundus_ust_id' => ['nullable', 'string', 'max:50'],
                'fundus_website' => ['nullable', 'string', 'max:100'],
                'fundus_house_number' => ['required', 'string', 'max:100'],
                'fundus_street' => ['required', 'string', 'max:200'],
                'fundus_postal_code' => ['required', 'string', 'max:20'],
                'fundus_location' => ['required', 'string', 'max:200'],
                'fundus_country' => ['required', 'string', 'max:100'],
            ];
        }
    }

    public function messages(): array {
        return [
            'company_name.required_if' => 'Firma muss ausgefüllt werden.',
            'ust_id.required_if' => 'USt-ID muss ausgefüllt werden.',
            'fundus_company_name.required_if' => 'Firma muss ausgefüllt werden.',
            'fundus_ust_id.required_if' => 'USt-ID muss ausgefüllt werden.',
        ];
    }

}
