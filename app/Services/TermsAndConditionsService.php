<?php

namespace App\Services;

use App\Repositories\TermsAndConditionsRepository;
use Illuminate\Support\Facades\Auth;

class TermsAndConditionsService
{
    public function __construct(
        protected TermsAndConditionsRepository $repository
    ) {}

    /**
     * Get all terms and conditions
     */
    public function all()
    {
        return $this->repository->all();
    }

    /**
     * Get paginated terms and conditions
     */
    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Find terms and conditions by ID
     */
    public function find(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Get the active terms and conditions
     */
    public function getActive()
    {
        return $this->repository->getActive();
    }

    /**
     * Create new terms and conditions
     */
    public function create(array $data)
    {
        // If this is set as active, deactivate all others
        if ($data['is_active'] ?? false) {
            $this->repository->model->where('is_active', true)->update(['is_active' => false]);
        }

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return $this->repository->create($data);
    }

    /**
     * Update terms and conditions
     */
    public function update(int $id, array $data)
    {
        $terms = $this->repository->findOrFail($id);

        // If this is set as active, deactivate all others
        if (($data['is_active'] ?? false) && !$terms->is_active) {
            $this->repository->model->where('id', '!=', $id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $data['updated_by'] = Auth::id();

        return $this->repository->update($id, $data);
    }

    /**
     * Delete terms and conditions
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Activate terms and conditions
     */
    public function activate(int $id)
    {
        // Deactivate all others
        $this->repository->model->where('is_active', true)->update(['is_active' => false]);

        return $this->repository->update($id, [
            'is_active' => true,
            'updated_by' => Auth::id(),
        ]);
    }

    /**
     * Deactivate terms and conditions
     */
    public function deactivate(int $id)
    {
        return $this->repository->update($id, [
            'is_active' => false,
            'updated_by' => Auth::id(),
        ]);
    }
}
