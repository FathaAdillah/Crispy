<?php

namespace App\Http\Controllers;


use Nette\Utils\Image;
use App\Models\Kuisioner;
use App\Models\Responden;
use Illuminate\Http\Request;
use App\Models\AnswerHarapan;
use App\Models\AnswerKepuasan;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KuisionerController extends Controller
{

    public function index()
    {
        $questions = Kuisioner::with('variable')
            ->orderBy('variable_id')
            ->orderBy('id')
            ->get()
            ->groupBy('variable_id');

        return view('kuisioner2', compact('questions'));
    }

    public function submit(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:aresponden,email',
            'pekerjaan' => 'required|string',
            'pekerjaanLain' => 'nullable|string',
            'instansi' => 'required|string',
            'jenisKelamin' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'responses.*.*' => 'required|in:1,2,3,4,5',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        try {
            if ($request->pekerjaan === 'lain-lain') {
                $request->pekerjaan = $request->pekerjaanLain;
            }

            $buktiPath = null;
            if ($request->file('bukti')) {
                try {
                    $bukti = $request->file('bukti');
                    $path = $bukti->store('bukti', 'public');
                    $buktiPath = $path;
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                    return response('Error', 500);
                }
            }

            $respondent = Responden::create([
                'name' => $request->name,
                'email' => $request->email,
                'pekerjaan' => $request->pekerjaan,
                'instansi' => $request->instansi,
                'jenis_kelamin' => $request->jenisKelamin,
                'bukti' => $buktiPath,
            ]);


            foreach ($request->responses as $questionId => $categories) {
                foreach ($categories as $categoryId => $value) {
                    $value = (int) $value;

                    if ($categoryId == 1) {
                        AnswerHarapan::create([
                            'question_id' => $questionId,
                            'responden_id' => $respondent->id,
                            'category_id' => $categoryId,
                            'jawaban1' => $value == 1 ? 1 : 0,
                            'jawaban2' => $value == 2 ? 2 : 0,
                            'jawaban3' => $value == 3 ? 3 : 0,
                            'jawaban4' => $value == 4 ? 4 : 0,
                            'jawaban5' => $value == 5 ? 5 : 0,
                        ]);
                    } elseif ($categoryId == 2) {
                        AnswerKepuasan::create([
                            'question_id' => $questionId,
                            'responden_id' => $respondent->id,
                            'category_id' => $categoryId,
                            'jawaban1' => $value == 1 ? 1 : 0,
                            'jawaban2' => $value == 2 ? 2 : 0,
                            'jawaban3' => $value == 3 ? 3 : 0,
                            'jawaban4' => $value == 4 ? 4 : 0,
                            'jawaban5' => $value == 5 ? 5 : 0,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Kuisioner berhasil dikirim!');
        } catch (Exception $e) {
            if (isset($respondent)) {
                $respondent->delete();
            }
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan kuisioner. Silakan coba lagi.');
        }
    }
}
