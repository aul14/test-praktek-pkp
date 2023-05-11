<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">Home</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
            aria-expanded="false">Master</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('kategori.index') }}">Kategori</a>
            <a class="dropdown-item" href="{{ route('barang.index') }}">Barang</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('penjualan.index') }}">Penjualan</a>
    </li>
</ul>
