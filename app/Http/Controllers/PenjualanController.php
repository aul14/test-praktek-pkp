<?php

namespace App\Http\Controllers;

use App\Models\MsBarang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penjualan = Penjualan::with('ms_barang')->select('*');
            return DataTables::of($penjualan)
                ->addColumn('action', function ($penjualan) {
                    return view('_action', [
                        'row_id' => $penjualan->id,
                        'edit_url' => route('penjualan.edit', $penjualan->id),
                        'delete_url' => route('penjualan.destroy', $penjualan->id),
                    ]);
                })
                ->editColumn('updated_at', function ($penjualan) {
                    return !empty($penjualan->updated_at) ? date("d-m-Y H:i", strtotime($penjualan->updated_at)) : "-";
                })
                ->editColumn('harga_satuan', function ($penjualan) {
                    return !empty($penjualan->harga_satuan) ? number_format($penjualan->harga_satuan, 2, '.', ',') : "-";
                })
                ->editColumn('total', function ($penjualan) {
                    return !empty($penjualan->total) ? number_format($penjualan->total, 2, '.', ',') : "-";
                })
                ->rawColumns(['updated_at', 'action', 'total', 'harga_satuan'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('penjualan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brg = MsBarang::all();
        return view('penjualan.create', compact('brg'));
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
            'no_faktur'  => 'required|unique:penjualan,no_faktur',
            'tgl_faktur'  => 'required',
            'nama_konsumen'  => 'required',
            'ms_barang_id'  => 'required',
            'jumlah'  => 'required',
        ]);


        DB::beginTransaction();
        try {
            $nf = $this->no_faktur();

            $pj = new Penjualan();
            $pj->no_faktur =  $nf;
            $pj->tgl_faktur = !empty($request->tgl_faktur) ?  date('Y-m-d', strtotime(str_replace('/', '-', $request->tgl_faktur))) : null;
            $pj->nama_konsumen = $request->nama_konsumen;
            $pj->ms_barang_id = $request->ms_barang_id;
            $pj->jumlah = $request->jumlah;
            $pj->harga_satuan = !empty($request->harga_satuan) ? str_replace(",", "", $request->harga_satuan) : null;
            $pj->total = !empty($request->total) ? str_replace(",", "", $request->total) : null;
            $pj->save();

            $brg = MsBarang::where('id', $request->ms_barang_id)->first();
            $brg->stok_barang = $brg->stok_barang - $request->jumlah;
            $brg->update();

            DB::commit();
            return to_route('penjualan.index')->with('success', 'Penjualan berhasil di Tambah!');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
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
    public function edit(Penjualan $penjualan)
    {
        $brg = MsBarang::all();
        $pj = $penjualan;
        return view('penjualan.edit', compact('brg', 'pj'));
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
            'no_faktur'  => 'required|unique:penjualan,no_faktur,' . $id,
            'tgl_faktur'  => 'required',
            'nama_konsumen'  => 'required',
            'ms_barang_id'  => 'required',
            'jumlah'  => 'required',
        ]);

        DB::beginTransaction();
        try {

            $pj = Penjualan::find($id);
            $pj->tgl_faktur = !empty($request->tgl_faktur) ?  date('Y-m-d', strtotime(str_replace('/', '-', $request->tgl_faktur))) : null;
            $pj->nama_konsumen = $request->nama_konsumen;
            $pj->ms_barang_id = $request->ms_barang_id;
            $pj->jumlah = $request->jumlah;
            $pj->harga_satuan = !empty($request->harga_satuan) ? str_replace(",", "", $request->harga_satuan) : null;
            $pj->total = !empty($request->total) ? str_replace(",", "", $request->total) : null;
            $pj->save();

            if ($pj->jumlah != $request->jumlah) {
                $brg = MsBarang::where('id', $request->ms_barang_id)->first();
                $brg->stok_barang = $brg->stok_barang - $request->jumlah;
                $brg->update();
            } else {
                $brg = MsBarang::where('id', $request->ms_barang_id)->first();
                $brg->stok_barang = $brg->stok_barang_old - $request->jumlah;
                $brg->update();
            }

            DB::commit();
            return to_route('penjualan.index')->with('success', 'Penjualan berhasil di Edit!');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $pj = Penjualan::find($id);

            $brg = MsBarang::where('id', $pj->ms_barang_id)->first();
            $brg->stok_barang = $brg->stok_barang_old;
            $brg->update();

            $pj->delete();


            DB::commit();
            return to_route('penjualan.index')->with('success', 'Penjualan berhasil di Hapus!');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function no_faktur()
    {
        $thn = date('y');
        $bln = date('m');
        $format =  "INV-$thn$bln";

        $query = Penjualan::select('no_faktur')
            ->where("no_faktur", 'like', "%{$format}%")->count() + 1;

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
