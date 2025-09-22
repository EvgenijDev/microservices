<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $inStock = filter_var($request->boolean('inStock'), FILTER_VALIDATE_BOOLEAN);
        // if (!$query) {
        //     return response()->json([]);
        // }
        try {
            $results = Product::search($query, $inStock);
            $products = collect($results['hits']['hits'])->map(function ($hit) {
                return $hit['_source'];
            });
            return response()->json([
                'data' => $products,
                'total' => $results['hits']['total']['value']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}