<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function ProductPage(): View {
        return view('pages.dashboard.product-page');
    }

    public function ProductCreate(Request $request) {
        $user_id = $request->header('id');

        $image = $request->file('image');

        $time = time();
        $file_name = $image->getClientOriginalName();
        $image_name = "{$user_id}-{$time}-{$file_name}";
        $image_path = "uploads/{$image_name}";

        $image->move(public_path('uploads'), $image_name);

        return Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'image' => $image_path,
            'category_id' => $request->input('category_id'),
            'user_id' => $user_id
        ]);
    }

    public function ProductDelete(Request $request) {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        $file_path = $request->input('file_path');
        File::delete($file_path);
        return Product::where('user_id', $user_id)->where('id', $product_id)->delete();

    }

    public function ProductList(Request $request) {
        $user_id = $request->header('id');

        return Product::where('user_id', $user_id)->get();
    }

    public function ProductById(Request $request) {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        return Product::where('user_id', $user_id)->where('id', $product_id)->first();
    }

    // public function ProductBy(Request $request, $product_id) {
    //     $user_id = $request->header('id');

    //     return Product::where('user_id', $user_id)->where('id', $product_id)->first();
    // }

    public function ProductUpdate(Request $request) {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        if($request->hasFile('image')) {
            // Upload new image
            $image = $request->file('image');

            $time = time();
            $file_name = $image->getClientOriginalName();
            $image_name = "{$user_id}-{$time}-{$file_name}";
            $image_path = "uploads/{$image_name}";

            $image->move(public_path('uploads'), $image_name);

            // Delete old Image
            $file_path = $request->input('file_path');
            File::delete($file_path);

            // Update Database Table
            return Product::where('user_id', $user_id)->where('id', $product_id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'image' => $image_path,
                'category_id' => $request->input('category_id'),
            ]);
        }
        else {
            // Update Database Table
            return Product::where('user_id', $user_id)->where('id', $product_id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'category_id' => $request->input('category_id'),
            ]);
        }
    }

}
