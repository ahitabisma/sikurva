<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\TestimoniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimoniController extends Controller
{
    protected $testimoniService;

    public function __construct(TestimoniService $testimoniService)
    {
        $this->testimoniService = $testimoniService;
    }

    public function searchUsers(Request $request)
    {
        $search = $request->query('q');

        $users = DB::table('users')
            ->select('id', 'name')
            ->where('name', 'like', '%' . $search . '%')
            ->limit(25) // Batasi hasil agar tidak terlalu banyak
            ->get();

        return response()->json($users);
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil nilai pencarian dari request
        $testimonis = $this->testimoniService->getAllPaginated(25, $search); // Kirim pencarian ke service

        return view("super-admin.testimoni.index")->with([
            'title' => 'Manajemen Testimoni',
            'testimonis' => $testimonis,
            'search' => $search // Kirim nilai pencarian ke view agar tetap ada di input
        ]);
    }

    public function create()
    {
        $users = DB::table('users')->select('id', 'name')->get();
        return view("super-admin.testimoni.create")->with([
            'title' => 'Tambah Testimoni',
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user' => ['required', 'exists:users,id'],
            'testimoni' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5']
        ]);

        // Check if Exists
        $exists = $this->testimoniService->exists($request->user);

        if ($exists)
            return back()->with('error', 'Testimoni untuk user sudah ada.');

        $this->testimoniService->create([
            'user_id' => $request->user,
            'rating' => $request->rating,
            'testimoni' => $request->testimoni
        ]);

        return redirect()->route('super-admin.testimoni.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $testimoni = $this->testimoniService->getById($id);
        return view("super-admin.testimoni.edit")->with([
            'title' => 'Edit Testimoni',
            'testimoni' => $testimoni
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user' => ['required', 'exists:users,id'],
            'testimoni' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5']
        ]);

        // Ambil testimoni berdasarkan ID
        $testimoni = $this->testimoniService->getById($id);

        if (!$testimoni) {
            return redirect()->route('super-admin.testimoni.index')->with('error', 'Testimoni tidak ditemukan.');
        }

        // Perbarui testimoni
        $testimoni->update([
            'user_id' => $request->user,
            'rating' => $request->rating,
            'testimoni' => $request->testimoni
        ]);

        return redirect()->route('super-admin.testimoni.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $isDeleted = $this->testimoniService->delete($id);

        if (!$isDeleted) {
            return redirect()->route('super-admin.testimoni.index')->with('error', 'Terjadi kesalahan saat menghapus testimoni!');
        }

        return redirect()->route('super-admin.testimoni.index')->with('success', 'Testimoni berhasil dihapus!');
    }
}
