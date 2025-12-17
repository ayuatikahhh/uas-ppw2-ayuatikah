<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $data = Pegawai::with('pekerjaan')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%")
                      ->orWhere('telepon', 'like', "%{$keyword}%");
            })
            ->paginate(5);

        return view('pegawai.index', compact('data'));
    }

    public function add() {
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.add', compact('pekerjaan'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email',
            'telepon' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        try {
            $data = new Pegawai();
            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->telepon = $request->telepon;
            $data->tanggal_lahir = $request->tanggal_lahir;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->pekerjaan_id = $request->pekerjaan_id;

            $data->save();
            return redirect()->route('pegawai.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.index')->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    public function edit(Request $request) {
        $data = Pegawai::findOrFail($request->id);
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.edit', compact('data', 'pekerjaan'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email,' . $request->id,
            'telepon' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        try {
            $data = Pegawai::findOrFail($request->id);

            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->telepon = $request->telepon;
            $data->tanggal_lahir = $request->tanggal_lahir;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->pekerjaan_id = $request->pekerjaan_id;

            $data->save();
            return redirect()->route('pegawai.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.index')->with('error', 'Terjadi kesalahan saat mengubah data');
        }
    }

    public function destroy(Request $request) {
        Pegawai::findOrFail($request->id)->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data berhasil dihapus');
    }
}