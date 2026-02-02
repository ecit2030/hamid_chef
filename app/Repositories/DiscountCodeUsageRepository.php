<?php

namespace App\Repositories;

use App\Models\DiscountCodeUsage;
use App\Repositories\Contracts\DiscountCodeUsageRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class DiscountCodeUsageRepository extends BaseRepository implements DiscountCodeUsageRepositoryInterface
{
    public function __construct(DiscountCodeUsage $model)
    {
        parent::__construct($model);
    }

    public function recordUsage(array $data)
    {
        return $this->create($data);
    }

    public function getUserUsages(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['discountCode', 'booking'])
            ->latest('used_at')
            ->get();
    }

    public function getCodeUsages(int $codeId)
    {
        return $this->model
            ->where('discount_code_id', $codeId)
            ->with(['user', 'booking'])
            ->latest('used_at')
            ->get();
    }
}
