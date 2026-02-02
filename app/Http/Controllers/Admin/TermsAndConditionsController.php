<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TermsAndConditionsService;
use App\DTOs\TermsAndConditionsDTO;
use App\Http\Requests\StoreTermsAndConditionsRequest;
use App\Http\Requests\UpdateTermsAndConditionsRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    public function __construct(
        protected TermsAndConditionsService $service
    ) {}

    /**
     * Display a listing of terms and conditions
     */
    public function index()
    {
        $terms = $this->service->paginate(15);

        $terms->getCollection()->transform(function ($term) {
            return TermsAndConditionsDTO::fromModel($term)->toArray();
        });

        return Inertia::render('Admin/TermsAndConditions/Index', [
            'terms' => $terms,
        ]);
    }

    /**
     * Show the form for creating new terms and conditions
     */
    public function create()
    {
        return Inertia::render('Admin/TermsAndConditions/Create');
    }

    /**
     * Store newly created terms and conditions
     */
    public function store(StoreTermsAndConditionsRequest $request)
    {
        $terms = $this->service->create($request->validated());

        return redirect()
            ->route('admin.terms-and-conditions.index')
            ->with('success', 'تم إنشاء الشروط والأحكام بنجاح');
    }

    /**
     * Show the form for editing terms and conditions
     */
    public function edit(int $id)
    {
        $terms = $this->service->find($id);
        $dto = TermsAndConditionsDTO::fromModel($terms);

        return Inertia::render('Admin/TermsAndConditions/Edit', [
            'terms' => $dto->toArray(),
        ]);
    }

    /**
     * Update terms and conditions
     */
    public function update(UpdateTermsAndConditionsRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()
            ->route('admin.terms-and-conditions.index')
            ->with('success', 'تم تحديث الشروط والأحكام بنجاح');
    }

    /**
     * Remove terms and conditions
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return redirect()
            ->route('admin.terms-and-conditions.index')
            ->with('success', 'تم حذف الشروط والأحكام بنجاح');
    }

    /**
     * Activate terms and conditions
     */
    public function activate(int $id)
    {
        $this->service->activate($id);

        return redirect()
            ->route('admin.terms-and-conditions.index')
            ->with('success', 'تم تفعيل الشروط والأحكام بنجاح');
    }

    /**
     * Deactivate terms and conditions
     */
    public function deactivate(int $id)
    {
        $this->service->deactivate($id);

        return redirect()
            ->route('admin.terms-and-conditions.index')
            ->with('success', 'تم تعطيل الشروط والأحكام بنجاح');
    }
}
