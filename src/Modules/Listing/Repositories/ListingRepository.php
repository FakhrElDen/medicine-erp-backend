<?php

namespace Modules\Listing\Repositories;

use App\Repositories\BaseRepository;
use Modules\Listing\Entities\Listing;

class ListingRepository extends BaseRepository
{
    public function __construct(protected Listing $model)
    {
    }

    public function get($input)
    {
        return $this->model->with('users', 'pharmacies.lists')->applySorts($input)
            ->when(isset($input['client_id']), function ($query) use ($input) {
                $query->whereHas('pharmacies', function ($query) use ($input) {
                    $query->whereHas('clients', function ($query) use ($input) {
                        $query->where('clients.id', $input['client_id']);
                    });
                });
            })->when(isset($input['listing_id']), function ($query) use ($input) {
                $query->where('id', $input['listing_id']);
            })->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function create($input)
    {
        $listing = $this->model->create($input);
        $listing->pharmacies()->attach($input['pharmacy_ids']);
        $listing->users()->attach($input['user_id']);

        return $listing;
    }

    public function delete($input)
    {
        return $this->model->find($input['listing_id'])->delete();
    }

    public function update($input)
    {
        $listing = $this->model->find($input['listing_id']);
        $listing->update($input);
        $listing->pharmacies()->detach();
        $listing->pharmacies()->sync($input['pharmacy_ids']);
        $listing->users()->detach();
        $listing->users()->sync($input['user_id']);
    }
}
