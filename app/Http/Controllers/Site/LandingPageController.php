<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Testimonial;
use App\Services\LandingPageSectionService;
use Inertia\Inertia;
use Inertia\Response;

class LandingPageController extends Controller
{
    /**
     * Display the public landing page with all active sections
     */
    public function index(LandingPageSectionService $service): Response
    {
        // Get all active sections - use empty with to avoid loading admin relations on public page
        $sections = $service->builder([])
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        // Transform sections into a keyed array for easy access
        $sectionsData = [];
        foreach ($sections as $section) {
            $additionalData = $section->additional_data ?? [];

            // For categories section: inject real categories from database
            if ($section->section_key === 'categories') {
                $additionalData['categories'] = $this->getCategoriesForLanding();
            }

            // For testimonials section: use admin items first, else Testimonial model
            if ($section->section_key === 'testimonials') {
                $additionalData['testimonials'] = $this->getTestimonialsForLanding($additionalData);
            }

            // For vision_mission section: transform items array to vision, mission, goals
            if ($section->section_key === 'vision_mission') {
                $additionalData = $this->transformVisionMissionForLanding($additionalData);
            }

            $sectionsData[$section->section_key] = [
                'id' => $section->id,
                'section_key' => $section->section_key,
                'title_ar' => $section->title_ar,
                'title_en' => $section->title_en,
                'description_ar' => $section->description_ar,
                'description_en' => $section->description_en,
                'image' => $section->image,
                'display_order' => $section->display_order,
                'additional_data' => $additionalData,
                'is_active' => $section->is_active,
            ];
        }

        // Get current locale from session or default to 'ar'
        $locale = session('locale', config('app.locale', 'ar'));

        return Inertia::render('Landing/Index', [
            'sections' => $sectionsData,
            'locale' => $locale,
        ]);
    }

    /**
     * Get active categories from database for landing page
     */
    private function getCategoriesForLanding(): array
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $placeholderImages = [
            'saudi-cuisine' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&q=80',
            'gulf-cuisine' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&q=80',
            'levantine-cuisine' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400&q=80',
            'egyptian-cuisine' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&q=80',
            'indian-cuisine' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=400&q=80',
            'italian-cuisine' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=400&q=80',
            'grills' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=400&q=80',
            'desserts' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&q=80',
            'seafood' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=400&q=80',
            'healthy-food' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80',
        ];

        return $categories->map(function (Category $category) use ($placeholderImages) {
            return [
                'id' => $category->id,
                'name_ar' => $category->name,
                'name_en' => $category->name,
                'slug' => $category->slug,
                'image' => $category->icon_url ?? ($placeholderImages[$category->slug] ?? 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=400&q=80'),
            ];
        })->values()->all();
    }

    /**
     * Transform vision_mission items array to vision, mission, goals structure for landing
     */
    private function transformVisionMissionForLanding(array $additionalData): array
    {
        $items = $additionalData['items'] ?? [];
        $vision = [];
        $mission = [];
        $goals = [];

        foreach ($items as $item) {
            $key = $item['key'] ?? null;
            $block = [
                'title_ar' => $item['title_ar'] ?? '',
                'title_en' => $item['title_en'] ?? '',
                'description_ar' => $item['description_ar'] ?? '',
                'description_en' => $item['description_en'] ?? '',
            ];
            if ($key === 'vision') {
                $vision = $block;
            } elseif ($key === 'mission') {
                $mission = $block;
            } elseif ($key === 'values') {
                $goals[] = $block;
            }
        }

        $additionalData['vision'] = $vision;
        $additionalData['mission'] = $mission;
        $additionalData['goals'] = $goals;

        return $additionalData;
    }

    /**
     * Get testimonials for landing: admin items (from landing_page_sections) or Testimonial model
     */
    private function getTestimonialsForLanding(array $additionalData): array
    {
        $items = $additionalData['items'] ?? [];
        if (!empty($items)) {
            $out = [];
            foreach ($items as $i => $t) {
                $out[] = [
                    'id' => $t['id'] ?? 'item-'.$i,
                    'comment_ar' => $t['content_ar'] ?? $t['comment_ar'] ?? '',
                    'comment_en' => $t['content_en'] ?? $t['comment_en'] ?? '',
                    'rating' => (int) ($t['rating'] ?? 5),
                ];
            }
            return $out;
        }

        return Testimonial::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get()
            ->map(fn (Testimonial $t) => [
                'id' => $t->id,
                'comment_ar' => $t->comment_ar,
                'comment_en' => $t->comment_en,
                'rating' => $t->rating,
            ])
            ->values()
            ->all();
    }
}
