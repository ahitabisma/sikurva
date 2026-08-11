<?php

namespace App\Http\Controllers\SuperAdmin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\LpHelp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HelpController extends Controller
{
    public function index()
    {
        $helps = LpHelp::all();
        return view('super-admin.landing-page.help.index', [
            'title' => 'Help Settings',
            'helps' => $helps,
        ]);
    }

    public function update(Request $request)
    {
        if ($request->has('helpItems') && is_array($request->helpItems)) {
            $updatedItems = [];
            $hasErrors = false;

            // Collect all items for validation
            $validationRules = [];
            $validationMessages = [];

            foreach ($request->helpItems as $index => $item) {
                $validationRules["helpItems.$index.title"] = 'required|string|max:255';
                $validationRules["helpItems.$index.url"] = 'required|string|max:255';

                $validationMessages["helpItems.$index.title.required"] = 'Judul menu wajib diisi.';
                $validationMessages["helpItems.$index.url.required"] = 'URL menu wajib diisi.';
                $validationMessages["helpItems.$index.title.max"] = 'Judul menu maksimal 255 karakter.';
                $validationMessages["helpItems.$index.url.max"] = 'URL menu maksimal 255 karakter.';
            }

            // Validate all items first
            $validator = Validator::make($request->all(), $validationRules, $validationMessages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $existingIds = LpHelp::pluck('id')->toArray();
                $updatedIds = [];
                $invalidUrls = [];

                foreach ($request->helpItems as $index => $item) {
                    // Validate URL format manually to provide better feedback
                    if (!filter_var($item['url'], FILTER_VALIDATE_URL)) {
                        $invalidUrls[] = $item['url'];
                        continue;
                    }

                    if (isset($item['id']) && $item['id'] > 0) {
                        LpHelp::where('id', $item['id'])->update([
                            'title' => $item['title'],
                            'url' => $item['url'],
                            'updated_at' => now(),
                        ]);

                        $updatedIds[] = $item['id'];
                    } else {
                        $newItem = LpHelp::create([
                            'title' => $item['title'],
                            'url' => $item['url'],
                        ]);

                        $updatedIds[] = $newItem->id;
                    }
                }

                // If any invalid URLs were found, roll back and report error
                if (!empty($invalidUrls)) {
                    DB::rollBack();
                    $errorMessage = 'URL tidak valid: ' . implode(', ', $invalidUrls);
                    return redirect()->back()
                        ->with('error', $errorMessage)
                        ->withInput();
                }

                $idsToDelete = array_diff($existingIds, $updatedIds);
                if (!empty($idsToDelete)) {
                    LpHelp::whereIn('id', $idsToDelete)->delete();
                }

                DB::commit();
                Cache::forget('lp_helps');

                return redirect()->route('super-admin.landing-page.help.index')
                    ->with('success', 'Menu help berhasil diperbarui.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating help menu: ' . $e->getMessage());

                return redirect()->back()
                    ->with('error', 'Gagal memperbarui menu help: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return redirect()->route('super-admin.landing-page.help.index');
    }

    public function destroy($id)
    {
        try {
            $help = LpHelp::findOrFail($id);
            $help->delete();

            // Clear cache
            Cache::forget('lp_helps');

            // Check if the request wants JSON
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Menu berhasil dihapus!'
                ]);
            }

            // Regular form submission response
            return redirect()->route('super-admin.landing-page.help.index')
                ->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Gagal menghapus menu: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('super-admin.landing-page.help.index')
                ->with('error', 'Gagal menghapus menu: ' . $e->getMessage());
        }
    }
}
