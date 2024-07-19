<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MathPHP\Statistics\Descriptive;

class ReportController extends Controller
{
    public function index()
    {
        // Query untuk mengambil data jawaban harapan
        $dataHarapan = DB::table('aanswer_harapan as ah')
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
                'ah.responden_id'
            )
            ->join('aquestion as q', 'ah.question_id', '=', 'q.id')
            ->join('acategory as c', 'ah.category_id', '=', 'c.id')
            ->join('avariable as v', 'q.variable_id', '=', 'v.id')
            ->get();

        // Query untuk mengambil data jawaban kepuasan
        $dataKepuasan = DB::table('aanswer_kepuasan as ak')
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
                'ak.responden_id'
            )
            ->join('aquestion as q', 'ak.question_id', '=', 'q.id')
            ->join('acategory as c', 'ak.category_id', '=', 'c.id')
            ->join('avariable as v', 'q.variable_id', '=', 'v.id')
            ->get();

        // Gabungkan dataHarapan dan dataKepuasan ke dalam satu array besar
        $allData = $dataHarapan->concat($dataKepuasan);

        // Hitung koefisien korelasi Pearson untuk semua data
        $correlation = $this->hitungKorelasi($allData);

        // Menghitung Cronbach's Alpha untuk jawaban harapan dan kepuasan
        $alphaHarapan = $this->cronbachAlpha($dataHarapan);
        $alphaKepuasan = $this->cronbachAlpha($dataKepuasan);


        // Tampilkan hasil dalam view
        return view('report', [
            'dataHarapan' => $dataHarapan,
            'dataKepuasan' => $dataKepuasan,
            'allData' => $allData,
            'correlation' => $correlation,
            'alphaHarapan' => $alphaHarapan,
            'alphaKepuasan' => $alphaKepuasan
        ]);
    }

    private function cronbachAlpha($data)
    {
        $items = [];
        foreach ($data as $row) {
            $items[] = [
                $row->jawaban1,
                $row->jawaban2,
                $row->jawaban3,
                $row->jawaban4,
                $row->jawaban5,
            ];
        }


        if (empty($items) || !isset($items[0])) {
            return 0;
        }

        $k = count($items[0]);
        $itemSum = array_map('array_sum', $items);
        $totalVariance = Descriptive::populationVariance($itemSum);
        $itemVariances = array_map([Descriptive::class, 'populationVariance'], $items);

        $alpha = ($k / ($k - 1)) * (1 - array_sum($itemVariances) / $totalVariance);
        return $alpha;
    }

    private function hitungKorelasi($data)
    {

        $correlation = [];


        if ($data->isEmpty()) {
            return $correlation;
        }

        for ($i = 1; $i <= 5; $i++) {

            $mean = $data->avg("jawaban$i");


            $covariance = $this->hitungCovariance($data, "jawaban$i");


            $stddev = $this->hitungStdDeviation($data, "jawaban$i");


            if ($stddev == 0) {

                $correlation["pearson_corr_jawaban$i"] = 0;
            } else {
                $pearsonCorrelation = $covariance / ($stddev * $stddev);
                $correlation["pearson_corr_jawaban$i"] = $pearsonCorrelation;
            }
        }

        return $correlation;
    }

    private function hitungCovariance($data, $column)
    {
        $covariance = 0;
        $mean = $data->avg($column);

        foreach ($data as $item) {
            $covariance += ($item->$column - $mean) * ($item->$column - $mean);
        }

        return $covariance / count($data);
    }

    private function hitungStdDeviation($data, $column)
    {
        $mean = $data->avg($column);
        $variance = 0;

        foreach ($data as $item) {
            $variance += pow($item->$column - $mean, 2);
        }

        $stddev = sqrt($variance / count($data));


        if ($stddev == 0) {

            $stddev = 0.001;
        }

        return $stddev;
    }
}
