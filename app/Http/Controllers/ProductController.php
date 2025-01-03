<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $keyword = request()->get('search');
        $perPage = 5;
        if(!empty($keyword)){
            $products = Product::where('name', 'LIKE', "%$keyword%") // Search in the name column
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('category', 'LIKE', "%$keyword%")
                ->orWhere('quantity', 'LIKE', "%$keyword%")
                ->orWhere('price', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $products = Product::latest()->paginate($perPage); // Get the products in the database
        }
        return view('products.index', ['products' => $products])->with('i', (request()->input('page', 1) - 1) *5); // Pass the products to the view
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {

        // Validate the request
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $product = new Product();

        $file_name = time() . '.' . request()->image->getClientOriginalExtension(); // Generate unique name
        request()->image->move(public_path('images'), $file_name); // Move the file to public path

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->category = $request->category;
        $product->image = $file_name;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
       $product = Product::findOrFail($id);
         return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',

        ]);

        $file_name = $request->hidden_product_image;

        if($request->image != '')
        {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $file_name = time() . '.' . request()->image->getClientOriginalExtension(); // Generate unique name
            request()->image->move(public_path('images'), $file_name); // Move the file to public path
        }

        $product = Product::find($request->hidden_id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->category = $request->category;
        $product->image = $file_name;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');

    }

    public function destroy($id)
    {
        // Find the product by ID or fail
        $product = Product::findOrFail($id);

        // Define the image path
        $image_path = public_path() . "/images/";
        $image = $image_path . $product->image;

        // Check if the image file exists and delete it
        if (file_exists($image)) {
            @unlink($image); // Delete the image
        }

        // Delete the product from the database
        $product->delete();

        // Redirect back to the products list with a success message
        return redirect('products')->with('success', 'Product deleted successfully.');
    }
}
