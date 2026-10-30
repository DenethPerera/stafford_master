<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Scale;
use App\Models\Size;
use App\Models\Style;
use Illuminate\Http\JsonResponse;

class MasterDataController extends Controller
{
    /**
     * Get all styles.
     */
    public function styles(): JsonResponse
    {
        $styles = Style::select('id', 'style_no', 'style_name', 'client_id', 'status')->get();

        return response()->json([
            'success' => true,
            'data' => $styles,
        ]);
    }

    /**
     * Get all sizes.
     */
    public function sizes(): JsonResponse
    {
        $sizes = Size::where('status', 'Active')->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $sizes,
        ]);
    }

    /**
     * Get all colors.
     */
    public function colors(): JsonResponse
    {
        $colors = Color::where('status', 'Active')->get();

        return response()->json([
            'success' => true,
            'data' => $colors,
        ]);
    }

    /**
     * Get all scales.
     */
    public function scales(): JsonResponse
    {
        $scales = Scale::where('status', 'Active')->get();

        return response()->json([
            'success' => true,
            'data' => $scales,
        ]);
    }
}