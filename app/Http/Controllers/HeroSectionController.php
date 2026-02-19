<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    /**
     * Get active hero sections for frontend display
     */
    public function getActiveHeroSections()
    {
        return HeroSection::active()->ordered()->get();
    }

    /**
     * Get the primary hero section (first active one)
     */
    public function getPrimaryHeroSection()
    {
        return HeroSection::active()->ordered()->first();
    }

    /**
     * API endpoint to get hero sections
     */
    public function index()
    {
        $heroSections = HeroSection::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $heroSections,
            'count' => $heroSections->count()
        ]);
    }

    /**
     * API endpoint to get a specific hero section
     */
    public function show(HeroSection $heroSection)
    {
        if (!$heroSection->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Hero section not found or inactive'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $heroSection
        ]);
    }
}
