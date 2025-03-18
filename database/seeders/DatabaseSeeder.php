<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create a test user
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create some projects
        $projects = [
            [
                'name' => 'Project A',
                'status' => 'active',
            ],
            [
                'name' => 'Project B',
                'status' => 'inactive',
            ],
            [
                'name' => 'Project C',
                'status' => 'completed',
            ],
        ];

        foreach ($projects as $project) {
            $p = Project::create($project);
            $p->users()->attach($user->id);
        }

        // Create some attributes
        $attributes = [
            [
                'name' => 'department',
                'type' => 'text',
            ],
            [
                'name' => 'start_date',
                'type' => 'date',
            ],
            [
                'name' => 'end_date',
                'type' => 'date',
            ],
            [
                'name' => 'budget',
                'type' => 'number',
            ],
            [
                'name' => 'priority',
                'type' => 'select',
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
