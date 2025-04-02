<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function addProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100', // Corregido: comas por pipes
            'price' => 'required|numeric', // Corregido: eliminada concatenación incorrecta
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        Product::create([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
        ]);
        return response()->json(['message' => 'Product added successfully'], 201);
    }

    public function getProduct(){
        $products = Product::all();
        if($products->isEmpty()){
            return response()->json(['message' => 'No products found'], 404);
        }
        return response()->json(['products' => $products], 200);
    }

    public function getProductById($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['product' => $product], 200);
    }

    public function updateProduct(Request $request, $id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|min:3|max:100', // Corregido: comas por pipes
            'price' => 'required|numeric', // Corregido: eliminada concatenación incorrecta
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        if($request->has('name')){
            $product->name = $request->get('name');
        }
        if($request->has('price')){
            $product->price = $request->get('price');
        }
        $product->update();
        return response()->json(['message' => 'Product updated successfully'], 200);
    } 

    public function deleteProduct($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
