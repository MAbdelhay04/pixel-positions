<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::insert([
            ['name' => 'php'],
            ['name' => 'laravel'],
            ['name' => 'mysql'],
            ['name' => 'javascript'],
            ['name' => 'vue.js'],
            ['name' => 'react'],
            ['name' => 'html'],
            ['name' => 'css'],
            ['name' => 'tailwind css'],
            ['name' => 'git'],
            ['name' => 'rest api'],
            ['name' => 'api development'],
            ['name' => 'unit testing'],
            ['name' => 'phpunit'],
            ['name' => 'database design'],
            ['name' => 'sql'],
            ['name' => 'eloquent'],
            ['name' => 'mvc'],
            ['name' => 'docker'],
            ['name' => 'linux'],
            ['name' => 'bash'],
            ['name' => 'debugging'],
            ['name' => 'performance optimization'],
            ['name' => 'problem solving'],
            ['name' => 'communication'],
            ['name' => 'team collaboration'],
            ['name' => 'agile methodology'],
            ['name' => 'scrum'],
            ['name' => 'jira'],
            ['name' => 'restful services'],
            ['name' => 'json'],
            ['name' => 'api integration'],
            ['name' => 'responsive design'],
            ['name' => 'cross browser compatibility'],
            ['name' => 'oauth'],
            ['name' => 'authentication'],
            ['name' => 'authorization'],
            ['name' => 'version control'],
            ['name' => 'code review'],
            ['name' => 'continuous integration'],
            ['name' => 'continuous deployment'],
            ['name' => 'testing'],
            ['name' => 'software development'],
            ['name' => 'problem analysis'],
            ['name' => 'data modeling'],
            ['name' => 'jquery'],
            ['name' => 'bootstrap'],
            ['name' => 'project management'],
        ]);
    }
}
