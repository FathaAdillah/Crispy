@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <style>
        .custom-excel-button {
            background-color: #28a745;
            /* Bootstrap 'success' color */
            border: 1px solid #28a745;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 30px;
            /* Add margin to the right */
        }

        .custom-excel-button:hover {
            background-color: #218838;
            /* Darker shade of 'success' color */
        }
    </style>
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
                        Responden
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display nowrap" style="width:100%" id="responden-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            {{-- <th>Email</th> --}}
                                            <th>Pekerjaan</th>
                                            <th>Instansi</th>
                                            <th>Bukti</th>
                                            <th>Jenis Kelamin</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                </table>
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
        $(document).ready(function() {
            var table = $('#responden-table').DataTable({
                processing: true,
                // serverSide: true,
                ajax: '/responden',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {
                    //     data: 'email',
                    //     name: 'email'
                    // },
                    {
                        data: 'pekerjaan',
                        name: 'pekerjaan'
                    },
                    {
                        data: 'instansi',
                        name: 'instansi'
                    },
                    {
                        data: 'bukti',
                        name: 'bukti'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    }
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ],
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Data Responden',
                    filename: 'responden_excel',
                    className: 'custom-excel-button btn-success',
                }],
                // rowCallback: function(row, data, index) {
                //     var info = table.page.info();
                //     var pageIndex = info.page * info.length + (index + 1);
                //     $('td:eq(0)', row).html(pageIndex);
                // }
            });
        });
    </script>
@endpush
