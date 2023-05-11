<?php

namespace App\Http\Controllers;

use App\Models\MsKategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kategori = MsKategori::select('*');
            return DataTables::of($kategori)
                ->addColumn('action', function ($kategori) {
                    return view('_action', [
                        'row_id' => $kategori->id,
                        'edit_url' => route('kategori.edit', $kategori->id),
                        'delete_url' => route('kategori.destroy', $kategori->id),
                    ]);
                })
                ->editColumn('updated_at', function ($kategori) {
                    return !empty($kategori->updated_at) ? date("d-m-Y H:i", strtotime($kategori->updated_at)) : "-";
                })
                ->rawColumns(['updated_at', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori.create');
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
            'kategori_barang'  => 'required|max:100',
        ]);

        $kategori = new MsKategori();
        $kategori->kategori_barang = $request->kategori_barang;
        $kategori->save();
        return to_route('kategori.index')->with('success', 'Kategori berhasil di Tambah!');
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
    public function edit(MsKategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
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
            'kategori_barang'  => 'required|max:100',
        ]);

        $kategori = MsKategori::find($id);
        $kategori->kategori_barang = $request->kategori_barang;
        $kategori->update();
        return to_route('kategori.index')->with('success', 'Kategori berhasil di Edit!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MsKategori $kategori)
    {
        $kategori->delete();
        return to_route('kategori.index')->with('success', 'Kategori berhasil di Hapus!');
    }
}
