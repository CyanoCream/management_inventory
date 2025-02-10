<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::with('category');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function($row){
                    return $row->category->name;
                })
                ->addColumn('action', function($row){
                    return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('sub-categories.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'price_limit' => 'required|numeric|min:0',
        ]);

        SubCategory::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'price_limit' => 'required|numeric|min:0',
        ]);

        $subCategory->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return response()->json(['success' => true]);
    }

    public function getByCategory($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
        return response()->json($subCategories);
    }
}
