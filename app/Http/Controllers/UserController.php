<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()
                ->when($request->role, function($q) use ($request) {
                    $q->where('role', $request->role);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actions = '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'">Edit</button>';
                    if ($row->is_locked) {
                        $actions .= '<button class="btn btn-sm btn-warning unlock-btn" data-id="'.$row->id.'">Unlock</button>';
                    } else {
                        $actions .= '<button class="btn btn-sm btn-danger lock-btn" data-id="'.$row->id.'">Lock</button>';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:8|max:100|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|min:8|max:100|email|unique:users',
            'role' => 'required|in:Admin,Operator',
        ]);

        $request->merge([
            'password' => Hash::make($request->password)
        ]);

        User::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|min:8|max:100|unique:users,username,'.$user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|min:8|max:100|email|unique:users,email,'.$user->id,
            'role' => 'required|in:Admin,Operator',
        ]);

        if ($request->filled('password')) {
            $request->merge([
                'password' => Hash::make($request->password)
            ]);
        } else {
            $request->request->remove('password');
        }

        $user->update($request->all());
        return response()->json(['success' => true]);
    }

    public function toggleLock(User $user)
    {
        $user->update(['is_locked' => !$user->is_locked]);
        return response()->json(['success' => true]);
    }
    public function show(User $user)
    {
        return response()->json($user);
    }
}
