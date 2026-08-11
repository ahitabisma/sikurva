@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white mb-6">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('patient.export-template') }}"
                        class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border  bg-green-500 px-4 py-2.5 font-medium text-white hover:bg-green-700 transition ease-in-out duration-300">
                        <i class="fa-solid fa-download"></i>
                        Download Template
                    </a>
                </div>
            </div>

            <form action="{{ route('patient.import-store') }}" method="POST" enctype="multipart/form-data"
                id="import-patient-form" x-data="{
                    'importPasienPoint': {{ $pointSettings->where('name', 'IMPORT-PASIEN')->value('points') ?? 0 }},

                    openConfirmationImportModal() {
                        const customMessage = `
                                                                                            <p>Tindakan import pasien akan membutuhkan <strong class='text-blue-500'>${this.importPasienPoint} poin</strong> untuk setiap data pasien yang berhasil diimport.</p>
                                                                                            <p class='mt-2'>Total poin yang dibutuhkan akan dikalkulasikan berdasarkan jumlah data yang berhasil diimport.</p>
                                                                                            <p class='mt-2 font-medium'>Apakah Anda yakin ingin melanjutkan?</p>
                                                                                        `;

                        window.dispatchEvent(
                            new CustomEvent('open-confirmation-modal', {
                                detail: {
                                    title: 'Import Pasien',
                                    points: this.importPasienPoint,
                                    callback: () => document.getElementById('import-patient-form').submit(),
                                    message: customMessage
                                }
                            })
                        );
                    }
                }">
                @csrf
                <div class="w-full xl:w-full mb-5">
                    <x-input-label for="file" :value="__('Masukkan File Import')" required />
                    <input type="file" name="file"
                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                        accept=".xls, .xlsx" required />
                    <p class="mt-1 text-xs text-gray-500">
                        Format yang didukung: XLS, XLSX.
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Batasan untuk melakukan import adalah 50 pasien. Jika lebih dari itu, silakan lakukan import secara bertahap.
                    </p>
                    <x-input-error :messages="$errors->get('file')" />
                </div>
                <div class="flex space-x-3">
                    <x-primary-button type="button" @click="openConfirmationImportModal()">Import</x-primary-button>
                    <x-cancel-button url="{{ route('patient.index') }}">Cancel</x-cancel-button>
                </div>
            </form>
        </div>
    </div>
@endsection
