@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
@endpush

@section('main')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Report
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs">
                                <li class="nav-item">
                                    <a href="#tabs-home-7" class="nav-link active"
                                        data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                        </svg>
                                        Uji Validitas</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-profile-7" class="nav-link"
                                        data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        Uji Reliable</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-activity-7" class="nav-link"
                                        data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12h4l3 8l4 -16l3 8h4" />
                                        </svg>
                                        Customer Satisfication Index</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-activity-8" class="nav-link"
                                        data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12h4l3 8l4 -16l3 8h4" />
                                        </svg>
                                        PIECES Framework</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-home-7">
                                    <h4>Uji Validitas</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Code Name</th>
                                                    <th>Correlation Coefficient</th>
                                                    <th>Validity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($correlations as $codeId => $correlation)
                                                    @php
                                                        $codeName =
                                                            $dataHarapan->firstWhere('code_id', $codeId)->code_name ??
                                                            'Unknown';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $codeName }}</td>
                                                        <td>{{ $correlation }}</td>
                                                        <td>{{ $correlation > 0.195 ? 'Valid' : 'Tidak Valid' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-profile-7">
                                    <h2>Reliability Testing</h2>
                                    <h3>Harapan</h3>
                                    <p>Cronbach's Alpha: {{ $alphaHarapan }}</p>
                                    <p>Kesimpulan: {{ $alphaHarapan > 0.6 ? 'Reliable' : 'Tidak Reliable' }}</p>

                                    <h3>Kepuasan</h3>
                                    <p>Cronbach's Alpha: {{ $alphaKepuasan }}</p>
                                    <p>Kesimpulan: {{ $alphaKepuasan > 0.6 ? 'Reliable' : 'Tidak Reliable' }}</p>
                                </div>
                                <div class="tab-pane" id="tabs-activity-7">
                                    <h2>Customer Satisfaction Index (CSI) per Variable</h2>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Variable Name</th>
                                                    <th>MIS</th>
                                                    <th>MSS</th>
                                                    <th>WF</th>
                                                    <th>WS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($csiPerVariable as $csi)
                                                    <tr>
                                                        <td>{{ $csi['variable_name'] }}</td>
                                                        <td>{{ $csi['mis'] }}</td>
                                                        <td>{{ $csi['mss'] }}</td>
                                                        <td>{{ $csi['wf'] }}</td>
                                                        <td>{{ $csi['ws'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <h3>Total CSI: {{ $totalCSI }}</h3>
                                    <br>
                                </div>
                                <div class="tab-pane" id="tabs-activity-8">
                                    <div class="mt-2">
                                        <h2>Harapan</h2>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Code Name</th>
                                                        <th>Total Sum per Code</th>
                                                        <th>JSK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($piecesHarapan['totalSumPerCodeId'] as $codeId => $totalSum)
                                                        <tr>
                                                            <td>{{ $piecesHarapan['codeNames'][$codeId] }}</td>
                                                            <td>{{ $totalSum }}</td>
                                                            <td>{{ number_format($piecesHarapan['JSK'][$codeId], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        {{-- <p>Total RK :
                                            {{ number_format($piecesKepuasan['totalJSK'], 2) }}</p> --}}
                                        <p>Rata RK (Total RK / Jumlah Code): {{ number_format($piecesHarapan['RK'], 2) }}
                                        </p>
                                    </div>
                                    <div class="mt-10">
                                        <h2>Kepuasan</h2>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Code Name</th>
                                                        <th>Total Sum per Code</th>
                                                        <th>JSK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($piecesKepuasan['totalSumPerCodeId'] as $codeId => $totalSum)
                                                        <tr>
                                                            <td>{{ $piecesKepuasan['codeNames'][$codeId] }}</td>
                                                            <td>{{ $totalSum }}</td>
                                                            <td>{{ number_format($piecesKepuasan['JSK'][$codeId], 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        {{-- <p>Total RK :
                                            {{ number_format($piecesKepuasan['totalJSK'], 2) }}</p> --}}
                                        <p>Rata RK (Total RK / Jumlah Code): {{ number_format($piecesKepuasan['RK'], 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
    @endpush
