<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('categories.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:categories,code',
            'name' => 'required|string|max:100',
        ]);

        Category::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:categories,code,'.$category->id,
            'name' => 'required|string|max:100',
        ]);

        $category->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
