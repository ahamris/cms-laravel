<?php

namespace App\Http\Controllers;

use App\Models\RobotsTxt;
use Illuminate\Http\Response;

class RobotsTxtController extends Controller
{
    /**
     * Display the robots.txt content
     */
    public function index(): Response
    {
        $content = RobotsTxt::getCachedContent();

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
