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
                        Variable
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
                            Add Variable
                        </a>
                    </div>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="hover" id="variable-table">
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
    <!-- Add Variable Modal -->
    <div class="modal modal-blur fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Variable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-variable-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Your variable name">
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Create new Variable</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Variable Modal -->
    <div class="modal modal-blur fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Variable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-variable-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name"
                                placeholder="Your variable name">
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Update Variable</button>
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
    <script>
        $(document).ready(function() {
            // Open create modal
            $('#create-variable-form').on('submit', function(e) {
                e.preventDefault();
                let name = $('input[name="name"]').val();
                $.ajax({
                    url: "{{ route('variable.store') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        alert('Error creating variable');
                    }
                });
            });

            // Open edit modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `variable/${id}`,
                    method: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-name').val(data.name);
                        $('#modal-edit').modal('show');
                    },
                    error: function(response) {
                        alert('Error fetching variable details');
                    }
                });
            });

            // Update variable
            $('#edit-variable-form').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit-id').val();
                let name = $('#edit-name').val();
                $.ajax({
                    url: `variable/${id}`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        alert('Error updating variable');
                    }
                });
            });

            // Delete variable
            $(document).on('click', '.btn-delete', function() {
                if (confirm('Are you sure you want to delete this variable?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: `variable/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(response) {
                            alert('Error deleting variable');
                        }
                    });
                }
            });
        });
    </script>
@endpush
