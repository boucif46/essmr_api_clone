<?php


namespace App\Http\Controllers;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function getproduct()
    {
        $startIndex = request()->input('startIndex', 0); // default to 0 if not provided
        $limit = request()->input('limit', 10); // default to 10 if not provided
        
        $products = DB::table('products')
            ->skip($startIndex)
            ->take($limit)
            ->get();
            return response()->json(['product' => $products]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }





    public function store(Request $request)
{
   
    $validator = Validator::make($request->all(), [
        'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2500',
        'title' => 'required|string|max:255',
        'tags'=>'required|string',
        'price' => 'required|numeric',
        'description' => 'required|string',
        
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
     // Create the "public/images" directory if it doesn't exist
      $destinationPath = public_path('/images');
      if (!File::exists($destinationPath)) {
          File::makeDirectory($destinationPath, 0775, true);
      }

    if ($request->hasFile('image_url')) {
        $image = $request->file('image_url');
        $name = $image->getClientOriginalName();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
    }

    $product = Product::create([
        'image_url' => '/images/'.$name,
        'title' => $request->title,
        'tags'=>$request->tags,
        'description' => $request->description,
        'price'=>$request->price,
        
        
    ]);

    return response()->json([
        'message' => 'product stored succeffully.',
        'product' => $product,
        
    ], 200);
}

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('title')) {
            $product->title = $request->title;
        }

        if ($request->has('description')) {
            $product->description = $request->description;
        }

        if ($request->has('price')) {
            $product->price = $request->price;
        }

        if ($request->has('image_url')) {
            $product->image_url = $request->image_url;
        }

        $product->save();

        return response()->json(['product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
