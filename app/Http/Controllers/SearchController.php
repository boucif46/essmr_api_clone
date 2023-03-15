<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  

    public function search(Request $request)
    {
        $tags = explode(',', $request->input('tags'));
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
    
        $results = Product::where(function ($query) use ($tags, $minPrice, $maxPrice) {
            foreach ($tags as $tag) {
                $query->orWhere('tags', 'like', '%' . $tag . '%');
            }
        })
        ->whereBetween('price', [$minPrice, $maxPrice])
        ->get();
    
        // Return the results to the view
        return response()->json(['product' => $results]);
    }
}
