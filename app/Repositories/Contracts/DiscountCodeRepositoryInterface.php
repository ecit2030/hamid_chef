<?php

namespace App\Repositories\Contracts;

interface DiscountCodeRepositoryInterface
{
    public function findByCode(string $code);
    public function getActive();
    public function getValid();
    public function getAvailable();
    public function getUserUsageCount(int $codeId, int $userId): int;
    public function getUsageStats(int $id): array;
}
