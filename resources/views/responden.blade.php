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
                                            <th>Email</th>
                                            <th>Pekerjaan</th>
                                            <th>Instansi</th>
                                            <th>Bukti</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Action</th>
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
                        data: null,
                        name: 'sequence'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
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
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'pdfHtml5',
                        title: 'Data Responden',
                        filename: 'responden_pdf',
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Data Responden',
                        filename: 'responden_excel',
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    var info = table.page.info();
                    var pageIndex = info.page * info.length + (index + 1);
                    $('td:eq(0)', row).html(pageIndex);
                }
            });
        });
    </script>
@endpush
