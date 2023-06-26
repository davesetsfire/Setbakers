<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilePostRequest extends FormRequest {

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
        $userId = \Auth::user()->id;
        if ($this->route('data') == 'project') {
            return [
                'project_name' => ['required', 'string', 'max:190'],
                'phone_number' => ['sometimes', 'max:25'],
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'company_name' => ['required_if:is_company,1', 'nullable', 'string', 'max:200'],
                'ust_id' => ['nullable', 'string', 'max:50'],
                'house_number' => ['required', 'string', 'max:100'],
                'street' => ['required', 'string', 'max:200'],
                'postal_code' => ['required', 'string', 'max:20'],
                'location' => ['required', 'string', 'max:200'],
                'country' => ['required', 'string', 'max:100']
            ];
        } else if ($this->route('data') == 'fundus') {
            return [
                'fundus_name' => ['required', 'string', 'max:190', Rule::unique('fundus_details')->ignore($userId, 'user_id')],
                'fundus_email' => ['required', 'string', 'email', 'max:190'],
                'phone_number' => ['sometimes', 'max:25'],
                'owner_first_name' => ['required', 'string', 'max:100'],
                'owner_last_name' => ['required', 'string', 'max:100'],
                'company_name' => ['required_if:is_company,1', 'nullable', 'string', 'max:200'],
                'ust_id' => ['nullable', 'string', 'max:50'],
                'website' => ['nullable', 'string', 'max:100'],
                'house_number' => ['required', 'string', 'max:100'],
                'street' => ['required', 'string', 'max:200'],
                'postal_code' => ['required', 'string', 'max:20'],
                'location' => ['required', 'string', 'max:200'],
                'country' => ['required', 'string', 'max:100'],
                'description' => ['nullable', 'string', 'max:400']
            ];
        }
    }

    public function messages(): array {
        return [
            'company_name.required_if' => 'Firma muss ausgefüllt werden.',
            'ust_id.required_if' => 'USt-ID muss ausgefüllt werden.',
        ];
    }

}
