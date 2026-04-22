<?php

namespace Database\Seeders;

use App\Models\HeroImage;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class BerandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Hero Images
        HeroImage::updateOrCreate(
            ['position' => 'main'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1300&q=80',
                'alt_text' => 'Foto utama tim',
            ]
        );

        HeroImage::updateOrCreate(
            ['position' => 'side1'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80',
                'alt_text' => 'Aktivitas tim 1',
            ]
        );

        HeroImage::updateOrCreate(
            ['position' => 'side2'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=900&q=80',
                'alt_text' => 'Aktivitas tim 2',
            ]
        );

        // Seed Team Members
        $teamMembers = [
            [
                'name' => 'Ari Pratama',
                'role' => 'Project Coordinator',
                'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=900&q=80',
                'order' => 0,
            ],
            [
                'name' => 'Nadia Putri',
                'role' => 'UI/UX Designer',
                'image_url' => 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=900&q=80',
                'order' => 1,
            ],
            [
                'name' => 'Rizki Fadillah',
                'role' => 'Frontend Developer',
                'image_url' => 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=900&q=80',
                'order' => 2,
            ],
            [
                'name' => 'Dina Rahma',
                'role' => 'Backend Developer',
                'image_url' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=900&q=80',
                'order' => 3,
            ],
            [
                'name' => 'Fajar Maulana',
                'role' => 'Quality Assurance',
                'image_url' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=900&q=80',
                'order' => 4,
            ],
            [
                'name' => 'Salsa Dewi',
                'role' => 'Data Analyst',
                'image_url' => 'https://images.unsplash.com/photo-1556157382-97eda2d62296?auto=format&fit=crop&w=900&q=80',
                'order' => 5,
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }
    }
}
