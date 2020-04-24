<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;

class transaksicontroller extends Controller
{
    public function tambah(Request $req){
            $validator=Validator::make($req->all(),
        [
            'id_barang'=>'required',
            'id_member'=>'required',
            'id_admin'=>'required',
            'nama_barang' => 'required',
            'tanggal_pengembalian'=>'required',
        ]
    );
    if($validator->fails()){
        return Response()->json($validator->errors());
    }
    $simpan= Transaksi::create([
            'id_barang'=>$req->id_barang,
            'id_member'=>$req->id_member,
            'id_admin'=>$req->id_admin,
            'nama_barang'=>$req->nama_barang,
            'tanggal_pengembalian'=>$req->tanggal_pengembalian,
        
    ]);
    if($simpan){
        return response ()->json(['status'=>'berhasil tambah data']);
    }
    else{
        return response ()->json(['status'=>'gagal']);
    }
}
    
    public function update($id,Request $req){
        $validator=Validator::make($req->all(),
        [
            'id_barang'=>'required',
            'id_member'=>'required',
            'id_admin'=>'required',
            'nama_barang' => 'required',
            'tanggal_pengembalian'=>'required',
        ]
    );
    if($validator->fails()){
        return Response()->json($validator->errors());
    }
    $ubah=Transaksi::where('id',$id)->update([
            'id_barang'=>$req->id_barang,
            'id_member'=>$req->id_member,
            'id_admin'=>$req->id_admin,
            'nama_barang'=>$req->nama_barang,
            'tanggal_pengembalian'=>$req->tanggal_pengembalian,
    ]);
    if($ubah){
        return Response()->json(['status'=>'berhasil update data']);
    }
    else{
     return Response()->json(['status'=>'gagal deh :(']);
    }
    
    }

    public function destroy($id){
        $hapus=Transaksi::where('id',$id)->delete();
        if($hapus){
            return Response()->json(['status'=>'berhasil']);
        }
        else{
            return Response()->json(['status'=>'gagal']);
        }
    }

    public function show(Request $req){
        $transaksi = DB::table('transaksi')->join('barang', 'barang.id', '=', 'transaksi.id_barang')
        ->where('transaksi.nama_barang', '==', $req->nama_barang)
        ->select('tanggal_ditemukan', 'kategori', 'jumlah', 'foto', 'deskripsi')
        ->get();

        if($transaksi->count() > 0){
            $data_transaksi = array();
            foreach($transaksi as $t){

                $data_transaksi[] = array(
                    'Nama Barang' => $t->nama_barang,
                    'Tanggal' => $t->tanggal_ditemukan,
                    'Kategori' => $t->kategori,
                    'Foto' => $t->foto,
                );
            }
            return response()->json(compact('data_transaksi'));
        } else {
            $pesan = "Barang telah kembali";
            return response()->json(compact('pesan'));
        }
    }
}
