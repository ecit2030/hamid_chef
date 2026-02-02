<?php

namespace App\Repositories\Contracts;

interface DiscountCodeUsageRepositoryInterface
{
    public function recordUsage(array $data);
    public function getUserUsages(int $userId);
    public function getCodeUsages(int $codeId);
}
