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
                            Add Question
                        </a>
                    </div>
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
    <!-- Add Kuisioner Modal -->
    <div class="modal modal-blur fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Kuisioner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-kuisioner-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="question">Question</label>
                            <input type="text" class="form-control" name="question" placeholder="Your question" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="variable_id">Variable</label>
                            <select class="form-control" name="variable_id" required>
                                <option value="" selected disabled>Pilih Variable</option>
                                @foreach ($variables as $variable)
                                    <option value="{{ $variable->id }}">{{ $variable->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Create new Kuisioner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Kuisioner Modal -->
    <div class="modal modal-blur fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kuisioner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-kuisioner-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label class="form-label" for="edit-question">Question</label>
                            <input type="text" class="form-control" id="edit-question" name="question"
                                placeholder="Your question" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-variable_id">Variable</label>
                            <select class="form-control" id="edit-variable_id" name="variable_id" required>
                                <option value="" selected disabled>Pilih Variable</option>
                                @foreach ($variables as $variable)
                                    <option value="{{ $variable->id }}">{{ $variable->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-auto">Update Kuisioner</button>
                        </div>
                    </form>
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

            // Create Kuisioner
            $('#create-kuisioner-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('pertanyaan.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-add').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(response) {
                        alert('Error creating questionnaire');
                    }
                });
            });

            // Edit Kuisioner
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.get('/pertanyaan/' + id, function(data) {
                    $('#edit-id').val(data.id);
                    $('#edit-question').val(data.question);
                    $('#edit-variable_id').val(data.variable_id);
                    $('#modal-edit').modal('show');
                });
            });

            $('#edit-kuisioner-form').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit-id').val();
                $.ajax({
                    url: '/pertanyaan/' + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modal-edit').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(response) {
                        alert('Error updating questionnaire');
                    }
                });
            });

            // Delete Kuisioner
            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this questionnaire?')) {
                    $.ajax({
                        url: '/pertanyaan/' + id,
                        method: 'DELETE',
                        success: function(response) {
                            table.ajax.reload();
                        },
                        error: function(response) {
                            alert('Error deleting questionnaire');
                        }
                    });
                }
            });
        });
    </script>
@endpush
