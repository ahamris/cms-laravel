<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomepageBuilderController extends Controller
{
    /**
     * Get parameter options for a template
     */
    public function getTemplateParameters(Request $request): JsonResponse
    {
        $template = $request->get('template');
        
        if (!$template) {
            return response()->json(['options' => []]);
        }

        $options = Widget::getParameterOptions($template);
        
        return response()->json(['options' => $options]);
    }
}
