@extends('layouts.app')
@section('titlepage', 'Pelanggaran')

@section('content')
@section('navigasi')
    <span>Pelanggaran</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('pelanggaran.create')
                    <a href="#" class="btn btn-primary" id="btnCreatePelanggaran">
                        <i class="ti ti-plus me-2"></i>Tambah Pelanggaran
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('pelanggaran.index') }}">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="nik" id="nik_search" class="form-select select2Nik">
                                            <option value="">Semua Karyawan</option>
                                            @foreach ($karyawans as $karyawan)
                                                <option value="{{ $karyawan->nik }}" {{ Request('nik') == $karyawan->nik ? 'selected' : '' }}>
                                                    {{ $karyawan->nik_show ?? $karyawan->nik }} - {{ $karyawan->nama_karyawan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-calendar"></i></span>
                                            <input type="text" class="form-control flatpickr-date" id="dari_search" name="dari"
                                                placeholder="Dari" value="{{ Request('dari') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-calendar"></i></span>
                                            <input type="text" class="form-control flatpickr-date" id="sampai_search" name="sampai"
                                                placeholder="Sampai" value="{{ Request('sampai') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>No Dokumen</th>
                                        <th>Karyawan</th>
                                        <th>Tanggal</th>
                                        <th>Dari</th>
                                        <th>Sampai</th>
                                        <th>Jenis SP</th>
                                        

                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pelanggaran as $index => $item)
                                        <tr>
                                            <td>{{ $pelanggaran->firstItem() + $index }}</td>
                                            <td><strong>{{ $item->no_dokumen }}</strong></td>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->nama_karyawan ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $item->nik_show ?? $item->nik }}</small><br>
                                                    <small class="text-muted">{{ $item->nama_jabatan }} - {{ $item->nama_dept }}</small>
                                                </div>
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->dari)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->sampai)) }}</td>
                                            <td><span class="badge bg-warning">{{ $item->jenis_sp }}</span></td>
                                            
                                            
                                            <td>
                                                <div class="d-flex">
                                                    @can('pelanggaran.index')
                                                        <div>
                                                            <a href="{{ route('pelanggaran.print', Crypt::encrypt($item->no_sp)) }}" class="me-2" target="_blank">
                                                                <i class="ti ti-printer text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('pelanggaran.index')
                                                        <div>
                                                            <a href="{{ route('pelanggaran.show', Crypt::encrypt($item->no_sp)) }}" class="me-2">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('pelanggaran.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editPelanggaran" no_sp="{{ Crypt::encrypt($item->no_sp) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('pelanggaran.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform me-1"
                                                                action="{{ route('pelanggaran.delete', Crypt::encrypt($item->no_sp)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                                    <p class="mt-2">Tidak ada data pelanggaran</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $pelanggaran->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreatePelanggaran" size="" show="loadCreatePelanggaran" title="Tambah Pelanggaran" />
<x-modal-form id="mdlEditPelanggaran" size="" show="loadEditPelanggaran" title="Edit Pelanggaran" />
@endsection

@push('myscript')
<script>
    $(function() {
        // Initialize select2 for karyawan
        const select2Nik = $(".select2Nik");
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Initialize flatpickr for date inputs
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $('.delete-confirm').click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data pelanggaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $("#btnCreatePelanggaran").click(function(e) {
            e.preventDefault();
            $('#mdlCreatePelanggaran').modal("show");
            $("#loadCreatePelanggaran").load('{{ route("pelanggaran.create") }}');
        });

        $(".editPelanggaran").click(function(e) {
            e.preventDefault();
            var no_sp = $(this).attr("no_sp");
            $('#mdlEditPelanggaran').modal("show");
            $("#loadEditPelanggaran").load('/pelanggaran/' + no_sp + '/edit');
        });
    });
</script>
@endpush

