<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AnswerController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $responden = Responden::select('id','name');

                return DataTables::of($responden)

                    ->addColumn('action', function ($responden) {
                        $Button = "<button class='btn btn-primary' data-id='" . $responden->id . "'>Isi Kuisioner</button>";
                        return $Button;
                    })
                    ->rawColumns(['bukti', 'action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        return view('jawaban');
    }

    public function show($responden_id)
    {
        try {
            $dataCombined = DB::table('aanswer_harapan as ah')
                ->select(
                    'ah.id as answer_id',
                    'ah.question_id',
                    'q.question as question_text',
                    'q.variable_id',
                    'v.name as variable_name',
                    'ah.category_id',
                    'c.name as category_name',
                    'ah.jawaban1',
                    'ah.jawaban2',
                    'ah.jawaban3',
                    'ah.jawaban4',
                    'ah.jawaban5',
                    'ah.responden_id',
                    'q.code_id',
                    'ac.name as code_name',
                    DB::raw('"harapan" as type')
                )
                ->join('aquestion as q', 'ah.question_id', '=', 'q.id')
                ->join('acategory as c', 'ah.category_id', '=', 'c.id')
                ->join('avariable as v', 'q.variable_id', '=', 'v.id')
                ->join('acode as ac', 'q.code_id', '=', 'ac.id')
                ->where('ah.responden_id', $responden_id)
                ->unionAll(
                    DB::table('aanswer_kepuasan as ak')
                        ->select(
                            'ak.id as answer_id',
                            'ak.question_id',
                            'q.question as question_text',
                            'q.variable_id',
                            'v.name as variable_name',
                            'ak.category_id',
                            'c.name as category_name',
                            'ak.jawaban1',
                            'ak.jawaban2',
                            'ak.jawaban3',
                            'ak.jawaban4',
                            'ak.jawaban5',
                            'ak.responden_id',
                            'q.code_id',
                            'ac.name as code_name',
                            DB::raw('"kepuasan" as type')
                        )
                        ->join('aquestion as q', 'ak.question_id', '=', 'q.id')
                        ->join('acategory as c', 'ak.category_id', '=', 'c.id')
                        ->join('avariable as v', 'q.variable_id', '=', 'v.id')
                        ->join('acode as ac', 'q.code_id', '=', 'ac.id')
                        ->where('ak.responden_id', $responden_id)
                )
                ->get();

            return response()->json($dataCombined);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}


