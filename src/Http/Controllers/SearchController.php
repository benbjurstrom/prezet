<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Data\HeadingData;
use BenBjurstrom\Prezet\Models\Heading;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SearchController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = $request->input('q');

        $results = Heading::where('text', 'LIKE', "%{$query}%")
            ->with('document')
            ->limit(5)
            ->get()
            ->map(function ($heading) {
                return HeadingData::fromModel($heading);
            });

        return response()->json($results);
    }
}
