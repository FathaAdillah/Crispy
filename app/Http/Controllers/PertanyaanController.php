<?php

namespace App\Http\Controllers;

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
                $kuisioner = Kuisioner::with('variable')->select('aquestion.*');

                return DataTables::of($kuisioner)
                    ->addColumn('variable', function ($kuisioner) {
                        return $kuisioner->variable->name; // Assuming variable has a 'name' attribute
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
        return view('pertanyaan');
    }
}
