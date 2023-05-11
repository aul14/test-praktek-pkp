@extends('app')
@section('content')
    <div class="row mx-3 my-3">
        <div class="col-md-6">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="kategori_barang">Kategori <span style="color: red;">*</span></label>
                    <input type="text" autocomplete="off"
                        class="form-control @error('kategori_barang') is-invalid @enderror" required id="kategori_barang"
                        name="kategori_barang" value="{{ old('kategori_barang', $kategori->kategori_barang) }}">
                    @error('kategori_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <a href="{{ route('kategori.index') }}" class="btn btn-danger btn-back">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
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
