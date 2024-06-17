<?php

namespace App\Filters;

class Sort 
{
    protected string $table;

    public function __construct(protected $local)
    {
        $this->local = app()->getLocale();
    }

    public function id($query, $direction = 'desc')
    {
        return $query->orderBy("$this->table.id", $direction);
    }

    public function name($query, $direction = 'desc')
    {
        return $query->orderBy("$this->table.name->$this->local", $direction);
    }

    public function createdAt($query, $direction = 'desc')
    {
        return $query->orderBy("$this->table.created_at", $direction);
    }
}