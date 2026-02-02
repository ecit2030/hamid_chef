<?php

namespace App\Repositories;

use App\Models\TermsAndConditions;
use App\Repositories\Eloquent\BaseRepository;

class TermsAndConditionsRepository extends BaseRepository
{
    public function __construct(TermsAndConditions $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the active terms and conditions
     */
    public function getActive()
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('effective_date', 'desc')
            ->first();
    }

    /**
     * Get all versions ordered by date
     */
    public function getAllVersions()
    {
        return $this->model
            ->orderBy('effective_date', 'desc')
            ->get();
    }
}
