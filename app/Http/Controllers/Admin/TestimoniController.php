<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\TestimoniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniController extends Controller
{
    protected $testimoniService;

    public function __construct(TestimoniService $testimoniService)
    {
        $this->testimoniService = $testimoniService;
    }

    public function index()
    {
        $testimoni = $this->testimoniService->getByUserId(Auth::user()->id);

        return view('admin.testimoni.index')->with([
            'title' => 'Testimoni',
            'testimoni' => $testimoni
        ]);
    }

    public function create()
    {
        return view('admin.testimoni.create')->with([
            'title' => 'Tambah Testimoni'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'testimoni' => ['required', 'string'],
        ]);

        // Check if Exists
        $exists = $this->testimoniService->exists(Auth::user()->id);

        if ($exists)
            return back()->with('error', 'Testimoni untuk user sudah ada.');

        $this->testimoniService->create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'testimoni' => $request->testimoni
        ]);

        return redirect()->route('testimoni.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit()
    {
        $testimoni = $this->testimoniService->getByUserId(Auth::user()->id);

        if (!$testimoni)
            return back()->with('error', 'Anda belum menambahkan testimoni');

        return view('admin.testimoni.edit')->with([
            'title' => 'Edit Testimoni',
            'testimoni' => $testimoni
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'testimoni' => ['required', 'string']
        ]);

        // Ambil testimoni berdasarkan UserID
        $testimoni = $this->testimoniService->getByUserId(Auth::user()->id);

        if (!$testimoni) {
            return redirect()->route('testimoni.index')->with('error', 'Testimoni tidak ditemukan.');
        }

        // Perbarui testimoni
        $testimoni->update([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'testimoni' => $request->testimoni
        ]);

        return redirect()->route('testimoni.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy()
    {
        $testimoni = $this->testimoniService->getByUserId(Auth::user()->id);
        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
