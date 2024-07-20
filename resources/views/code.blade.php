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
                        code
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-success d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modal-add">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add code
                        </a>
                    </div>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="hover" id="code-table">
                                    <thead>
                                        <th>No</th>
                                        <th>Name</th>
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
    <!-- Add code Modal -->
    <div class="modal modal-blur fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-code-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Your code name">
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Create new code</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit code Modal -->
    <div class="modal modal-blur fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-code-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name"
                                placeholder="Your code name">
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Update code</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#code-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/code',
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
    <script>
        $(document).ready(function() {
            // Open create modal
            $('#create-code-form').on('submit', function(e) {
                e.preventDefault();
                let name = $('input[name="name"]').val();
                $.ajax({
                    url: "{{ route('code.store') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        alert('Error creating code');
                    }
                });
            });

            // Open edit modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `code/${id}`,
                    method: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-name').val(data.name);
                        $('#modal-edit').modal('show');
                    },
                    error: function(response) {
                        alert('Error fetching code details');
                    }
                });
            });

            // Update code
            $('#edit-code-form').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit-id').val();
                let name = $('#edit-name').val();
                $.ajax({
                    url: `code/${id}`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        alert('Error updating code');
                    }
                });
            });

            // Delete code
            $(document).on('click', '.btn-delete', function() {
                if (confirm('Are you sure you want to delete this code?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: `code/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(response) {
                            alert('Error deleting code');
                        }
                    });
                }
            });
        });
    </script>
@endpush
