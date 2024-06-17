<?php

namespace App\Filters;

class Filter 
{
    protected string $table;
    protected $local;

    public function __construct()
    {
        $this->local = app()->getLocale();
    }

    public function quantity($query, $value)
    {
        return $query->where("$this->table.quantity", $value);
    }

    public function code($query, $value)
    {
        return $query->where("$this->table.code", $value);
    }

    public function nameLocal($query, $value)
    {
        return $query->where("$this->table.name->$this->local", $value);
    }

    public function name($query, $value)
    {
        return $query->where("$this->table.name", $value);
    }

    public function status($query, $value)
    {
        return $query->where("$this->table.status", $value);
    }

    public function number($query, $value)
    {
        return $query->where("$this->table.number", $value);
    }

    public function createdAt($query, $value)
    {
        return $query->whereDate("$this->table.created_at", $value);
    }

    public function createdBy($query, $value)
    {
        return $query->where("$this->table.created_by", $value);
    }

    public function updatedBy($query, $value)
    {
        return $query->where("$this->table.updated_by", $value);
    }

    public function warehouseId($query, $value)
    {
        return $query->where("$this->table.warehouse_id", $value);
    }

    public function pharmacyId($query, $value)
    {
        return $query->where("$this->table.pharmacy_id", $value);
    }

    public function clientId($query, $value)
    {
        return $query->where("$this->table.client_id", $value);
    }
}