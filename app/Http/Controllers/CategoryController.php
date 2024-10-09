<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function CategoryPage() {
        return view('pages.dashboard.category-page');
    }

    public function CategoryList(Request $request) {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get(); 
    }

    public function CategoryById(Request $request) {
        $user_id = $request->header('id');
        $category_id = $request->input('id');
        return Category::where('user_id', $user_id)->where('id', $category_id)->first();
    }
    public function CategoryCreate(Request $request) {
       try {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ], [
            'name.unique' => 'Category name already exists',
        ]);
        
        $user_id = $request->header('id');

        Category::create([
           'name' => $request->input('name'),
           'user_id' => $user_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category Created Successfully'
        ]);
       }
       catch(ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'status' => 'failed',
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function CategoryUpdate(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ], [
                'name.unique' => 'Category name already exists',
            ]);
            
            $category_id = $request->input('id');
            $user_id = $request->header('id');
            Category::where('id', $category_id)->where('user_id', $user_id)->update([
                'name' => $request->input('name')
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Category Updated Successfully'
            ]);
        }
        catch(ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'status' => 'failed',
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }

    }

    public function CategoryDelete(Request $request) {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->delete();
    }

}
