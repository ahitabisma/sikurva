<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\LpLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = DB::table('lp_layanans')->get()->toArray();
        return view('super-admin.landing-page.layanan.index')->with([
            'title' => 'Layanan Settings',
            'layanans' => $layanans
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'layanans' => 'required|array',
            'layanans.*.id' => 'nullable|integer',
            'layanans.*.icon_file' => 'nullable|image|max:2048',
            'layanans.*.image' => 'nullable|string',
            'layanans.*.title' => 'required|string|max:255',
            'layanans.*.description' => 'required|string|max:500',
        ], [
            'layanans.*.icon_file.image' => 'The service icon must be an image.',
            'layanans.*.icon_file.max' => 'The service icon must not be larger than 2 MB.',
            'layanans.*.title.required' => 'The title field is required.',
            'layanans.*.description.required' => 'The description field is required.',
        ]);

        foreach ($request->input('layanans') as $index => $service) {
            $serviceData = [
                'title' => $service['title'],
                'description' => $service['description'],
            ];

            // Handle file upload
            if ($request->hasFile("layanans.$index.icon_file")) {
                $file = $request->file("layanans.$index.icon_file");
                $fileName = time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/landing-page/layanan'), $fileName);
                $serviceData['image'] = '/img/landing-page/layanan/' . $fileName;
            } else {
                // Use existing icon if no new file uploaded
                $serviceData['image'] = $service['image'] ?? '';
            }

            // Update or create
            if (!empty($service['id'])) {
                LpLayanan::where('id', $service['id'])->update($serviceData);
            } else {
                LpLayanan::create($serviceData);
            }
        }

        // Clear cache after update
        Cache::forget('lp_layanans');

        return redirect()->back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $layanans = LpLayanan::find($id);
        if (!$layanans) {
            return response()->json(['message' => 'Terjadi kesalahan layanan tidak ditemukan'], 404);
        }

        // Ensure minimum of 1 layanan remains (optional, remove if not needed)
        if (LpLayanan::count() <= 1) {
            return response()->json(['message' => 'Tidak bisa menghapus layanan karena minimal layanan berjumlah 1'], 403);
        }

        if ($layanans->image && File::exists(public_path($layanans->image)) && $layanans->image !== '/placeholder.svg') {
            File::delete(public_path($layanans->image));
        }

        $layanans->delete();

        // Clear cache after delete
        Cache::forget('lp_layanans');

        return response()->json(['message' => 'Layanan berhasil dihapus!']);
    }
}
