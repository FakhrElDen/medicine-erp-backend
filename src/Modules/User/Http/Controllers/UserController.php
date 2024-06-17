<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\User\Transformers\UserResource;
use Modules\User\Http\Requests\RolesRequest;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Http\Requests\FilterUserRequest;
use Modules\User\Http\Requests\SalesIndexRequest;
use Modules\User\Http\Requests\UserByRolesRequest;
use Modules\User\Repositories\PermissionRepository;
use Modules\User\Transformers\RoleResourceCollection;
use Modules\User\Transformers\UserResourceCollection;
use Modules\User\Transformers\PermissionResourceCollection;

class UserController extends BaseController
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected PermissionRepository $permissionRepository,
    ) {
        $this->middleware('permission:listing_sales')->only(['sales']);
        $this->middleware('permission:listing_delivers')->only(['delivers']);
        $this->middleware('permission:listing_suppliers')->only(['suppliers']);
        $this->middleware('permission:listing_receivers_auditor')->only(['receiversAuditor', 'receiversAuditorStoreKeepers']);
        $this->middleware('permission:listing_general_preparation')->only(['generalPreparation']);
        $this->middleware('permission:listing_retail_preparation|retail_preparation')->only(['retailPreparation']);
        $this->middleware('permission:listing_storing_workers')->only(['storingWorkers']);
        $this->middleware('permission:listing_retail_sales_reviewer|retail_reviewer')->only(['retailSalesAuditor']);
    }

    public function sales(SalesIndexRequest $request)
    {
        $sales = $this->userRepository->sales($request->validated());

        return $this->apiResponse(new UserResourceCollection($sales));
    }

    public function delivers(FilterUserRequest $request)
    {
        $delivers = $this->userRepository->delivers($request->validated());

        return $this->apiResponse(new UserResourceCollection($delivers));
    }

    public function suppliers(FilterUserRequest $request)
    {
        $suppliers = $this->userRepository->suppliers($request->validated());

        return $this->apiResponse(new UserResourceCollection($suppliers));
    }

    public function receiversAuditor(FilterUserRequest $request)
    {
        $receiversAuditor = $this->userRepository->receiversAuditor($request->validated());

        return $this->apiResponse(new UserResourceCollection($receiversAuditor));
    }

    public function generalPreparation(FilterUserRequest $request)
    {
        $generalPreparations = $this->userRepository->generalPreparation($request->validated());

        return $this->apiResponse(new UserResourceCollection($generalPreparations));
    }

    public function retailPreparation(FilterUserRequest $request)
    {
        $retailPreparations = $this->userRepository->retailPreparation($request->validated());

        return $this->apiResponse(new UserResourceCollection($retailPreparations));
    }

    public function storingWorkers(FilterUserRequest $request)
    {
        $storingWorkers = $this->userRepository->storingWorkers($request->validated());

        return $this->apiResponse(new UserResourceCollection($storingWorkers));
    }

    public function receiversAuditorStoreKeepers(FilterUserRequest $request)
    {
        $receiversAuditorStoreKeepers = $this->userRepository->receiversAuditorStoreKeepers();

        return $this->apiResponse(new UserResourceCollection($receiversAuditorStoreKeepers));        
    }

    public function retailSalesAuditor(FilterUserRequest $request)
    {
        $retailSalesAuditors = $this->userRepository->retailSalesAuditor($request->validated());

        return $this->apiResponse(new UserResourceCollection($retailSalesAuditors));
    }

    public function getRoles(RolesRequest $request)
    {
        $roles = $this->roleRepository->roles($request->validated());

        return $this->apiResponse(new RoleResourceCollection($roles));
    }

    public function getPermissions()
    {
        $roles = $this->permissionRepository->all();

        return $this->apiResponse(new PermissionResourceCollection($roles));
    }

    public function getUsersByRole(UserByRolesRequest $request)
    {
        $users = $this->roleRepository->getUserByRole($request->validated());

        return $this->apiResponse(new UserResourceCollection($users));
    }

    public function getUsersClients($user_id)
    {
        return $this->userRepository->getUserClients($user_id);
    }

    public function getClientPharmacies()
    {
        $user = $this->userRepository->getClient();

        return $this->apiResponse(new UserResource($user));
    }
}
