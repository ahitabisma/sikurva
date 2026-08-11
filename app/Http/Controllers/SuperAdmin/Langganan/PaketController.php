<?php

namespace App\Http\Controllers\SuperAdmin\Langganan;

use App\Http\Controllers\Controller;
use App\Http\Services\PaketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketController extends Controller
{
    protected $paketService;
    public function __construct(PaketService $paketService)
    {
        $this->paketService = $paketService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil nilai pencarian dari request
        $pakets = $this->paketService->getAll(25, $search);
        return view("super-admin.langganan.paket.index")->with([
            'title' => 'Manajemen Langganan | Paket',
            'pakets' => $pakets
        ]);
    }

    public function create()
    {
        $users = DB::table('users')->select('id', 'name')->get();
        return view("super-admin.langganan..paket.create")->with([
            'title' => 'Tambah Paket',
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        // Konversi status dari string "true"/"false" ke boolean
        $request->merge(['status' => filter_var($request->status, FILTER_VALIDATE_BOOLEAN)]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'point' => ['required', 'numeric'],
            'duration' => ['required', 'numeric'],
            'duration_type' => ['required', 'in:bulan,tahun'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'array'],
            'status' => ['required', 'boolean'],
        ]);

        $data['status'] = (bool) $data['status'];
        $data['description'] = json_encode($data['description']);

        $this->paketService->create($data);

        return redirect()->route('super-admin.langganan.paket.index')->with('success', 'Paket berhasil ditambahkan');
    }

    public function edit($id)
    {
        $paket = $this->paketService->getById($id);
        return view("super-admin.langganan.paket.edit")->with([
            'title' => 'Edit Paket',
            'paket' => $paket
        ]);
    }

    public function update(Request $request, $id)
    {
        // Konversi status dari string "true"/"false" ke boolean
        $request->merge(['status' => filter_var($request->status, FILTER_VALIDATE_BOOLEAN)]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'point' => ['required', 'numeric'],
            'duration' => ['required', 'numeric'],
            'duration_type' => ['required', 'in:bulan,tahun'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'array'],
            'status' => ['required', 'boolean'],
        ]);

        $data['status'] = (bool) $data['status'];
        $data['description'] = json_encode($data['description']);

        $paket = $this->paketService->getById($id);

        if (!$paket) {
            return redirect()->route('super-admin.langganan.paket.index')->with('error', 'Paket tidak ditemukan');
        }

        $paket->update($data);

        return redirect()->route('super-admin.langganan.paket.index')->with('success', 'Paket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $isDeleted = $this->paketService->delete($id);

        if (!$isDeleted) {
            return redirect()->route('super-admin.langganan.paket.index')->with('error', 'Terjadi kesalahan saat menghapus paket!');
        }

        return redirect()->route('super-admin.langganan.paket.index')->with('success', 'Paket berhasil dihapus!');
    }
}
