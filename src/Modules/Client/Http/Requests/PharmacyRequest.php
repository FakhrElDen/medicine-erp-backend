<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Client\Rules\PharmacyAllRule;
use Modules\Client\Rules\PharmacyCallShiftRule;
use Modules\Client\Rules\PharmacyStatusRule;
use Modules\Client\Rules\PharmacyDebtLimitRule;
use Modules\Client\Rules\PharmacyDeliveryRule;
use Modules\Client\Rules\PharmacyDiscountSlatRule;
use Modules\Client\Rules\PharmacyExtraDiscountRule;
use Modules\Client\Rules\PharmacyFollowUpRule;
use Modules\Client\Rules\PharmacyIterateAvailableQuantityRule;
use Modules\Client\Rules\PharmacyMinimumTargetRule;
use Modules\Client\Rules\PharmacyPaymentPeriodRule;
use Modules\Client\Rules\PharmacyPaymentTypeRule;
use Modules\Client\Rules\PharmacyTargetRule;
use Modules\Client\Rules\PharmacyTrackRule;

class PharmacyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = Route::current()->parameter('pharmacy')->id;

        return [
            'id'                            => [Rule::exists('pharmacies', 'id')],
            'name'                          => ['nullable'],
            'phone_number'                  => ['nullable', Rule::unique('pharmacies')->ignore($id, 'id'),],
            'city_id'                       => 'nullable|exists:cities,id',
            'area_id'                       => 'nullable|exists:areas,id',
            'morning_sales_id'              => 'nullable|exists:users,id',
            'morning_list_id'               => 'nullable|exists:listings,id',
            'night_sales_id'                => 'nullable|exists:users,id',
            'night_list_id'                 => 'nullable|exists:listings,id',
            'address'                       => 'nullable',
            'optional_phone_number'         => 'nullable',
            'status'                        => ['nullable', new PharmacyStatusRule()],
            'email'                         => ['nullable', Rule::unique('pharmacies')->ignore($id, 'id'),],
            'longitude'                     => 'nullable',
            'latitude'                      => 'nullable',
            'payment_period'                => ['nullable', new PharmacyPaymentPeriodRule()],
            'commercial_registration_no'    => ['nullable', Rule::unique('pharmacies')->ignore($id, 'id'),],
            'license_no'                    => ['nullable', Rule::unique('pharmacies')->ignore($id, 'id'),],
            'tax_card_no'                   => ['nullable', Rule::unique('pharmacies')->ignore($id, 'id'),],
            'active'                        => 'nullable',
            'doctor'                        => 'nullable',
            'note'                          => 'nullable',
            'target'                        => ['nullable', 'numeric', new PharmacyTargetRule()],
            'minimum_target'                => ['nullable', 'numeric', 'lte:target', new PharmacyMinimumTargetRule()],
            'iterate_available_quantity'    => ['nullable', 'numeric', new PharmacyIterateAvailableQuantityRule()],
            'track_id'                      => ['nullable', new PharmacyTrackRule()],
            'extra_discount'                => ['nullable', new PharmacyExtraDiscountRule()],
            'all'                           => ['nullable', 'numeric', new PharmacyAllRule()],
            'follow_up'                     => ['nullable', 'integer', new PharmacyFollowUpRule()],
            'call_shift'                    => ['nullable', 'integer', 'required_with:follow_up', new PharmacyCallShiftRule()],
            'delivery_id'                   => ['nullable', new PharmacyDeliveryRule()],
            'payment_type'                  => ['nullable', new PharmacyPaymentTypeRule()],
            'debt_limit'                    => ['nullable', new PharmacyDebtLimitRule()],
            'pharmacy_media'                => 'nullable',
            'discount_slat'                 => ['nullable', new PharmacyDiscountSlatRule()],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
