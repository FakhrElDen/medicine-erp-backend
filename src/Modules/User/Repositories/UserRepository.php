<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Str;
use Modules\User\Entities\User;

class UserRepository extends BaseRepository
{
    // ? what if role is editable?!
    // we will work with permissions because it's static
    // we need to refactor this file one method take permission and return user
    public function __construct(protected User $model)
    {
    }

    public function sales($input)
    {
        return $this->model->role('sales_employee')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->when(isset($input['shift']), function ($query) use ($input) {
            $query->where('shift', $input['shift']);
        })->when(isset($input['list_type']), function ($query) use ($input) {
            $query->whereHas('listing', function ($query) use ($input) {
                $query->where('type', $input['list_type']);
            });
        })->with('listing')->get();
    }

    public function delivers($input)
    {
        return $this->model->role('delivery')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function suppliers($input)
    {
        return $this->model->role('supplier')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function receiversAuditor($input)
    {
        return $this->model->role('receiving_reviewer')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function storingWorkers($input)
    {
        return $this->model->role('storing_worker')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function generalPreparation($input)
    {
        return $this->model->role('general_preparation')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function retailPreparation($input)
    {
        return $this->model->role('retail_preparation')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function retailSalesAuditor($input)
    {
        return $this->model->role('retail_sales_reviewer')->when(isset($input['name']), function ($query) use ($input) {
            $query->where('name', $input['name']);
        })->get();
    }

    public function getUserClients($user_id)
    {
        $user = $this->model->find($user_id);

        return $user->listing->first() ? $user->listing->first()->pharmacies->pluck('id') : [0];
    }

    public function receiversAuditorStoreKeepers()
    {
        return $this->model->permission(['receiving_reviewer', 'purchases_employee'])->whereDoesntHave('roles', function ($query) {
            $query->whereHas('permissions', function ($query) {
                $query->where('name', 'super_admin');
            });
        })->get();
    }

    public function getClient()
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->load('client.pharmacies');
    }

    public function createUserForClient($client_id, $client_code)
    {
        $user = $this->model->create([
            'client_id' => $client_id,
            'name' => 'free_delegate ' . $client_code,
            'email' => 'free_delegate' . strtolower($client_code) . '@medical.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole('free_delegate');
    }
}
