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

        // Menghitung Uji Validitas
        $scoresHarapan = $this->calculateScoresV($dataHarapan);
        $scoresKepuasan = $this->calculateScoresV($dataKepuasan);
        $correlations = $this->calculateCorrelation($scoresHarapan, $scoresKepuasan);

        // Menghitung Cronbach's Alpha untuk jawaban harapan dan kepuasan
        $scoresHarapan = $this->calculateScoresR($dataHarapan);
        $scoresKepuasan = $this->calculateScoresR($dataKepuasan);

        // Menghitung Cronbach's Alpha
        $alphaHarapan = $this->calculateCronbachAlphaR($scoresHarapan);
        $alphaKepuasan = $this->calculateCronbachAlphaR($scoresKepuasan);

        // Hitung CSI per variable
        $csiPerVariable = $this->hitungCSI($dataHarapan, $dataKepuasan);

        // Hitung total CSI hanya jika terdapat elemen dalam $csiPerVariable
        if (count($csiPerVariable) > 0) {
            $totalCSI = array_sum(array_column($csiPerVariable, 'csi')) / count($csiPerVariable);
        } else {
            $totalCSI = 0;
        }

        //Menghitung PIECES Framework
        $piecesHarapan = $this->calculatePIECES($dataHarapan);
        $piecesKepuasan = $this->calculatePIECES($dataKepuasan);

        // $piecesPerCode = $this->hitungPIECES($dataHarapan, $dataKepuasan);


        return view('report', [
            'dataHarapan' => $dataHarapan,
            'dataKepuasan' => $dataKepuasan,
            'allData' => $allData,
            'correlations' => $correlations,
            'alphaHarapan' => $alphaHarapan,
            'alphaKepuasan' => $alphaKepuasan,
            'csiPerVariable' => $csiPerVariable,
            'totalCSI' => round($totalCSI, 2),
            'piecesHarapan' => $piecesHarapan,
            'piecesKepuasan' => $piecesKepuasan,
            // 'piecesPerCode' => $piecesPerCode
        ]);
    }


    //Function Validitas
    public function calculateScoresV($data)
    {
        $scores = [];
        foreach ($data as $item) {
            $respondentId = $item->responden_id;
            $codeId = $item->code_id;
            $score = $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;

            if (!isset($scores[$respondentId])) {
                $scores[$respondentId] = [];
            }
            if (!isset($scores[$respondentId][$codeId])) {
                $scores[$respondentId][$codeId] = 0;
            }

            $scores[$respondentId][$codeId] += $score;
        }
        return $scores;
    }

    public function calculateCorrelation($scoresHarapan, $scoresKepuasan)
    {
        $correlations = [];
        foreach ($scoresHarapan as $respondentId => $codes) {
            foreach ($codes as $codeId => $scoreHarapan) {
                if (isset($scoresKepuasan[$respondentId][$codeId])) {
                    $scoreKepuasan = $scoresKepuasan[$respondentId][$codeId];

                    // Menghitung koefisien korelasi (Pearson correlation coefficient)
                    $n = count($scoresHarapan);
                    $sumX = array_sum(array_column($scoresHarapan, $codeId));
                    $sumY = array_sum(array_column($scoresKepuasan, $codeId));
                    $sumXY = 0;
                    $sumX2 = 0;
                    $sumY2 = 0;

                    foreach ($scoresHarapan as $resId => $codes) {
                        $x = $codes[$codeId];
                        $y = $scoresKepuasan[$resId][$codeId];
                        $sumXY += $x * $y;
                        $sumX2 += $x * $x;
                        $sumY2 += $y * $y;
                    }

                    $numerator = $n * $sumXY - $sumX * $sumY;
                    $denominator = sqrt(($n * $sumX2 - $sumX * $sumX) * ($n * $sumY2 - $sumY * $sumY));
                    $correlation = ($denominator != 0) ? $numerator / $denominator : 0;

                    $correlations[$codeId] = $correlation;
                }
            }
        }
        return $correlations;
    }

    //Function Uji Reabilitas
    public function calculateVarianceR($array)
    {
        $count = count($array);
        if ($count === 0) {
            return 0;
        }

        $mean = array_sum($array) / $count;
        $sumOfSquares = array_reduce($array, function ($carry, $item) use ($mean) {
            return $carry + pow($item - $mean, 2);
        }, 0);

        return $count > 1 ? $sumOfSquares / ($count - 1) : 0;
    }

    public function calculateCronbachAlphaR($data)
    {
        if (empty($data)) {
            return 0; // Atau nilai lain yang sesuai untuk menunjukkan bahwa data kosong
        }

        $firstElement = reset($data);
        if (!is_array($firstElement) || count($firstElement) === 0) {
            return 0; // Atau nilai lain yang sesuai untuk menunjukkan bahwa data kosong atau tidak valid
        }

        $itemCount = count($firstElement);
        $respondentCount = count($data);
        $totalVariance = 0;
        $itemVariances = [];

        // Menghitung varians setiap item
        for ($i = 0; $i < $itemCount; $i++) {
            $itemScores = array_column($data, $i);
            $itemVariance = $this->calculateVarianceR($itemScores);
            $itemVariances[] = $itemVariance;
        }

        // Menghitung varians total
        $totalScores = array_map('array_sum', $data);
        $totalVariance = $this->calculateVarianceR($totalScores);

        if ($totalVariance == 0) {
            return 0; // Menghindari pembagian dengan nol
        }

        // Menghitung Cronbach's Alpha
        $sumItemVariances = array_sum($itemVariances);
        $alpha = ($itemCount / ($itemCount - 1)) * (1 - ($sumItemVariances / $totalVariance));

        return $alpha;
    }

    public function calculateScoresR($data)
    {
        $scores = [];
        foreach ($data as $item) {
            $respondentId = $item->responden_id;
            $codeId = $item->code_id;
            $score = $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;

            if (!isset($scores[$respondentId])) {
                $scores[$respondentId] = [];
            }
            if (!isset($scores[$respondentId][$codeId])) {
                $scores[$respondentId][$codeId] = 0;
            }

            $scores[$respondentId][$codeId] += $score;
        }

        // Mengubah struktur $scores menjadi array 2 dimensi
        $flattenedScores = [];
        foreach ($scores as $respondentScores) {
            $flattenedScores[] = array_values($respondentScores);
        }

        return $flattenedScores;
    }



    //Function CSI
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

    //Function PIECES Framework
    public function calculatePIECES($data)
    {
        if (empty($data)) {
            return [
                'totalSumPerCodeId' => [],
                'JSK' => [],
                'RK' => 0,
                'codeCount' => 0,
                'codeNames' => []
            ];
        }

        // Menghitung total sum per code_id
        $totalSumPerCodeId = [];
        $respondentCounts = [];
        $codeNames = [];

        foreach ($data as $item) {
            $codeId = $item->code_id;
            $score = $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;

            if (!isset($totalSumPerCodeId[$codeId])) {
                $totalSumPerCodeId[$codeId] = 0;
                $respondentCounts[$codeId] = 0;
                $codeNames[$codeId] = $item->code_name; // Menyimpan nama code_id
            }

            $totalSumPerCodeId[$codeId] += $score;
            $respondentCounts[$codeId]++;
        }

        // Menghitung JSK (Jumlah Skor Kuisioner)
        $JSK = [];
        foreach ($totalSumPerCodeId as $codeId => $sum) {
            $JSK[$codeId] = $sum / $respondentCounts[$codeId];
        }

        // Menghitung RK
        $totalJSK = array_sum($JSK);
        $codeCount = count($totalSumPerCodeId);
        $RK = $codeCount ? $totalJSK / $codeCount : 0;

        return [
            'totalSumPerCodeId' => $totalSumPerCodeId,
            'JSK' => $JSK,
            'RK' => $RK,
            'codeCount' => $codeCount,
            'totalJSK' => $totalJSK,
            'codeNames' => $codeNames
        ];
    }

    // private function hitungPIECES($dataHarapan, $dataKepuasan)
    // {
    //     $piecesPerCode = [];

    //     // Group data by variable and code_id
    //     $groupedHarapan = $dataHarapan->groupBy(['variable_name', 'code_id']);
    //     $groupedKepuasan = $dataKepuasan->groupBy(['variable_name', 'code_id']);

    //     foreach ($groupedHarapan as $variableName => $codeGroups) {
    //         foreach ($codeGroups as $codeId => $harapanData) {
    //             if (isset($groupedKepuasan[$variableName][$codeId])) {
    //                 $kepuasanData = $groupedKepuasan[$variableName][$codeId];

    //                 $totalHarapan = $harapanData->count() * 5; // Assuming each question has 5 answers
    //                 $totalKepuasan = $kepuasanData->count() * 5;

    //                 $sumHarapan = $harapanData->sum(function ($item) {
    //                     return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
    //                 });

    //                 $sumKepuasan = $kepuasanData->sum(function ($item) {
    //                     return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
    //                 });

    //                 // Cek untuk menghindari pembagian dengan nol
    //                 if ($totalHarapan == 0 || $totalKepuasan == 0) {
    //                     continue;
    //                 }

    //                 $meanHarapan = $sumHarapan / $totalHarapan;
    //                 $meanKepuasan = $sumKepuasan / $totalKepuasan;

    //                 $piecesPerCode[] = [
    //                     'variable_name' => $variableName,
    //                     'code_name' => $harapanData->first()->code_name,
    //                     'mean_harapan' => round($meanHarapan, 2),
    //                     'mean_kepuasan' => round($meanKepuasan, 2)
    //                 ];
    //             }
    //         }
    //     }
    //     return $piecesPerCode;
    // }
}
