<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $update_category = null;
        if(!blank(request()->category_id)){
            $update_category = Category::find(request()->category_id);
        }
        $categories = Category::getAllCategories();
        return view('admin.category.index',compact('categories','update_category'));
    }

    public function getCategory()
    {
        $categories = Category::latest()->get();
        return response()->json(['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::getAllCategories();
        return view('admin.category.index',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'sometimes|nullable'
        ]);

        //make slug
        $slug = Str::slug($data['name']);
        $slug_count = Category::where('slug',$slug)->count();
        if($slug_count > 0){
            $slug = time()."-".$slug;
        }
        $data['slug'] = $slug;

        //store data
        $success = Category::create($data);

        if($success){
            return back()->with('success','Category created successfully!');
        }else{
            return back()->with('error','Something went wrong!!!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['$category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'sometimes|nullable'
        ]);
         //make slug
         $slug = Str::slug($data['name']);
         $slug_count = Category::where('slug',$slug)->count();
         if($slug_count > 0){
             $slug = time()."-".$slug;
         }
         $data['slug'] = $slug;

         $success = $category->update($data);
         if($success){
             return redirect()->route('categories.index')->with('success','Category updated successfully!');
         }else{
             return back()->with('error','Something went wrong!!!');
         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Category deleted successfully!');
    }
}
