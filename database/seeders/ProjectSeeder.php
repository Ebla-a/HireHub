<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $client = User::where('role', 'client')->first();
    $webTag = Tag::where('name', 'Web')->first();

    $project = $client->projects()->create([
        'title' => 'Build E-commerce API',
        'description' => 'Detailed description for the project...',
        'budget_type' => 'fixed',
        'budget' => 1500,
        'deadline' => now()->addMonths(2),
        'status' => 'open'
    ]);

    $project->tags()->attach($webTag->id);
}
}
