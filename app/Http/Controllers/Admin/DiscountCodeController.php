<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DiscountCodeService;
use App\Repositories\DiscountCodeRepository;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Http\Requests\UpdateDiscountCodeRequest;
use App\Http\Resources\DiscountCodeResource;
use Inertia\Inertia;

class DiscountCodeController extends Controller
{
    public function __construct(
        private DiscountCodeRepository $repo,
        private DiscountCodeService $service
    ) {}

    public function index()
    {
        $codes = $this->repo->paginate(15);

        return Inertia::render('Admin/DiscountCode/Index', [
            'codes' => DiscountCodeResource::collection($codes),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/DiscountCode/Create');
    }

    public function store(StoreDiscountCodeRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();

        $this->repo->create($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', __('تم إنشاء كود الخصم بنجاح'));
    }

    public function show($id)
    {
        $code = $this->repo->find($id);
        $statistics = $this->service->getCodeStatistics($id);

        return Inertia::render('Admin/DiscountCode/Show', [
            'code' => new DiscountCodeResource($code),
            'statistics' => $statistics,
        ]);
    }

    public function edit($id)
    {
        $code = $this->repo->find($id);

        return Inertia::render('Admin/DiscountCode/Edit', [
            'code' => new DiscountCodeResource($code),
        ]);
    }

    public function update(UpdateDiscountCodeRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->id();

        $this->repo->update($id, $validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', __('تم تحديث كود الخصم بنجاح'));
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', __('تم حذف كود الخصم بنجاح'));
    }
}
