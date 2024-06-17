<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Client\Entities\Pharmacy;
use Modules\Client\Http\Requests\AddExtraDiscountRequest;
use Modules\Client\Http\Requests\AddPharmacyToClientRequest;
use Modules\Client\Http\Requests\CreatePharmacyRequest;
use Modules\Client\Http\Requests\DeleteMediaRequest;
use Modules\Client\Http\Requests\PharmacyIndexRequest;
use Modules\Client\Http\Requests\PharmacyRequest;
use Modules\Client\Repositories\ClientRepository;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Client\Transformers\PharmacyResourceCollection;
use Modules\Listing\Enums\ListingType;

class PharmacyController extends BaseController
{
    public function __construct(protected ClientRepository $clientRepository, protected PharmacyRepository $pharmacyRepository)
    {
        $this->middleware('permission:listing_pharmacies|retail_preparation|retail_reviewer')->only(['index']);
        $this->middleware('permission:add_extra_discount')->only(['addExtraDiscount']);
        $this->middleware('permission:pharmacy_crud')->only([
            'create', 
            'addPharmacyToClient', 
            'updatePharmacy', 
            'deleteMedia'
        ]);
    }

    public function index(PharmacyIndexRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $pharmacies = $this->pharmacyRepository->get(['client_id' => $user->client_id] + $request->validated());
        } else {
            $pharmacies = $this->pharmacyRepository->get($request->validated());
        }

        if (request()->has('sorted_by')) {
            if ($request->sorted_by == 'has_client') {
                $pharmacies = $pharmacies->sortBy(function ($pharmacy) {
                    return $pharmacy->has_client;
                });
            }
        }

        return $this->respondResource(new PharmacyResourceCollection($pharmacies));
    }

    public function create(CreatePharmacyRequest $request)
    {
        $client['name'] = $request->client_name;
        $client['type'] = $request->client_type;
        $client['phone_number'] = $request->client_phone_number;
        $client = $this->clientRepository->store($client);
        $pharmacy = $this->pharmacyRepository->create($request->validated());
        $client->pharmacies()->attach($pharmacy);

        if ($request->has('morning_sales_id') && $request->has('morning_list_id')) {
            $pharmacy->lists()->attach($request->morning_list_id);
        }

        if ($request->has('night_sales_id') && $request->has('night_list_id')) {
            $pharmacy->lists()->attach($request->night_list_id);
        }

        return $this->apiResponse(message: trans('client::message.create_pharmacy_message'));
    }

    public function addPharmacyToClient(AddPharmacyToClientRequest $request)
    {
        $client = $this->clientRepository->find($request->client_id);
        $pharmacy = $this->pharmacyRepository->create($request->validated());
        $client->pharmacies()->attach($pharmacy);

        return $this->apiResponse(new PharmacyResource($pharmacy), trans('client::message.create_pharmacy_message'));
    }

    public function updatePharmacy(PharmacyRequest $request, Pharmacy $pharmacy)
    {
        $pharmacy = $this->pharmacyRepository->updatePharmacy($request->validated(), $pharmacy->id);

        if ($request->has('morning_sales_id') && $request->has('morning_list_id')) {
            $listsToDetach = $pharmacy->lists()->where('type', ListingType::MORNING)->get();
            $pharmacy->lists()->detach($listsToDetach[0]->id);
            $pharmacy->lists()->attach([$request->morning_list_id]);
        }

        if ($request->has('night_sales_id') && $request->has('night_list_id')) {
            $listsToDetach = $pharmacy->lists()->where('type', ListingType::NIGHT)->get();
            $pharmacy->lists()->detach($listsToDetach[0]->id);
            $pharmacy->lists()->attach([$request->night_list_id]);
        }

        return $this->apiResponse(message: trans('client::message.update_pharmacy_message'));
    }

    public function addExtraDiscount(AddExtraDiscountRequest $request)
    {
        $this->pharmacyRepository->addExtraDiscount($request->validated());

        return $this->apiResponse(message: trans('client::message.update_pharmacy_extra_discount'));
    }

    public function deleteMedia(DeleteMediaRequest $request)
    {
        $this->pharmacyRepository->deletingMedia($request->validated());

        return $this->apiResponse(message: trans('client::message.delete_media_message'));
    }

    public function settings()
    {
        $data = $this->pharmacyRepository->settings();

        return $this->apiResponse($data);
    }
}
