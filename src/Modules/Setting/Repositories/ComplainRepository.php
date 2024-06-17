<?php

namespace Modules\Setting\Repositories;

use App\Repositories\BaseRepository;
use Modules\Setting\Entities\Complain;
use Modules\Setting\Enums\ComplainType;

class ComplainRepository extends BaseRepository
{
    public function __construct(protected Complain $model)
    {
    }

    public function unsolvedComplains($input)
    {
        return $this->model->where('status', ComplainType::NOT_SOLVE)->applySorts($input)
            ->with('sales', 'client', 'createdBy', 'user')
            ->when(isset($input['client_id']), function ($query) use ($input) {
                $query->where('client_id', $input['client_id']);
            })->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function solvedComplains($input)
    {
        return $this->model->where('status', ComplainType::SOLVE)->applySorts($input)
            ->with('sales', 'client', 'createdBy', 'user', 'solver')
            ->when(isset($input['client_id']), function ($query) use ($input) {
                $query->where('client_id', $input['client_id']);
            })->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function store($input)
    {
        return $this->model->create($input)->load('sales', 'client', 'user', 'createdBy', 'pharmacy', 'role');
    }

    public function find($complain_id)
    {
        return $this->model->find($complain_id);
    }

    public function updateSolverComplain($input)
    {
        $complain = $this->find($input['id']);

        return $this->model->where('id', $input['id'])->update(['status' => ComplainType::SOLVE, 'solver_id' => auth()->user()->id, 'solved_duration' => now()->diffInMinutes($complain->created_at)]);
    }

    public function showComplain($input)
    {
        return $this->model->where('id', $input['id'])->with('user', 'sales', 'client', 'pharmacy', 'role', 'solver')->first();
    }
}
