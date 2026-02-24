<?php

namespace Database\Seeders;

use App\Models\ContactSubject;
use Illuminate\Database\Seeder;

class ContactSubjectSeeder extends Seeder
{
    /**
     * Seed contact subjects (runs only once when none exist).
     */
    public function run(): void
    {
        if (ContactSubject::count() > 0) {
            return;
        }

        $subjects = [
            ['title' => 'Algemene vraag', 'sort_order' => 1],
            ['title' => 'Demo', 'sort_order' => 2],
            ['title' => 'Prijsopgave', 'sort_order' => 3],
            ['title' => 'Implementatie', 'sort_order' => 4],
            ['title' => 'Ondersteuning', 'sort_order' => 5],
        ];

        foreach ($subjects as $subject) {
            ContactSubject::create(array_merge($subject, ['is_active' => true]));
        }
    }
}
