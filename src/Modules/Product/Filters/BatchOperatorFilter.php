<?php

namespace Modules\Product\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class BatchOperatorFilter extends Filter
{
    protected string $table = 'batch_operators';

    static $fields = [
        'receiver_distributor_id'       => 'receiverDistributorId',
        'supplier_id'                   => 'supplierId',
        'receiver_reviewer_id'          => 'receiverReviewerId',
        'storing_worker_id'             => 'storingWorkerId',
        'created_by'                    => 'createdBy',
        'distributor_received_at'       => 'distributorReceivedAt',
        'reviewer_received_at'          => 'reviewerReceivedAt',
        'supplied_at'                   => 'suppliedAt',
        'stored_at'                     => 'storedAt',
    ];

    public function receiverDistributorId($query, $value)
    {
        return $query->where("$this->table.receiver_distributor_id", $value);
    }

    public function storingWorkerId($query, $value)
    {
        return $query->where("$this->table.storing_worker_id", $value);
    }

    public function receiverReviewerId($query, $value)
    {
        return $query->where("$this->table.receiver_reviewer_id", $value);
    }

    public function supplierId($query, $value)
    {
        return $query->where("$this->table.supplier_id", $value);
    }

    public function distributorReceivedAt($query, $value)
    {
        return $query->whereDate("$this->table.distributor_received_at", $value);
    }

    public function reviewerReceivedAt($query, $value)
    {
        return $query->whereDate("$this->table.reviewer_received_at", $value);
    }

    public function suppliedAt($query, $value)
    {
        return $query->whereDate("$this->table.supplied_at", $value);
    }

    public function storedAt($query, $value)
    {
        return $query->whereDate("$this->table.stored_at", $value);
    }
}
