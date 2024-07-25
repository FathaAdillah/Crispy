<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CodeController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $code = Code::where('is_active', 1)->where('is_delete', 0)->select('acode.*');

                return DataTables::of($code)
                    ->addColumn('action', function ($code) {
                        $Button = "<button class='btn btn-primary btn-edit' data-id='" . $code->id . "'><i class='fas fa-edit'></i></button>";
                        $Button .= " <button class='btn btn-danger btn-delete' data-id='" . $code->id . "'><i class='fas fa-trash'></i></button>";
                        return $Button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        return view('code');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            Code::create([
                'name' => $request->name,
                'is_active' => 1,
                'is_delete' => 0
            ]);
            return response()->json(['success' => 'code created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating code: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Taek', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $code = Code::findOrFail($id);
            return response()->json($code);
        } catch (\Exception $e) {
            Log::error('Error fetching code: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $code = Code::findOrFail($id);
            $code->update([
                'name' => $request->name,
            ]);
            return response()->json(['success' => 'code updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating code: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $code = Code::findOrFail($id);
            $code->update([
                'is_delete' => 1,
                'is_active' => 0,
            ]);
            return response()->json(['success' => 'code deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting code: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
