<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Tag;

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void 
    {
         $skills = ['Laravel', 'Vue.js', 'React', 'UI/UX Design', 'Mobile Development', 'Python'];
    foreach ($skills as $skill) {
        Skill::firstOrCreate(['name' => $skill]);
    }

    $tags = ['Web', 'Mobile', 'Backend', 'Frontend', 'Design', 'Urgent'];
    foreach ($tags as $tag) {
        Tag::firstOrCreate(['name' => $tag]);
    }
    }
  }

