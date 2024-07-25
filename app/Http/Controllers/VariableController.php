<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VariableController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $variable = Variable::where('is_active', 1)->where('is_delete', 0)->select('avariable.*');

                return DataTables::of($variable)
                    ->addColumn('action', function ($variable) {
                        $Button = "<button class='btn btn-primary btn-edit' data-id='" . $variable->id . "'><i class='fas fa-edit'></i></button>";
                        $Button .= " <button class='btn btn-danger btn-delete' data-id='" . $variable->id . "'><i class='fas fa-trash'></i></button>";
                        return $Button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        return view('variable');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            Variable::create([
                'name' => $request->name,
                'is_active' => 1,
                'is_delete' => 0
            ]);
            return response()->json(['success' => 'Variable created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating variable: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Taek', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $variable = Variable::findOrFail($id);
            return response()->json($variable);
        } catch (\Exception $e) {
            Log::error('Error fetching variable: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $variable = Variable::findOrFail($id);
            $variable->update([
                'name' => $request->name,
            ]);
            return response()->json(['success' => 'Variable updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating variable: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $variable = Variable::findOrFail($id);
            $variable->update([
                'is_delete' => 1,
                'is_active' =>0,
            ]);
            return response()->json(['success' => 'Variable deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting variable: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
