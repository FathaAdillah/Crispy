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
                       Isi Kuisoner Responden
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="hover" id="responden-table">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Isi Kuisioner</th>
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
        $(function() {
            var table = $('#responden-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/jawaban',
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
