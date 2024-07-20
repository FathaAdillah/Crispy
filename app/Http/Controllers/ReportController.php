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
                'ah.responden_id',
                'q.code_id',
                'ac.name as code_name'
            )
            ->join('aquestion as q', 'ah.question_id', '=', 'q.id')
            ->join('acategory as c', 'ah.category_id', '=', 'c.id')
            ->join('avariable as v', 'q.variable_id', '=', 'v.id')
            ->join('acode as ac', 'q.code_id', '=', 'ac.id')
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
                'ak.responden_id',
                'q.code_id',
                'ac.name as code_name'
            )
            ->join('aquestion as q', 'ak.question_id', '=', 'q.id')
            ->join('acategory as c', 'ak.category_id', '=', 'c.id')
            ->join('avariable as v', 'q.variable_id', '=', 'v.id')
            ->join('acode as ac', 'q.code_id', '=', 'ac.id')
            ->get();

        // Gabungkan dataHarapan dan dataKepuasan ke dalam satu array besar
        $allData = $dataHarapan->concat($dataKepuasan);

        // Hitung koefisien korelasi Pearson untuk semua data
        $correlation = $this->hitungKorelasi($allData);

        // Menghitung Cronbach's Alpha untuk jawaban harapan dan kepuasan
        $alphaHarapan = $this->cronbachAlpha($dataHarapan);
        $alphaKepuasan = $this->cronbachAlpha($dataKepuasan);

        // Hitung CSI per variable
        $csiPerVariable = $this->hitungCSI($dataHarapan, $dataKepuasan);

        // Hitung total CSI hanya jika terdapat elemen dalam $csiPerVariable
        if (count($csiPerVariable) > 0) {
            $totalCSI = array_sum(array_column($csiPerVariable, 'csi')) / count($csiPerVariable);
        } else {
            $totalCSI = 0;
        }

        // Hitung PIECES
        $piecesPerCode = $this->hitungPIECES($dataHarapan, $dataKepuasan);

        // Tampilkan hasil dalam view
        return view('report', [
            'dataHarapan' => $dataHarapan,
            'dataKepuasan' => $dataKepuasan,
            'allData' => $allData,
            'correlation' => $correlation,
            'alphaHarapan' => $alphaHarapan,
            'alphaKepuasan' => $alphaKepuasan,
            'csiPerVariable' => $csiPerVariable,
            'totalCSI' => round($totalCSI, 2),
            'piecesPerCode' => $piecesPerCode
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


    private function hitungCSI($dataHarapan, $dataKepuasan)
    {
        $csiPerVariable = [];

        // Group data by variable
        $groupedHarapan = $dataHarapan->groupBy('variable_id');
        $groupedKepuasan = $dataKepuasan->groupBy('variable_id');

        foreach ($groupedHarapan as $variableId => $harapanData) {
            if (isset($groupedKepuasan[$variableId])) {
                $kepuasanData = $groupedKepuasan[$variableId];

                $totalHarapan = $harapanData->count() * 5; // Assuming each question has 5 answers
                $totalKepuasan = $kepuasanData->count() * 5;

                $sumHarapan = $harapanData->sum(function ($item) {
                    return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
                });

                $sumKepuasan = $kepuasanData->sum(function ($item) {
                    return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
                });

                // Cek untuk menghindari pembagian dengan nol
                if ($totalHarapan == 0 || $totalKepuasan == 0) {
                    continue;
                }

                $meanHarapan = $sumHarapan / $totalHarapan;
                $meanKepuasan = $sumKepuasan / $totalKepuasan;

                // Cek untuk menghindari pembagian dengan nol
                if ($meanHarapan + $meanKepuasan == 0) {
                    continue;
                }

                $wf = $meanHarapan / ($meanHarapan + $meanKepuasan);
                $ws = $meanKepuasan / ($meanHarapan + $meanKepuasan);

                // Cek untuk menghindari pembagian dengan nol
                $csi = $meanHarapan != 0 ? round(($meanKepuasan / $meanHarapan) * 100, 2) : 0;

                $csiPerVariable[] = [
                    'variable_id' => $variableId,
                    'variable_name' => $harapanData->first()->variable_name,
                    'mis' => round($meanHarapan, 2),
                    'mss' => round($meanKepuasan, 2),
                    'wf' => round($wf, 2),
                    'ws' => round($ws, 2),
                    'csi' => $csi
                ];
            }
        }

        return $csiPerVariable;
    }

    private function hitungPIECES($dataHarapan, $dataKepuasan)
    {
        $piecesPerCode = [];

        // Group data by variable and code_id
        $groupedHarapan = $dataHarapan->groupBy(['variable_name', 'code_id']);
        $groupedKepuasan = $dataKepuasan->groupBy(['variable_name', 'code_id']);

        foreach ($groupedHarapan as $variableName => $codeGroups) {
            foreach ($codeGroups as $codeId => $harapanData) {
                if (isset($groupedKepuasan[$variableName][$codeId])) {
                    $kepuasanData = $groupedKepuasan[$variableName][$codeId];

                    $totalHarapan = $harapanData->count() * 5; // Assuming each question has 5 answers
                    $totalKepuasan = $kepuasanData->count() * 5;

                    $sumHarapan = $harapanData->sum(function ($item) {
                        return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
                    });

                    $sumKepuasan = $kepuasanData->sum(function ($item) {
                        return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
                    });

                    // Cek untuk menghindari pembagian dengan nol
                    if ($totalHarapan == 0 || $totalKepuasan == 0) {
                        continue;
                    }

                    $meanHarapan = $sumHarapan / $totalHarapan;
                    $meanKepuasan = $sumKepuasan / $totalKepuasan;

                    $piecesPerCode[] = [
                        'variable_name' => $variableName,
                        'code_name' => $harapanData->first()->code_name,
                        'mean_harapan' => round($meanHarapan, 2),
                        'mean_kepuasan' => round($meanKepuasan, 2)
                    ];
                }
            }
        }

        return $piecesPerCode;
    }
}
