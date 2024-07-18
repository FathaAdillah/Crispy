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
                $variable = Variable::select('avariable.*');

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
}
