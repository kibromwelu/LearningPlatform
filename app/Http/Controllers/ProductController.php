<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(Request  $request)
    {
        $products = Product::getAll($request->numberOfItems);
        return response()->json(['products' =>$products], 200);
    }
    public function show($id){
        $product = Product::getOne($id);
        return response()->json(['product' =>$product], 200);
    }
     public function store(ProductRequest $request)
    {
        
        $input = $request->only(['name', 'description', 'price','user_id']);
        $product = Product::createProduct($input);

        return response()->json(['message' => 'product registered successfully'], 201);
    }
    
    
    public function updateProduct(UpdateProductRequest $request, $id)
    {
        // dd($id);
        $product = Product::updateProduct($request->all(), $id);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
       $product = Product::deleteProduct($id);
        return response()->json($product, 202);
    }
}
