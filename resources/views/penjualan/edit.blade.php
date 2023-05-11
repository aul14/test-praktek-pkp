@extends('app')
@section('content')
    <div class="row mx-3 my-3">
        <div class="col-md-12">
            <form action="{{ route('penjualan.update', $pj->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_faktur">No Faktur <span style="color: red;">*</span></label>
                            <input type="text" autocomplete="off" readonly
                                class="form-control @error('no_faktur') is-invalid @enderror" required id="no_faktur"
                                name="no_faktur" value="{{ old('no_faktur', $pj->no_faktur) }}">
                            @error('no_faktur')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_faktur">Tanggal Faktur <span style="color: red;">*</span></label>
                            <input type="text" autocomplete="off"
                                class="form-control @error('tgl_faktur') is-invalid @enderror date-picker" required
                                id="tgl_faktur" name="tgl_faktur"
                                value="{{ old('tgl_faktur', !empty($pj->tgl_faktur) ? date('d/m/Y', strtotime($pj->tgl_faktur)) : '') }}">
                            @error('tgl_faktur')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_konsumen">Konsumen <span style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('nama_konsumen') is-invalid @enderror" required
                                id="nama_konsumen" name="nama_konsumen"
                                value="{{ old('nama_konsumen', $pj->nama_konsumen) }}">
                            @error('nama_konsumen')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ms_barang_id">Barang <span style="color: red;">*</span></label>
                            <select name="ms_barang_id" id="ms_barang_id" required
                                class="form-control @error('ms_barang_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($brg as $val)
                                    <option value="{{ $val->id }}" @selected($val->id == $pj->ms_barang_id)
                                        data-harga="{{ $val->harga_jual }}">
                                        {{ $val->kode_barang . ' - ' . $val->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ms_barang_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jumlah">Jumlah <span style="color: red;">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                autocomplete="off" required id="jumlah" name="jumlah"
                                value="{{ old('jumlah', $pj->jumlah) }}">
                            @error('jumlah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan </label>
                            <input type="text" class="form-control @error('harga_satuan') is-invalid @enderror" readonly
                                id="harga_satuan" name="harga_satuan"
                                value="{{ old('harga_satuan', number_format($pj->harga_satuan, 2, '.', ',')) }}">
                            @error('harga_satuan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="total">Total </label>
                            <input type="text" class="form-control @error('total') is-invalid @enderror" readonly
                                id="total" name="total"
                                value="{{ old('total', number_format($pj->total, 2, '.', ',')) }}">
                            @error('total')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('barang.index') }}" class="btn btn-danger btn-back">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("input[name=jumlah]").keyup(function(e) {
                e.preventDefault();
                let val = $(this).val();
                let harga = $("select[name=ms_barang_id]").find(":selected").data('harga');
                let total = harga * val;

                $("input[name=harga_satuan]").val(numberFormatter(harga));
                $("input[name=total]").val(numberFormatter(total));
            });

            $("select[name=ms_barang_id]").change(function(e) {
                e.preventDefault();
                let val = $("input[name=jumlah]").val();
                let harga = $(this).find(":selected").data('harga');
                let total = harga * val;

                $("input[name=harga_satuan]").val(numberFormatter(harga));
                $("input[name=total]").val(numberFormatter(total));
            });
        });

        function numberFormatter(num) {
            if (!isNaN(num)) {
                var wholeAndDecimal = String(num.toFixed(2)).split(".");
                var reversedWholeNumber = Array.from(wholeAndDecimal[0]).reverse();
                var formattedOutput = [];

                reversedWholeNumber.forEach((digit, index) => {
                    formattedOutput.push(digit);
                    if ((index + 1) % 3 === 0 && index < reversedWholeNumber.length - 1) {
                        formattedOutput.push(",");
                    }
                })

                formattedOutput = formattedOutput.reverse().join('') + "." + wholeAndDecimal[1];

                return formattedOutput;
            }

        }
    </script>
@endsection
