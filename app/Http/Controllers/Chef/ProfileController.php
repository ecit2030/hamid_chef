<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\Governorate;
use App\Models\District;
use App\Models\Area;
use App\Models\Category;
use App\Services\ChefService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    protected ChefService $chefService;

    public function __construct(ChefService $chefService)
    {
        $this->chefService = $chefService;
    }

    /**
     * Display the chef's profile edit form.
     */
    public function edit(): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $chef->load(['categories', 'gallery', 'governorate', 'district', 'area']);

        $governorates = Governorate::where('is_active', true)->get(['id', 'name']);
        $districts = District::where('is_active', true)->get(['id', 'name', 'governorate_id']);
        $areas = Area::where('is_active', true)->get(['id', 'name', 'district_id']);
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Chef/Profile/Edit', [
            'chef' => [
                'id' => $chef->id,
                'name' => $chef->name,
                'short_description' => $chef->short_description,
                'long_description' => $chef->long_description,
                'bio' => $chef->bio,
                'email' => $chef->email,
                'phone' => $chef->phone,
                'logo' => $chef->logo,
                'banner' => $chef->banner,
                'address' => $chef->address,
                'governorate_id' => $chef->governorate_id,
                'district_id' => $chef->district_id,
                'area_id' => $chef->area_id,
                'base_hourly_rate' => $chef->base_hourly_rate,
                'status' => $chef->status,
                'rating_avg' => $chef->rating_avg,
                'categories' => $chef->categories->pluck('id'),
                'gallery' => $chef->gallery->map(fn($g) => [
                    'id' => $g->id,
                    'image' => $g->image,
                ]),
            ],
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'governorates' => $governorates,
            'districts' => $districts,
            'areas' => $areas,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the chef's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $this->authorize('update', $chef);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'bio' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'governorate_id' => 'nullable|exists:governorates,id',
            'district_id' => 'nullable|exists:districts,id',
            'area_id' => 'nullable|exists:areas,id',
            'base_hourly_rate' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($chef->logo) {
                Storage::disk('public')->delete($chef->logo);
            }
            $validated['logo'] = $request->file('logo')->store('chefs/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($chef->banner) {
                Storage::disk('public')->delete($chef->banner);
            }
            $validated['banner'] = $request->file('banner')->store('chefs/banners', 'public');
        }

        // Update chef profile
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);
        
        $chef->update($validated);

        // Sync categories
        if (!empty($categories)) {
            $chef->categories()->sync($categories);
        }

        return back()->with('success', __('chef.profile_updated'));
    }
}
