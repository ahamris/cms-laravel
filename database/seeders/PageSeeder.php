<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Does not create a default homepage; the home route shows a welcome view
     * (front.home.index) until an admin sets a page as homepage via Pagina beheer.
     */
    public function run(): void
    {
        // Intentionally no default homepage: new projects see the welcome view
        // until the first page is created and set as homepage in the admin panel.
    }
}
