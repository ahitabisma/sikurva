<?php

namespace App\Http\Controllers\SuperAdmin\Interpretasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view("super-admin.interpretasi.setting.index")->with([
            'title' => 'Manajemen Setting Interpretasi'
        ]);
    }
    public function create()
    {
        return view("super-admin.interpretasi.setting.create")->with([
            'title' => 'Tambah Setting Interpretasi'
        ]);
    }
    public function edit()
    {
        return view("super-admin.interpretasi.setting.edit")->with([
            'title' => 'Edit Setting Interpretasi',
        ]);
    }
}
