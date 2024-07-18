@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
@endpush

@section('main')

    <!-- Page title actions -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Pertanyaan
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="hover" id="kuisioner-table">
                                    <thead>
                                        <th>No</th>
                                        <th>Pertanyaan</th>
                                        <th>Variable</th>
                                        <th>Action</th>
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
            var table = $('#kuisioner-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/pertanyaan',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'question',
                        name: 'question'
                    },
                    {
                        data: 'variable',
                        name: 'variable'
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
