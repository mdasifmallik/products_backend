<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use phpDocumentor\Reflection\PseudoTypes\True_;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product_id = Product::insertGetId($request->except('image') + [
            'created_at' => Carbon::now()
        ]);

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photo_name = $product_id . "." . $photo->getClientOriginalExtension();
            $photo_location = 'public/uploads/product_photos/' . $photo_name;
            Image::make($photo)->fit(450, 450)->save(base_path($photo_location), 50);

            Product::findOrFail($product_id)->update([
                'image' => $photo_name
            ]);
        }

        return response()->json(['successMessage' => "New Product Created Successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product->update($request->except('_method', 'image'));

        if ($request->hasFile('image')) {
            if ($product->image != 'default.jpg') {
                $old_photo_link = 'public/uploads/product_photos/' . $product->image;
                unlink(base_path($old_photo_link));
            }

            $photo = $request->file('image');
            $photo_name = $product->id . "." . $photo->getClientOriginalExtension();
            $photo_location = 'public/uploads/product_photos/' . $photo_name;
            Image::make($photo)->fit(450, 450)->save(base_path($photo_location), 50);

            $product->update([
                'image' => $photo_name
            ]);
        }

        return response()->json(['successMessage' => "Product Updated Successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image != 'default.jpg') {
            $old_photo_link = 'public/uploads/product_photos/' . $product->image;
            unlink(base_path($old_photo_link));
        }

        $product->delete();

        return response()->json(['successMessage' => "Product Deleted Successfully"]);
    }
}
