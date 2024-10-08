@extends('layouts.depan')

@section('title', 'Kuisioner')

@push('style')
    <style>
        .dropzone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .dropzone .dz-message {
            font-weight: 500;
            color: #6c757d;
        }

        .form-section {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .table thead th {
            vertical-align: middle;
            text-align: center;
        }

        .form-check-inline {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table thead th[colspan] {
            text-align: center;
        }

        .variable-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .variable-row {
            background-color: #e9ecef;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .form-check-label {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            z-index: 1000;
        }

        .scroll-to-top button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 10px 15px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .scroll-to-top button:hover {
            background-color: #0056b3;
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
                        Kuisioner
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 mt-5">
                    <div class="card card-md">
                        <div class="col-lg-3 m-5">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="logo row-start" width="252"
                                height="81" viewBox="0 0 252 81" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M37.044 0C16.637 0 .093 16.544.093 36.91a36.71 36.71 0 0 0 7.032 21.667 35.327 35.327 0 0 1 4.919-1.911c-4.309-5.406-6.87-12.276-6.87-19.715 0-17.52 14.268-31.788 31.788-31.788S68.75 19.43 68.75 36.95c0 11.22-5.853 21.138-14.715 26.788.854 0 1.708 0 2.602-.04 2.886-.163 5.61-1.464 7.927-2.52 5.853-6.546 9.43-14.757 9.43-24.269C73.994 16.545 57.45 0 37.044 0z"
                                    fill="#EA5F00"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M60.457 73.66c-2.073.691-4.105 1.097-6.056 1.138-8.252.325-14.187-2.154-19.878-4.593-5.082-2.155-9.838-4.187-15.691-3.862-5.529.447-9.39 3.537-9.553 3.658-1.138.895-1.3 2.561-.406 3.7.894 1.138 2.601 1.341 3.74.447.04 0 2.804-2.195 6.625-2.48 4.472-.203 8.578 1.463 13.252 3.496 5.325 2.276 11.26 4.797 19.187 5.04.976.041 1.951 0 2.968-.04 2.48-.081 5.04-.57 7.601-1.423a2.7 2.7 0 0 0 1.667-3.374c-.529-1.463-2.073-2.195-3.456-1.707z"
                                    fill="#2673DD"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M68.953 61.745c-2.52 1.748-7.601 4.715-13.414 4.919-7.398.325-12.236-1.83-17.398-4.025-5.407-2.357-11.016-4.796-19.431-4.43-7.805.284-14.43 4.756-17.683 7.357a2.725 2.725 0 0 0-.406 3.862 2.725 2.725 0 0 0 3.862.406c2.723-2.195 8.21-5.934 14.43-6.178 7.195-.285 11.951 1.788 16.992 3.983 4.96 2.155 10.04 4.39 17.358 4.512.813 0 1.626 0 2.48-.04 7.194-.285 13.292-3.821 16.34-5.895 1.26-.853 1.545-2.56.691-3.82-.853-1.22-2.601-1.545-3.82-.65z"
                                    fill="#2673DD"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M39.116 59.919l.081.04c3.212 1.301 6.301 2.561 9.919 3.252 10.04-4.593 17.032-14.715 17.032-26.463.04-16.016-13.048-29.064-29.064-29.064-16.017 0-29.147 13.048-29.147 29.064a28.862 28.862 0 0 0 7.196 19.106c.894-.163 1.788-.244 2.723-.325 9.268-.529 15.65 2.073 21.26 4.39zm2.073-19.593a6.084 6.084 0 0 0-1.83-1.26c-.73-.367-1.544-.651-2.397-.936a23.644 23.644 0 0 1-2.44-.935c-1.707-.65-3.13-1.626-4.308-3.008-1.18-1.341-1.708-3.048-1.708-5.121.041-1.342.326-2.52.813-3.537.529-1.016 1.22-1.87 2.114-2.52.895-.65 1.87-1.18 2.968-1.504a12.314 12.314 0 0 1 3.495-.488h.082c1.87.04 3.536.406 4.918 1.057a24.14 24.14 0 0 1 3.903 2.439l-2.886 4.146c-.813-.61-1.708-1.098-2.602-1.504-.935-.407-2.073-.65-3.414-.732-1.017-.081-1.952.122-2.724.61-.813.447-1.22 1.138-1.22 2.032 0 .529.122 1.017.407 1.423.285.407.691.773 1.179 1.057.488.325 1.016.529 1.544.773.529.203 1.098.406 1.586.61 1.057.365 2.114.812 3.17 1.34a13.933 13.933 0 0 1 2.846 1.911c.854.732 1.504 1.626 2.032 2.642.488 1.017.732 2.196.692 3.578-.041 1.341-.326 2.56-.813 3.577-.529 1.057-1.26 1.91-2.155 2.642-.935.732-1.992 1.26-3.211 1.626-1.22.366-2.561.529-3.984.488-1.341-.04-2.56-.203-3.658-.57-1.098-.365-2.114-.772-3.009-1.26a14.737 14.737 0 0 1-2.276-1.503c-.65-.448-1.097-.854-1.382-1.18l2.968-4.186a12.258 12.258 0 0 0 3.455 2.317c1.341.65 2.723.975 4.146.975 1.22.041 2.236-.244 3.13-.772.894-.528 1.3-1.341 1.342-2.358 0-.772-.244-1.382-.773-1.87zM102.367 36.623c-.854-.772-1.789-1.382-2.846-1.951-1.056-.528-2.113-1.016-3.21-1.382-.53-.203-1.058-.407-1.627-.61a7.167 7.167 0 0 1-1.585-.772c-.488-.285-.854-.65-1.18-1.057a2.464 2.464 0 0 1-.446-1.423c.04-.894.447-1.585 1.22-2.032.812-.447 1.707-.65 2.763-.57 1.342.082 2.52.326 3.456.732.935.448 1.829.935 2.642 1.545l2.927-4.146c-1.22-.976-2.561-1.789-3.943-2.48-1.382-.691-3.05-1.016-4.919-1.057h-.081c-1.22-.04-2.399.122-3.537.488s-2.114.854-3.008 1.504c-.894.65-1.585 1.504-2.114 2.52-.528 1.017-.813 2.236-.853 3.578-.041 2.073.528 3.78 1.707 5.121a10.754 10.754 0 0 0 4.309 3.008c.772.326 1.585.65 2.48.895.853.284 1.666.61 2.398.975.731.366 1.341.773 1.83 1.301.487.529.771 1.138.73 1.91-.04 1.017-.487 1.83-1.34 2.358-.854.529-1.911.813-3.13.773a10.865 10.865 0 0 1-4.188-.976c-1.341-.65-2.52-1.423-3.496-2.317l-3.008 4.187c.285.285.773.732 1.383 1.26a14.73 14.73 0 0 0 2.276 1.504c.894.488 1.91.895 3.008 1.26 1.138.366 2.358.529 3.7.57 1.422.04 2.763-.122 3.983-.488 1.22-.366 2.317-.895 3.252-1.626a7.798 7.798 0 0 0 2.195-2.683c.528-1.057.813-2.277.854-3.618.04-1.382-.204-2.602-.732-3.618-.366-1.057-1.016-1.951-1.87-2.683zM176.065 35.529c.244-.244.529-.57.773-.854.284-.325.528-.69.731-1.179.244-.487.407-1.016.57-1.666.122-.65.203-1.423.203-2.358a7.42 7.42 0 0 0-.488-2.642 7.457 7.457 0 0 0-1.382-2.317c-.61-.691-1.301-1.301-2.155-1.789a7.315 7.315 0 0 0-2.886-.935c-.691-.081-1.463-.162-2.276-.162-.854-.041-1.707-.041-2.683-.041l-7.886.04v29.756h10.894c1.016 0 1.789 0 2.439-.04 1.098-.122 2.114-.366 3.09-.773.934-.406 1.829-.975 2.52-1.707.732-.732 1.301-1.626 1.748-2.602a9.134 9.134 0 0 0 .65-3.414c0-1.992-.406-3.577-1.179-4.756-.772-1.22-1.666-2.073-2.683-2.561zm-12.52-8.821h2.561c.854 0 1.626 0 2.277.04.65.041 1.26.082 1.707.122 1.016.122 1.829.448 2.439.976.65.488.975 1.22.935 2.114.04 1.138-.325 1.992-.935 2.48-.651.487-1.464.813-2.48.934-.488.082-1.057.122-1.707.122h-4.837v-6.788h.04zm10.244 18.13c-.732.69-1.911 1.097-3.537 1.179-.487.04-1.097.08-1.91.04h-4.837v-7.683h2.479c.773 0 1.504 0 2.195-.04.691-.04 1.301-.04 1.789 0 1.585.081 2.764.447 3.618 1.097.853.65 1.301 1.545 1.301 2.724.04 1.097-.326 1.992-1.098 2.683zM129.4 35.366c-.569-1.544-1.382-2.886-2.439-3.943-1.016-1.097-2.236-1.91-3.618-2.48a11.215 11.215 0 0 0-4.35-.853c-1.869 0-3.536.325-4.959.894-1.463.61-2.683 1.463-3.658 2.52-.976 1.057-1.708 2.277-2.236 3.659-.488 1.382-.732 2.886-.732 4.471 0 1.748.325 3.334.935 4.797a11.73 11.73 0 0 0 2.52 3.7 11.66 11.66 0 0 0 3.659 2.398c1.382.528 2.845.813 4.39.813 1.423 0 2.683-.122 3.74-.366a15.832 15.832 0 0 0 2.845-.813 14.572 14.572 0 0 0 2.236-1.26c.651-.447 1.26-.895 1.87-1.342l-2.642-3.699c-.366.285-.732.57-1.179.894-.488.325-1.098.61-1.748.895-.65.243-1.382.447-2.236.61-.813.121-1.748.203-2.764.162a14.398 14.398 0 0 1-1.707-.244 6.38 6.38 0 0 1-1.708-.732 8.842 8.842 0 0 1-1.463-1.3c-.447-.529-.772-1.18-.976-1.952l-.04-.203h16.951l.041-1.3c.081-2.033-.163-3.781-.732-5.326zm-16.423 2.155l.041-.163c.081-.65.284-.976.488-1.423l.122-.244a5.314 5.314 0 0 1 1.341-1.504 4.855 4.855 0 0 1 1.789-.853c.691-.204 1.382-.326 2.073-.326 1.626.041 2.967.488 3.943 1.383.488.406.894.813 1.219 1.26.326.447.529.975.61 1.666l.041.204h-11.667zM149.44 30.366l-.325-.284c-.691-.61-1.586-1.098-2.602-1.423-.976-.366-2.154-.57-3.496-.57-1.504 0-2.927.285-4.227.855a9.379 9.379 0 0 0-3.374 2.357c-.976 1.016-1.708 2.277-2.277 3.74-.528 1.423-.813 3.049-.813 4.837 0 1.586.285 3.09.813 4.472.569 1.382 1.301 2.56 2.277 3.618a12.07 12.07 0 0 0 3.374 2.48c1.3.568 2.682.853 4.227.853 1.342 0 2.52-.163 3.496-.529 1.016-.365 1.911-.894 2.602-1.544l.325-.325v2.357h5.244V28.74h-5.244v1.626zm-.488 12.195c-.325.773-.772 1.464-1.301 1.992-.528.529-1.138.894-1.829 1.179a6.484 6.484 0 0 1-2.154.366c-1.667 0-3.049-.57-4.187-1.667-1.139-1.179-1.667-2.723-1.667-4.634 0-1.87.569-3.415 1.667-4.634 1.138-1.22 2.52-1.83 4.187-1.83.772 0 1.504.163 2.154.448.732.284 1.341.69 1.829 1.26.529.528.976 1.22 1.301 2.073.325.813.488 1.707.488 2.764 0 .976-.163 1.87-.488 2.683zM198.586 30.366l-.325-.284c-.691-.61-1.585-1.098-2.602-1.423-.975-.366-2.154-.57-3.495-.57-1.504 0-2.927.285-4.228.855a9.379 9.379 0 0 0-3.374 2.357c-.976 1.016-1.707 2.277-2.276 3.74-.529 1.423-.813 3.049-.813 4.837 0 1.586.284 3.09.813 4.472.569 1.382 1.3 2.56 2.276 3.618a12.085 12.085 0 0 0 3.374 2.48c1.301.568 2.683.853 4.228.853 1.341 0 2.52-.163 3.495-.529 1.017-.365 1.911-.894 2.602-1.544l.325-.325v2.357h5.244V28.862h-5.244v1.504zm-.488 12.195c-.325.773-.772 1.464-1.3 1.992-.529.529-1.139.894-1.83 1.179a6.48 6.48 0 0 1-2.154.366c-1.667 0-3.049-.57-4.187-1.667-1.138-1.179-1.667-2.723-1.667-4.634 0-1.87.569-3.415 1.667-4.634 1.138-1.22 2.52-1.83 4.187-1.83.772 0 1.463.163 2.154.448.732.284 1.342.69 1.83 1.26.528.528.975 1.22 1.3 2.073.326.813.488 1.707.488 2.764 0 .976-.162 1.87-.488 2.683zM242.122 38.579l9.553-10.447h-6.911l-7.886 8.577V21.262h-5.284v30.08h5.284v-6.828l1.748-1.952 6.829 8.78H252l-9.878-12.763zM226.878 33.334c-.488-1.382-1.422-2.601-2.764-3.617-1.341-1.017-3.049-1.545-5.081-1.545-1.057 0-2.155.162-3.252.488a7.852 7.852 0 0 0-2.642 1.382v-1.18h-5.326v22.48h5.326V39.31c.04-.813.122-1.585.203-2.317.122-.772.325-1.423.65-1.992s.813-1.016 1.423-1.382c.61-.366 1.382-.57 2.317-.61 1.179-.04 2.114.285 2.846.895.731.65 1.219 1.463 1.463 2.52.122.488.203 1.138.285 1.951.04.772.081 1.91.081 3.415v9.552h5.244V40.49a51.56 51.56 0 0 0-.122-3.821 13.473 13.473 0 0 0-.651-3.334z"
                                    fill="#EA5F00"></path>
                            </svg>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <h3 class="h1">Selamat Datang Responden!!</h3>
                                    <div class="markdown text-secondary">
                                        Terimakasih sudah mengunjungi website ini, website ini digunakan untuk mengetahui
                                        tingkat kepuasan pengguna aplikasi SeaBank menggunakan Pieces Framework dan Customer
                                        Satisfaction Index
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-5">
                    <div class="col-12 mt-5">
                        <div class="card card-md">
                            <div class="card-header">
                                <h1>Data Responden Kuisioner</h1>
                            </div>
                            <div class="card-body">
                                <form action="{{ Route('submit-kuisioner') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-section">
                                        <h2>Nama <span class="text-danger">*</span></h2>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-section">
                                        <h2>Email <span class="text-danger">*</span></h2>
                                        <div class="mb-3">
                                            <input type="email" class="form-control" id="email" name="email"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-section">
                                        <h2>Pekerjaan <span class="text-danger">*</span></h2>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="mahasiswa"
                                                    name="pekerjaan" value="mahasiswa" required>
                                                <label class="form-check-label" for="mahasiswa">Mahasiswa</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="wiraswasta"
                                                    name="pekerjaan" value="wiraswasta" required>
                                                <label class="form-check-label" for="wiraswasta">Wiraswasta</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="lain-lain"
                                                    name="pekerjaan" value="lain-lain" required>
                                                <label class="form-check-label" for="lain-lain">Lain-Lain:</label>
                                                <input type="text" class="form-control mt-2" id="pekerjaanLain"
                                                    name="pekerjaanLain">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-section">
                                        <h2>Instansi <span class="text-danger">*</span></h2>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="instansi" name="instansi"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-section">
                                        <h2>Jenis Kelamin <span class="text-danger">*</span></h2>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="laki-laki"
                                                    name="jenisKelamin" value="laki-laki" required>
                                                <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="perempuan"
                                                    name="jenisKelamin" value="perempuan" required>
                                                <label class="form-check-label" for="perempuan">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-section">
                                        <h2>Upload Bukti Pengguna Seabank</h2>
                                        <div class="mb-3">
                                            <div class="dropzone" id="uploadBukti">
                                                <div class="dz-message">
                                                    Drop files here or click to upload.
                                                </div>
                                            </div>

                                            <input type="hidden" id="buktiPath" name="bukti">
                                        </div>
                                    </div>


                                    <div class="form-section mt-5">
                                        <h2>Kuisioner <span class="text-danger">*</span></h2>
                                        <div class="container">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Pertanyaan</th>
                                                        <th colspan="5">Penilaian Responden</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($questions as $variable_id => $questionsGroup)
                                                        <tr class="variable-header">
                                                            <th colspan="2">
                                                                {{ $questionsGroup->first()->variable->name ?? 'Unknown Variable' }}
                                                            </th>
                                                            <th>STS/STP</th>
                                                            <th>TS/TP</th>
                                                            <th>N</th>
                                                            <th>S/P</th>
                                                            <th>SS/SP</th>
                                                        </tr>
                                                        @foreach ($questionsGroup as $index => $question)
                                                            <tr class="variable-row">
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $question->question }}</td>
                                                                @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                                                                    <td>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="q{{ $question->id }}"
                                                                                id="q{{ $question->id }}_{{ strtolower($option) }}"
                                                                                value="{{ $option }}" required>
                                                                            <label class="form-check-label"
                                                                                for="q{{ $question->id }}_{{ strtolower($option) }}"></label>
                                                                        </div>
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-section mt-5">
                                        <button type="submit" class="btn btn-primary">Kirim Kuisioner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-to-top" id="scrollToTopBtn">
        <button>&uarr;</button>
    </div>
@endsection


@push('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pekerjaanRadios = document.querySelectorAll('input[name="pekerjaan"]');
            const pekerjaanLainInput = document.getElementById('pekerjaanLain');

            pekerjaanRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'lain-lain') {
                        pekerjaanLainInput.disabled = false;
                    } else {
                        pekerjaanLainInput.disabled = true;
                        pekerjaanLainInput.value = '';
                    }
                });
            });

            if (document.getElementById('lain-lain').checked) {
                pekerjaanLainInput.disabled = false;
            } else {
                pekerjaanLainInput.disabled = true;
            }
        });
    </script>
    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#uploadBukti", {
            url: "/file/upload",
            paramName: "file",
            maxFilesize: 2, // MB
            acceptedFiles: ".jpeg,.jpg,.png,.pdf",
            success: function(file, response) {
                document.getElementById('buktiPath').value = response.path;
            }
        });
    </script>

    <script>
        // Menampilkan tombol saat scroll ke bawah
        window.addEventListener('scroll', function() {
            var scrollToTopBtn = document.getElementById('scrollToTopBtn');
            if (window.scrollY > 200) { // Menampilkan tombol setelah scroll lebih dari 200px
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });

        // Fungsi untuk kembali ke atas halaman
        document.getElementById('scrollToTopBtn').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
@endpush
