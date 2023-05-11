@extends('app')
@section('content')
    <div class="row mx-3 my-3">
        <div class="col-md-12">
            <form action="{{ route('barang.update', $barang->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang <span style="color: red;">*</span></label>
                            <input type="text" autocomplete="off" readonly
                                class="form-control @error('kode_barang') is-invalid @enderror" required id="kode_barang"
                                name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}">
                            @error('kode_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang <span style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" required
                                id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}">
                            @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_beli">Harga Beli <span style="color: red;">*</span></label>
                            <input type="text" data-type='currency'
                                class="form-control @error('harga_beli') is-invalid @enderror" autocomplete="off" required
                                id="harga_beli" name="harga_beli"
                                value="{{ old('harga_beli', number_format($barang->harga_beli, 2, '.', ',')) }}">
                            @error('harga_beli')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual <span style="color: red;">*</span></label>
                            <input type="text" data-type='currency'
                                class="form-control @error('harga_jual') is-invalid @enderror" autocomplete="off" required
                                id="harga_jual" name="harga_jual"
                                value="{{ old('harga_jual', number_format($barang->harga_jual, 2, '.', ',')) }}">
                            @error('harga_jual')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="satuan">Satuan <span style="color: red;">*</span></label>
                            <input type="text" required class="form-control @error('satuan') is-invalid @enderror"
                                id="satuan" name="satuan" value="{{ old('satuan', $barang->satuan) }}">
                            @error('satuan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ms_kategori_id">Kategori <span style="color: red;">*</span></label>
                            <select name="ms_kategori_id" id="ms_kategori_id" required
                                class="form-control @error('ms_kategori_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($kategori as $val)
                                    <option value="{{ $val->id }}" @selected($val->id == $barang->ms_kategori_id)>
                                        {{ $val->kategori_barang }}</option>
                                @endforeach
                            </select>
                            @error('ms_kategori_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stok_barang">Stok </label>
                            <input type="number" class="form-control @error('stok_barang') is-invalid @enderror"
                                id="stok_barang" name="stok_barang" value="{{ old('stok_barang', $barang->stok_barang) }}">
                            @error('stok_barang')
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
        });
    </script>
@endsection
