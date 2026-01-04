<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Chef\StoreChefServiceRequest;
use App\Http\Requests\UpdateChefServiceRequest;
use App\Services\ChefServiceService;
use App\DTOs\ChefServiceDTO;
use App\Models\ChefService;
use App\Models\Tag;
use App\Models\Category;
use Inertia\Inertia;

class ChefServiceController extends Controller
{
    public function index(Request $request, ChefServiceService $serviceService)
    {
        $this->authorize('viewAny', ChefService::class);

        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        $perPage = $request->input('per_page', 9);
        
        // Filter services by authenticated chef's ID only
        $services = $serviceService->query(['chef:id,name,logo', 'tags'])
            ->where('chef_id', $chef->id)
            ->latest()
            ->paginate($perPage);
        
        $services->getCollection()->transform(function ($service) {
            return ChefServiceDTO::fromModel($service)->toIndexArray();
        });
        
        return Inertia::render('Chef/ChefService/Index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        $this->authorize('create', ChefService::class);

        $tags = Tag::where('is_active', true)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Chef/ChefService/Create', [
            'tags' => $tags,
            'categories' => $categories,
        ]);
    }

    public function store(StoreChefServiceRequest $request, ChefServiceService $serviceService)
    {
        $this->authorize('create', ChefService::class);

        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $data = $request->validated();
        // Force chef_id to authenticated chef - security measure
        $data['chef_id'] = $chef->id;
        
        $serviceService->create($data);
        return redirect()->route('chef.services.index');
    }

    public function show(ChefService $service)
    {
        $this->authorize('view', $service);

        $service->load([
            'chef',
            'tags', 
            'images' => function($query) {
                $query->where('is_active', true)->orderBy('created_at');
            },
            'equipment' => function($query) {
                $query->orderBy('is_included', 'desc')->orderBy('created_at', 'desc');
            },
            'ratings' => function($query) {
                $query->with(['customer:id,first_name,last_name', 'booking:id,date'])
                      ->where('chef_service_ratings.is_active', true)
                      ->orderBy('chef_service_ratings.created_at', 'desc');
            }
        ]);
        $dto = ChefServiceDTO::fromModel($service)->toArray();
        return Inertia::render('Chef/ChefService/Show', [
            'service' => $dto,
        ]);
    }

    public function edit(ChefService $service)
    {
        $this->authorize('update', $service);

        $service->load([
            'tags', 
            'images' => function($query) {
                $query->where('is_active', true)->orderBy('created_at');
            },
            'equipment' => function($query) {
                $query->orderBy('is_included', 'desc')->orderBy('created_at', 'desc');
            }
        ]);
        
        $tags = Tag::where('is_active', true)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        $dto = ChefServiceDTO::fromModel($service)->toArray();
        return Inertia::render('Chef/ChefService/Edit', [
            'service' => $dto,
            'tags' => $tags,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateChefServiceRequest $request, ChefServiceService $serviceService, ChefService $service)
    {
        $this->authorize('update', $service);

        $data = $request->validated();
        $serviceService->update($service->id, $data);
        return redirect()->route('chef.services.index');
    }

    public function destroy(ChefServiceService $serviceService, ChefService $service)
    {
        $this->authorize('delete', $service);

        $serviceService->delete($service->id);
        return redirect()->route('chef.services.index');
    }

    public function activate(ChefServiceService $serviceService, $id)
    {
        $service = ChefService::findOrFail($id);
        $this->authorize('activate', $service);

        $serviceService->activate($id);
        return back()->with('success', __('chef_service.activated'));
    }

    public function deactivate(ChefServiceService $serviceService, $id)
    {
        $service = ChefService::findOrFail($id);
        $this->authorize('deactivate', $service);

        $serviceService->deactivate($id);
        return back()->with('success', __('chef_service.deactivated'));
    }
}