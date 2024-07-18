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
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Answer ID</th>
                                                    <th>Question Text</th>
                                                    <th>Variable Name</th>
                                                    <th>Category Name</th>
                                                    <th>Jawaban 1</th>
                                                    <th>Jawaban 2</th>
                                                    <th>Jawaban 3</th>
                                                    <th>Jawaban 4</th>
                                                    <th>Jawaban 5</th>
                                                    <th>Responden ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($allData as $data)
                                                    <tr>
                                                        <td>{{ $data->answer_id }}</td>
                                                        <td>{{ $data->question_text }}</td>
                                                        <td>{{ $data->variable_name }}</td>
                                                        <td>{{ $data->category_name }}</td>
                                                        <td>{{ $data->jawaban1 }}</td>
                                                        <td>{{ $data->jawaban2 }}</td>
                                                        <td>{{ $data->jawaban3 }}</td>
                                                        <td>{{ $data->jawaban4 }}</td>
                                                        <td>{{ $data->jawaban5 }}</td>
                                                        <td>{{ $data->responden_id }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <h2>Koefisien Korelasi Pearson</h2>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Variable</th>
                                                    <th>Pearson Correlation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($correlation as $key => $value)
                                                    <tr>
                                                        <td>{{ str_replace('_', ' ', ucfirst($key)) }}</td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-profile-7">
                                    <h2>Data Harapan</h2>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Answer ID</th>
                                                    <th>Question ID</th>
                                                    <th>Question Text</th>
                                                    <th>Variable ID</th>
                                                    <th>Variable Name</th>
                                                    <th>Category ID</th>
                                                    <th>Category Name</th>
                                                    <th>Jawaban1</th>
                                                    <th>Jawaban2</th>
                                                    <th>Jawaban3</th>
                                                    <th>Jawaban4</th>
                                                    <th>Jawaban5</th>
                                                    <th>Responden ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataHarapan as $data)
                                                    <tr>
                                                        <td>{{ $data->answer_id }}</td>
                                                        <td>{{ $data->question_id }}</td>
                                                        <td>{{ $data->question_text }}</td>
                                                        <td>{{ $data->variable_id }}</td>
                                                        <td>{{ $data->variable_name }}</td>
                                                        <td>{{ $data->category_id }}</td>
                                                        <td>{{ $data->category_name }}</td>
                                                        <td>{{ $data->jawaban1 }}</td>
                                                        <td>{{ $data->jawaban2 }}</td>
                                                        <td>{{ $data->jawaban3 }}</td>
                                                        <td>{{ $data->jawaban4 }}</td>
                                                        <td>{{ $data->jawaban5 }}</td>
                                                        <td>{{ $data->responden_id }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <h4>Cronbach's Alpha Harapan: {{ $alphaHarapan }}</h4>
                                        <h2>Data Kepuasan</h2>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Answer ID</th>
                                                        <th>Question ID</th>
                                                        <th>Question Text</th>
                                                        <th>Variable ID</th>
                                                        <th>Variable Name</th>
                                                        <th>Category ID</th>
                                                        <th>Category Name</th>
                                                        <th>Jawaban1</th>
                                                        <th>Jawaban2</th>
                                                        <th>Jawaban3</th>
                                                        <th>Jawaban4</th>
                                                        <th>Jawaban5</th>
                                                        <th>Responden ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dataKepuasan as $data)
                                                        <tr>
                                                            <td>{{ $data->answer_id }}</td>
                                                            <td>{{ $data->question_id }}</td>
                                                            <td>{{ $data->question_text }}</td>
                                                            <td>{{ $data->variable_id }}</td>
                                                            <td>{{ $data->variable_name }}</td>
                                                            <td>{{ $data->category_id }}</td>
                                                            <td>{{ $data->category_name }}</td>
                                                            <td>{{ $data->jawaban1 }}</td>
                                                            <td>{{ $data->jawaban2 }}</td>
                                                            <td>{{ $data->jawaban3 }}</td>
                                                            <td>{{ $data->jawaban4 }}</td>
                                                            <td>{{ $data->jawaban5 }}</td>
                                                            <td>{{ $data->responden_id }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <h4>Cronbach's Alpha Kepuasan: {{ $alphaKepuasan }}</h4>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-activity-7">
                                    <h4>Activity tab</h4>
                                    <div>Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet,
                                        facilisi
                                        sit mauris accumsan nibh habitant senectus</div>
                                </div>
                                <div class="tab-pane" id="tabs-activity-8">
                                    <h4>Activity tab</h4>
                                    <div>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                        Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book.
                                    </div>
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
    <script type="text/javascript">
        $(function() {
            var table = $('#variable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/variable',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
