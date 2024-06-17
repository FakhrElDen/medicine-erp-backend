<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\BaseController;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Http\Requests\BasketRestoreRequest;
use Modules\Setting\Http\Requests\BasketsCountUpdateRequest;
use Modules\Setting\Http\Requests\BasketSetDamagedRequest;
use Modules\Setting\Http\Requests\CorridorStoreRequest;
use Modules\Setting\Http\Requests\CorridorUpdateRequest;
use Modules\Setting\Http\Requests\FilterRequest;
use Modules\Setting\Http\Requests\SetExpirationWarningRequest;
use Modules\Setting\Http\Requests\SetHighPriceRequest;
use Modules\Setting\Http\Requests\WarehouseSystemRequest;
use Modules\Setting\Repositories\SettingRepository;
use Modules\Setting\Transformers\SettingResourceCollection;
use Modules\User\Entities\User;
use Modules\Warehouse\Enums\BasketStatus;
use Modules\Warehouse\Repositories\BasketRepository;
use Modules\Warehouse\Repositories\CorridorRepository;
use Modules\Warehouse\Transformers\BasketResource;
use Modules\Warehouse\Transformers\BasketResourceCollection;
use Modules\Warehouse\Transformers\CorridorResource;
use Modules\Warehouse\Transformers\CorridorResourceCollection;

class SettingController extends BaseController
{
    public function __construct(
        protected SettingRepository $settingRepository,
        protected CorridorRepository $corridor_repository,
        protected BasketRepository $basket_repository,
    ) {
        $this->middleware('permission:general')->only(['index', 'enums', 'getCurrentWarehouseSystem']);

        $this->middleware('permission:storekeeper_settings_permission')->only([
            'getWarehousesSettings',
            'warehouseSystem',
            'createCorridor',
            'updateCorridor',
            'updateTotalBasketsCount',
            'markBasketAsDamaged',
            'restoreDamagedBasket',
            'setHighPriceThreshold',
            'setExpirationWarningThreshold',
        ]);
    }

    public function index()
    {
        $settings = $this->settingRepository->all();

        return $this->apiResponse(new SettingResourceCollection($settings));
    }

    public function enums()
    {
        $enums = $this->settingRepository->enums();

        return $this->apiResponse($enums);
    }

    public function getWarehousesSettings()
    {
        $corridors = $this->corridor_repository->get();
        $damaged_baskets = $this->basket_repository->get(BasketStatus::DAMAGED);

        $settings = Setting::whereIn('key', [
            'multiple_corridors_enabled',
            'default_sorting',
            'baskets_number',
            'high_price',
            'remaining_months_for_expiration',
        ])->get()->pluck('value', 'key')->toArray();

        return $this->apiResponse([
            'corridors' => new CorridorResourceCollection($corridors),
            'damaged_baskets' => new BasketResourceCollection($damaged_baskets),
            ...$settings,
        ]);
    }

    public function getCurrentWarehouseSystem()
    {
        // $warehouse_system = Setting::getValue('multiple_corridors_enabled');

        $warehouse_system = true;

        return $this->apiResponse(['multiple_corridors_enabled' => (bool) $warehouse_system]);
    }

    public function warehouseSystem(WarehouseSystemRequest $request)
    {
        $multiple_corridors_enabled = Setting::getValue('multiple_corridors_enabled');

        if ($multiple_corridors_enabled != $request->multiple_corridors_enabled) {
            Setting::setValue('multiple_corridors_enabled', $request->multiple_corridors_enabled);
            /** @var User $user */
            $user = auth()->user();
            PersonalAccessToken::where('id', '!=', $user->currentAccessToken()->id)->delete();
        }

        Setting::setValue('default_sorting', $request->default_sorting);

        return $this->apiResponse();
    }

    public function createCorridor(CorridorStoreRequest $request)
    {
        $corridor = $this->corridor_repository->create($request->number);

        return $this->apiResponse([
            'corridor' => new CorridorResource($corridor),
        ]);
    }

    public function updateCorridor(CorridorUpdateRequest $request, $id)
    {
        $this->corridor_repository->update($id, $request->number);

        return $this->apiResponse();
    }

    public function updateTotalBasketsCount(BasketsCountUpdateRequest $request)
    {
        Setting::setValue('baskets_number', $request->count);

        return $this->apiResponse();
    }

    public function markBasketAsDamaged(BasketSetDamagedRequest $request)
    {
        $basket = $this->basket_repository->create(['number' => $request->number, 'status' => BasketStatus::DAMAGED]);

        return $this->apiResponse(['basket' => new BasketResource($basket)]);
    }

    public function restoreDamagedBasket(BasketRestoreRequest $request, $id)
    {
        $this->basket_repository->delete($id);

        return $this->apiResponse();
    }

    public function setHighPriceThreshold(SetHighPriceRequest $request)
    {
        Setting::setValue('high_price', $request->price);

        return $this->apiResponse();
    }

    public function setExpirationWarningThreshold(SetExpirationWarningRequest $request)
    {
        Setting::setValue('remaining_months_for_expiration', $request->months);

        return $this->apiResponse();
    }

    public function filters(FilterRequest $request)
    {
        $filters = $this->settingRepository->filters($request->validated());

        return $this->apiResponse($filters);
    }
}
