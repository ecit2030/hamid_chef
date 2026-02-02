<?php

namespace App\Repositories;

use App\Models\DiscountCode;
use App\Repositories\Contracts\DiscountCodeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class DiscountCodeRepository extends BaseRepository implements DiscountCodeRepositoryInterface
{
    public function __construct(DiscountCode $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function getValid()
    {
        return $this->model->valid()->get();
    }

    public function getAvailable()
    {
        return $this->model->available()->get();
    }

    public function getUserUsageCount(int $codeId, int $userId): int
    {
        return $this->model->find($codeId)
            ->usages()
            ->where('user_id', $userId)
            ->count();
    }

    public function getUsageStats(int $id): array
    {
        $code = $this->find($id);

        return [
            'total_usages' => $code->usage_count,
            'total_discount_amount' => $code->usages()->sum('discount_amount'),
            'unique_users' => $code->usages()->distinct('user_id')->count('user_id'),
            'recent_usages' => $code->usages()
                ->with(['user', 'booking'])
                ->latest('used_at')
                ->limit(10)
                ->get(),
        ];
    }
}
