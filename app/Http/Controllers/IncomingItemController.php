<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IncomingItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = IncomingItem::with(['user', 'subCategory.category'])
                ->when($request->category_id, function($q) use ($request) {
                    $q->whereHas('subCategory', function($q) use ($request) {
                        $q->where('category_id', $request->category_id);
                    });
                })
                ->when($request->sub_category_id, function($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                })
                ->when($request->year, function($q) use ($request) {
                    $q->whereYear('created_at', $request->year);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                    return $row->subCategory->category->name;
                })
                ->addColumn('sub_category', function($row){
                    return $row->subCategory->name;
                })
                ->addColumn('operator', function($row){
                    return $row->user->name;
                })
                ->addColumn('action', function($row){
                    $actions = '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'">Edit</button>
                               <button class="btn btn-sm btn-info print-btn" data-id="'.$row->id.'">Print</button>';
                    if ($row->is_verified) {
                        $actions .= '<button class="btn btn-sm btn-success verify-btn active" data-id="'.$row->id.'"><i class="fas fa-check"></i></button>';
                    } else {
                        $actions .= '<button class="btn btn-sm btn-secondary verify-btn" data-id="'.$row->id.'"><i class="fas fa-check"></i></button>';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('incoming-items.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'source' => 'required|string|max:200',
            'letter_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:doc,docx,zip',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:200',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:40',
            'items.*.expired_date' => 'nullable|date',
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments');
        }

        $incomingItem = IncomingItem::create([
            'user_id' => auth()->id(),
            'sub_category_id' => $request->sub_category_id,
            'source' => $request->source,
            'letter_number' => $request->letter_number,
            'attachment' => $attachmentPath,
        ]);

        foreach ($request->items as $item) {
            $incomingItem->details()->create($item);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, IncomingItem $incomingItem)
    {
        // Similar validation as store
        $incomingItem->update($request->except('items'));

        // Update or create details
        $incomingItem->details()->delete();
        foreach ($request->items as $item) {
            $incomingItem->details()->create($item);
        }

        return response()->json(['success' => true]);
    }

    public function toggleVerification(IncomingItem $incomingItem)
    {
        $incomingItem->update(['is_verified' => !$incomingItem->is_verified]);
        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        // Excel export logic here
    }

    public function print(IncomingItem $incomingItem)
    {
        // Print logic here
    }
}

