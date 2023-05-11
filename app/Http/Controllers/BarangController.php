<?php

namespace App\Http\Controllers;

use App\Models\MsBarang;
use App\Models\MsKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $barang = MsBarang::with('ms_kategori')->select('*');
            return DataTables::of($barang)
                ->addColumn('action', function ($barang) {
                    return view('_action', [
                        'row_id' => $barang->id,
                        'edit_url' => route('barang.edit', $barang->id),
                        'delete_url' => route('barang.destroy', $barang->id),
                    ]);
                })
                ->editColumn('updated_at', function ($barang) {
                    return !empty($barang->updated_at) ? date("d-m-Y H:i", strtotime($barang->updated_at)) : "-";
                })
                ->editColumn('harga_jual', function ($barang) {
                    return !empty($barang->harga_jual) ? number_format($barang->harga_jual, 2, '.', ',') : "-";
                })
                ->editColumn('harga_beli', function ($barang) {
                    return !empty($barang->harga_beli) ? number_format($barang->harga_beli, 2, '.', ',') : "-";
                })
                ->rawColumns(['updated_at', 'action', 'harga_beli', 'harga_jual'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('barang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = MsKategori::all();
        return view('barang.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:ms_barang,kode_barang',
            'nama_barang'  => 'required',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'satuan'  => 'required',
            'ms_kategori_id'  => 'required',
        ]);

        $kode = $this->kode_barang();

        $brg = new MsBarang();
        $brg->kode_barang = $kode;
        $brg->nama_barang = $request->nama_barang;
        $brg->harga_beli = !empty($request->harga_beli) ? str_replace(",", "", $request->harga_beli) : null;
        $brg->harga_jual = !empty($request->harga_jual) ? str_replace(",", "", $request->harga_jual) : null;
        $brg->satuan = $request->satuan;
        $brg->ms_kategori_id = $request->ms_kategori_id;
        $brg->stok_barang = !empty($request->stok_barang) ? $request->stok_barang : 0;
        $brg->stok_barang_old = !empty($request->stok_barang) ? $request->stok_barang : 0;
        $brg->save();

        return to_route('barang.index')->with('success', 'Barang berhasil di Tambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MsBarang $barang)
    {
        $kategori = MsKategori::all();
        return view('barang.edit', compact('kategori', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:ms_barang,kode_barang,' . $id,
            'nama_barang'  => 'required',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'satuan'  => 'required',
            'ms_kategori_id'  => 'required',
        ]);


        $brg = MsBarang::find($id);
        $brg->nama_barang = $request->nama_barang;
        $brg->harga_beli = !empty($request->harga_beli) ? str_replace(",", "", $request->harga_beli) : null;
        $brg->harga_jual = !empty($request->harga_jual) ? str_replace(",", "", $request->harga_jual) : null;
        $brg->satuan = $request->satuan;
        $brg->ms_kategori_id = $request->ms_kategori_id;
        $brg->stok_barang = !empty($request->stok_barang) ? $request->stok_barang : 0;
        $brg->stok_barang_old = !empty($request->stok_barang) ? $request->stok_barang : 0;
        $brg->update();

        return to_route('barang.index')->with('success', 'Barang berhasil di Edit!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MsBarang $barang)
    {
        $barang->delete();
        return to_route('barang.index')->with('success', 'Barang berhasil di Hapus!');
    }

    public function kode_barang()
    {
        $thn = date('y');
        $bln = date('m');
        $format =  "BRG-$thn$bln";

        $query = MsBarang::select('kode_barang')
            ->where("kode_barang", 'like', "%{$format}%")->count() + 1;

        if (strlen($query) <= 1) {
            $format .= "00" . $query;
        } else if (strlen($query) <= 2) {
            $format .= "0" . $query;
        } else {
            $format .= (string)$query;
        }

        $form = $format;

        return $form;
    }
}
