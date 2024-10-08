<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MathPHP\Statistics\Descriptive;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


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

        $scoresHarapan = $this->calculateScoresV($dataHarapan);
        $scoresKepuasan = $this->calculateScoresV($dataKepuasan);
        $correlations = $this->calculateCorrelation($scoresHarapan, $scoresKepuasan);

        // Pisahkan hasil korelasi untuk Harapan dan Kepuasan
        $correlationsHarapan = $correlations['correlationsHarapan'];
        $correlationsKepuasan = $correlations['correlationsKepuasan'];


        // Menghitung Cronbach's Alpha untuk jawaban harapan dan kepuasan
        $scoresHarapanR = $this->calculateScoresR($dataHarapan);
        $scoresKepuasanR = $this->calculateScoresR($dataKepuasan);

        // Menghitung Cronbach's Alpha
        $alphaHarapan = $this->calculateCronbachAlphaR($scoresHarapanR);
        $alphaKepuasan = $this->calculateCronbachAlphaR($scoresKepuasanR);

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
            // 'correlations' => $correlations,
            'correlationsHarapan' => $correlationsHarapan,
            'correlationsKepuasan' => $correlationsKepuasan,
            'alphaHarapan' => $alphaHarapan,
            'alphaKepuasan' => $alphaKepuasan,
            'csiPerVariable' => $csiPerVariable,
            'totalCSI' => round($totalCSI, 2),
            'piecesHarapan' => $piecesHarapan,
            'piecesKepuasan' => $piecesKepuasan,
        ]);
    }


    // Function Validitas
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
    // public function calculateScoresV($data)
    // {
    //     $scores = [];
    //     foreach ($data as $item) {
    //         $respondentId = $item->responden_id;
    //         $codeId = $item->code_id;
    //         $score = $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;

    //         if (!isset($scores[$respondentId])) {
    //             $scores[$respondentId] = [];
    //         }
    //         if (!isset($scores[$respondentId][$codeId])) {
    //             $scores[$respondentId][$codeId] = 0;
    //         }

    //         $scores[$respondentId][$codeId] += $score;`
    //     }
    //     return $scores;
    // }

    public function calculateYTotal($scores) {
        $YTotal = array();
        $numRows = count($scores);
        $numCols = isset($scores[0]) ? count($scores[0]) : 25;

        // Sum across columns for each row
        for ($row = 1; $row <= $numRows; $row++) { // Start from 0
            $sum = 0;
            for ($col = 1; $col <= $numCols; $col++) { // Start from 0
                if (isset($scores[$row][$col])) { // Check if index exists
                    $sum += $scores[$row][$col];
                }
            }
            $YTotal[] = $sum;
        }
        return $YTotal;
    }

    public function transformScoresArray($scores) {
        $numRows = count($scores);
        $numCols = isset($scores[0]) ? count($scores[0]) : 25;

        // Initialize a new array to hold the combined results
        $combinedArray = array();

        // Loop through each column
        for ($col = 1; $col <= $numCols; $col++) {
            // Create an array to store values for this column
            $columnValues = array();

            // Loop through each row to get the values for this column
            for ($row = 0; $row <= $numRows; $row++) {
                if (isset($scores[$row][$col])) {
                    // Append the value to the columnValues array
                    $columnValues[] = $scores[$row][$col];
                }
            }

            // Append the columnValues array to the combinedArray
            $combinedArray[] = $columnValues;
        }

        return $combinedArray;
    }

    public function pearsonCorrelation($x, $y) {
        $n = count($x);

        if ($n != count($y)) {
            return ("Arrays must have the same length.");
        }

        // Calculate means
        $meanX = array_sum($x) / $n;
        $meanY = array_sum($y) / $n;

        // Calculate covariance and standard deviations
        $covXY = 0;
        $varX = 0;
        $varY = 0;

        for ($i = 0; $i < $n; $i++) {
            $covXY += ($x[$i] - $meanX) * ($y[$i] - $meanY);
            $varX += pow($x[$i] - $meanX, 2);
            $varY += pow($y[$i] - $meanY, 2);
        }

        $covXY /= $n;
        $varX = sqrt($varX / $n);
        $varY = sqrt($varY / $n);

        // Calculate Pearson correlation coefficient
        $correlation = $covXY / ($varX * $varY);

        return $correlation;
    }

    public function calculateCorrelation($scoresHarapan, $scoresKepuasan)
    {
        $computeCorrelations = function($scores) {
            $XTransformed = $this->transformScoresArray($scores);
            $YTotal = $this->calculateYTotal($scores);
            return array_combine(
                range(1, count($XTransformed)),
                array_map(
                    fn($x) => $this->pearsonCorrelation($x, $YTotal),
                    $XTransformed
                )
            );
        };

        // Compute correlations for both Harapan and Kepuasan
        $correlationsHarapan = $computeCorrelations->bindTo($this, $this)($scoresHarapan);
        $correlationsKepuasan = $computeCorrelations->bindTo($this, $this)($scoresKepuasan);

        return [
            'correlationsHarapan' => $correlationsHarapan,
            'correlationsKepuasan' => $correlationsKepuasan
        ];
    }

    // public function calculateCorrelation($scoresHarapan, $scoresKepuasan)
    // {

    //     $XTransformed_Harapan = $this->transformScoresArray($scoresHarapan);
    //     $YTotal_Harapan = $this->calculateYTotal($scoresHarapan);
    //     $correlationsHarapan = array();

    //     for ($i = 0; $i < count($XTransformed_Harapan); $i++) {
    //         $correlationsHarapan[$i + 1] = $this->pearsonCorrelation($XTransformed_Harapan[$i], $YTotal_Harapan);
    //     }
    //     $XTransformed_Kepuasan = $this->transformScoresArray($scoresKepuasan);
    //     $YTotal_Kepuasan = $this->calculateYTotal($scoresKepuasan);
    //     $correlationsKepuasan = array();

    //     for ($i = 0; $i < count($XTransformed_Kepuasan); $i++) {
    //         $correlationsKepuasan[$i + 1] = $this->pearsonCorrelation($XTransformed_Kepuasan[$i], $YTotal_Kepuasan);
    //     }
    //     return  [
    //         'correlationsHarapan' => $correlationsHarapan,
    //         'correlationsKepuasan' => $correlationsKepuasan
    //     ];
    // }

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



    // private function hitungCSI($dataHarapan, $dataKepuasan)
    // {
    //     $csiPerVariable = [];

    //     // Group data by variable
    //     $groupedHarapan = $dataHarapan->groupBy('variable_id');
    //     $groupedKepuasan = $dataKepuasan->groupBy('variable_id');

    //     foreach ($groupedHarapan as $variableId => $harapanData) {
    //         if (isset($groupedKepuasan[$variableId])) {
    //             $kepuasanData = $groupedKepuasan[$variableId];

    //             // Sum the Harapan (expectation) scores
    //             $sumHarapan = $harapanData->sum(function ($item) {
    //                 return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
    //             });

    //             // Sum the Kepuasan (satisfaction) scores
    //             $sumKepuasan = $kepuasanData->sum(function ($item) {
    //                 return $item->jawaban1 + $item->jawaban2 + $item->jawaban3 + $item->jawaban4 + $item->jawaban5;
    //             });

    //             // Avoid division by zero and calculate CSI
    //             if ($sumHarapan > 0) {
    //                 $csi = round(($sumKepuasan / $sumHarapan) * 100, 2);

    //                 $csiPerVariable[] = [
    //                     'variable_id' => $variableId,
    //                     'variable_name' => $harapanData->first()->variable_name,
    //                     'csi' => $csi
    //                 ];
    //             }
    //         }
    //     }

    //     return $csiPerVariable;
    // }

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
                    'csi' => round($meanKepuasan * 100, 2),
                    'wf' => round($wf, 2),
                    'ws' => round($ws, 2),
                    'msi' => $csi
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
    public function exportExcel()
    {
        // Retrieve all unique responden IDs from both Harapan and Kepuasan
        $respondenIds = DB::table('aanswer_harapan')
            ->select('responden_id')
            ->union(
                DB::table('aanswer_kepuasan')->select('responden_id')
            )
            ->distinct()
            ->pluck('responden_id')
            ->toArray();

        // Retrieve and group the data from Harapan by code_name
        $dataHarapan = DB::table('aanswer_harapan as ah')
            ->select('ac.name as code_name', 'ah.responden_id', 'ah.jawaban1', 'ah.jawaban2', 'ah.jawaban3', 'ah.jawaban4', 'ah.jawaban5')
            ->join('aquestion as q', 'ah.question_id', '=', 'q.id')
            ->join('acode as ac', 'q.code_id', '=', 'ac.id')
            ->get()
            ->groupBy('code_name');

        // Retrieve and group the data from Kepuasan by code_name
        $dataKepuasan = DB::table('aanswer_kepuasan as ak')
            ->select('ac.name as code_name', 'ak.responden_id', 'ak.jawaban1', 'ak.jawaban2', 'ak.jawaban3', 'ak.jawaban4', 'ak.jawaban5')
            ->join('aquestion as q', 'ak.question_id', '=', 'q.id')
            ->join('acode as ac', 'q.code_id', '=', 'ac.id')
            ->get()
            ->groupBy('code_name');

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Create Harapan sheet
        $harapanSheet = $spreadsheet->createSheet();
        $harapanSheet->setTitle('Harapan');
        $harapanSheet->setCellValue('A1', 'Code Name');

        // Create Kepuasan sheet
        $kepuasanSheet = $spreadsheet->createSheet();
        $kepuasanSheet->setTitle('Kepuasan');
        $kepuasanSheet->setCellValue('A1', 'Code Name');

        // Define column ranges to support up to 100 respondents
        $columns = [];
        foreach (range('B', 'Z') as $column) {
            $columns[] = $column;
        }
        foreach (range('A', 'Z') as $firstLetter) {
            foreach (range('A', 'Z') as $secondLetter) {
                $columns[] = $firstLetter . $secondLetter;
            }
        }

        // Set Responden IDs in header row for both sheets
        foreach ($respondenIds as $index => $respondenId) {
            if (isset($columns[$index])) {
                $harapanSheet->setCellValue($columns[$index] . '1', $respondenId);
                $kepuasanSheet->setCellValue($columns[$index] . '1', $respondenId);
            }
        }

        // Populate Harapan sheet with data
        $row = 2;
        foreach ($dataHarapan as $codeName => $responses) {
            $harapanSheet->setCellValue('A' . $row, $codeName);
            foreach ($responses as $response) {
                $score = max($response->jawaban1, $response->jawaban2, $response->jawaban3, $response->jawaban4, $response->jawaban5);
                if ($score > 0) {
                    $columnIndex = array_search($response->responden_id, $respondenIds);
                    if (isset($columns[$columnIndex])) {
                        $harapanSheet->setCellValue($columns[$columnIndex] . $row, $score);
                    }
                }
            }
            $row++;
        }

        // Populate Kepuasan sheet with data
        $row = 2;
        foreach ($dataKepuasan as $codeName => $responses) {
            $kepuasanSheet->setCellValue('A' . $row, $codeName);
            foreach ($responses as $response) {
                $score = max($response->jawaban1, $response->jawaban2, $response->jawaban3, $response->jawaban4, $response->jawaban5);
                if ($score > 0) {
                    $columnIndex = array_search($response->responden_id, $respondenIds);
                    if (isset($columns[$columnIndex])) {
                        $kepuasanSheet->setCellValue($columns[$columnIndex] . $row, $score);
                    }
                }
            }
            $row++;
        }

        // Set the active sheet to the first one
        $spreadsheet->setActiveSheetIndex(0);

        // Set the headers for the response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="report.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the spreadsheet to the output stream
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
