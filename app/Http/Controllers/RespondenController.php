<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RespondenController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $responden = Responden::select('aresponden.*');

                return DataTables::of($responden)
                    ->addColumn('action', function ($responden) {
                        $Button = "<button class='btn btn-primary btn-edit' data-id='" . $responden->id . "'><i class='fas fa-edit'></i></button>";
                        $Button .= " <button class='btn btn-danger btn-delete' data-id='" . $responden->id . "'><i class='fas fa-trash'></i></button>";
                        return $Button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        return view('responden');
    }
}