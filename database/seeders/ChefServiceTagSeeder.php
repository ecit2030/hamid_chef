<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChefService;
use App\Models\ChefServiceTag;
use App\Models\Tag;

class ChefServiceTagSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة وسوم خدمات الطهاة...');

        $services = ChefService::all();

        foreach ($services as $service) {
            $tags = Tag::inRandomOrder()->take(rand(2, 4))->get();
            
            foreach ($tags as $tag) {
                ChefServiceTag::firstOrCreate([
                    'chef_service_id' => $service->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }

        $this->command->info('✅ تم إضافة وسوم الخدمات بنجاح');
    }
}
