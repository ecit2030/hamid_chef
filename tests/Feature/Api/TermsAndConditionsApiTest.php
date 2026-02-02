<?php

namespace Tests\Feature\Api;

use App\Models\TermsAndConditions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermsAndConditionsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        TermsAndConditions::factory()->create([
            'title_ar' => 'الشروط والأحكام النسخة 1',
            'title_en' => 'Terms and Conditions Version 1',
            'content_ar' => 'محتوى الشروط والأحكام بالعربي',
            'content_en' => 'Terms and Conditions content in English',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ]);

        TermsAndConditions::factory()->create([
            'title_ar' => 'الشروط والأحكام النسخة 2',
            'title_en' => 'Terms and Conditions Version 2',
            'content_ar' => 'محتوى الشروط والأحكام بالعربي - نسخة 2',
            'content_en' => 'Terms and Conditions content in English - Version 2',
            'version' => '2.0',
            'is_active' => false,
            'effective_date' => now()->addDays(30),
        ]);
    }

    /** @test */
    public function it_can_get_active_terms_and_conditions_without_auth()
    {
        $response = $this->getJson('/api/terms-and-conditions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title_ar',
                    'title_en',
                    'content_ar',
                    'content_en',
                    'version',
                    'is_active',
                    'effective_date',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'title_ar' => 'الشروط والأحكام النسخة 1',
                    'title_en' => 'Terms and Conditions Version 1',
                    'version' => '1.0',
                    'is_active' => true,
                ],
            ]);
    }

    /** @test */
    public function it_returns_both_arabic_and_english_content()
    {
        $response = $this->getJson('/api/terms-and-conditions');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertArrayHasKey('title_ar', $data);
        $this->assertArrayHasKey('title_en', $data);
        $this->assertArrayHasKey('content_ar', $data);
        $this->assertArrayHasKey('content_en', $data);

        $this->assertEquals('الشروط والأحكام النسخة 1', $data['title_ar']);
        $this->assertEquals('Terms and Conditions Version 1', $data['title_en']);
    }

    /** @test */
    public function it_can_get_all_versions_without_auth()
    {
        $response = $this->getJson('/api/terms-and-conditions/versions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title_ar',
                        'title_en',
                        'content_ar',
                        'content_en',
                        'version',
                        'is_active',
                        'effective_date',
                    ],
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function it_can_get_specific_version_by_id_without_auth()
    {
        $terms = TermsAndConditions::where('version', '2.0')->first();

        $response = $this->getJson("/api/terms-and-conditions/{$terms->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $terms->id,
                    'title_ar' => 'الشروط والأحكام النسخة 2',
                    'title_en' => 'Terms and Conditions Version 2',
                    'version' => '2.0',
                    'is_active' => false,
                ],
            ]);
    }

    /** @test */
    public function it_returns_404_when_no_active_terms_exist()
    {
        // Deactivate all terms
        TermsAndConditions::query()->update(['is_active' => false]);

        $response = $this->getJson('/api/terms-and-conditions');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'لا توجد شروط وأحكام نشطة',
            ]);
    }

    /** @test */
    public function it_returns_404_when_specific_version_not_found()
    {
        $response = $this->getJson('/api/terms-and-conditions/999');

        $response->assertStatus(404);
    }
}
