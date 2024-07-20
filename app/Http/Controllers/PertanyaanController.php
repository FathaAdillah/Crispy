<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Variable;
use App\Models\Kuisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PertanyaanController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $kuisioner = Kuisioner::where('is_active', 1)
                    ->where('is_delete', 0)
                    ->with('code')
                    ->with('variable')
                    ->select('aquestion.*');

                return DataTables::of($kuisioner)
                    ->addColumn('variable', function ($kuisioner) {
                        return $kuisioner->variable->name;
                    })
                    ->addColumn('code', function ($kuisioner) {
                        return $kuisioner->code->name;
                    })
                    ->addColumn('action', function ($kuisioner) {
                        $Button = "<button class='btn btn-primary btn-edit' data-id='" . $kuisioner->id . "'><i class='fas fa-edit'></i></button>";
                        $Button .= " <button class='btn btn-danger btn-delete' data-id='" . $kuisioner->id . "'><i class='fas fa-trash'></i></button>";
                        return $Button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        $variables = Variable::where('is_active', 1)->where('is_delete', 0)->get();
        $codes = Code::where('is_active', 1)->where('is_delete', 0)->get();

        return view('pertanyaan', compact('variables', 'codes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'code_id' => 'required|exists:acode,id',
            'variable_id' => 'required|exists:avariable,id',
        ]);

        try {
            Kuisioner::create([
                'question' => $request->question,
                'code_id' => $request->code_id,
                'variable_id' => $request->variable_id,
                'is_active' => 1,
                'is_delete' => 0,
            ]);
            return response()->json(['success' => 'Questionnaire created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating questionnaire: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $kuisioner = Kuisioner::findOrFail($id);
            return response()->json($kuisioner);
        } catch (\Exception $e) {
            Log::error('Error fetching questionnaire: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'code_id' => 'required|exists:acode,id',
            'variable_id' => 'required|exists:variables,id',
        ]);

        try {
            $kuisioner = Kuisioner::findOrFail($id);
            $kuisioner->update([
                'question' => $request->question,
                'code_id' => $request->code_id,
                'variable_id' => $request->variable_id,
            ]);
            return response()->json(['success' => 'Questionnaire updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating questionnaire: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kuisioner = Kuisioner::findOrFail($id);
            $kuisioner->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Questionnaire deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting questionnaire: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
