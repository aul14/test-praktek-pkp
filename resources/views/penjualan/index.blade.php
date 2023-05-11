@extends('app')
@section('content')
    <div class="row mx-3 my-3">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    {{-- <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button> --}}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    {{-- <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button> --}}
                </div>
            @endif

            <a href="{{ route('penjualan.create') }}" type="button" class="btn btn-sm btn-primary">Add</a>
        </div>
    </div>
    <div class="row mx-3">
        <div class="col-md-12">
            <div class="table-responsive p-0">
                <table id="myTable" class="table w-100">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>No</th>
                            <th class="select-filter">No Faktur</th>
                            <th class="select-filter">Tanggal Faktur</th>
                            <th class="select-filter">Konsumen</th>
                            <th class="select-filter">Kode Barang</th>
                            <th class="select-filter">Nama Barang</th>
                            <th class="select-filter">Jumlah</th>
                            <th class="select-filter">harga_satuan</th>
                            <th class="select-filter">Total</th>
                            <th class="select-filter">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>No Faktur</th>
                            <th>Tanggal Faktur</th>
                            <th>Konsumen</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>harga_satuan</th>
                            <th>Total</th>
                            <th>Updated At</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                pagingType: 'full_numbers',
                scrollY: "50vh",
                scrollCollapse: true,
                scrollX: true,
                ajax: '{{ route('penjualan.index') }}',
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_faktur',
                        name: 'no_faktur'
                    },
                    {
                        data: 'tgl_faktur',
                        name: 'tgl_faktur'
                    },
                    {
                        data: 'nama_konsumen',
                        name: 'nama_konsumen'
                    },
                    {
                        data: 'ms_barang.kode_barang',
                        name: 'ms_barang.kode_barang'
                    },
                    {
                        data: 'ms_barang.nama_barang',
                        name: 'ms_barang.nama_barang'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'harga_satuan',
                        name: 'harga_satuan'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ],
                columnDefs: [{
                    defaultContent: "-",
                    targets: "_all"
                }],
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    this.api()
                        .columns('.select-filter')
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select style="width: 100%;"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append('<option value="' + d + '">' + d +
                                        '</option>');
                                });
                        });
                },
            });
        });
    </script>
@endsection
