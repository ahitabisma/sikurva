@extends('layouts.tailadmin')

@section('content')
    <div x-data="skPpSettings" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- SECTION 1: SYARAT DAN KETENTUAN -->
        <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
            <form action="{{ route('super-admin.landing-page.sk-pp.update-sk') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Header dengan Tombol -->
                <div class="pb-5 mb-5 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Syarat dan Ketentuan</h1>
                    <div>
                        <button type="submit"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                            Simpan Syarat dan Ketentuan
                        </button>
                    </div>
                </div>

                <!-- SK Upload & Preview Section -->
                <div>
                    <label for="sk-file" class="mb-2 text-sm font-medium text-gray-700">
                        Document PDF
                    </label>
                    <div class="mt-2">
                        <input type="file" name="sk_file" id="sk-file" accept="application/pdf"
                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                    </div>

                    {{-- Notes --}}
                    <div class="mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal ukuran file 10MB, format document: PDF.
                        </p>
                    </div>

                    <!-- Current File Info -->
                    <div class="mt-4" x-show="skFileName">
                        <p class="text-sm font-medium text-gray-700 mb-2">File saat ini:</p>
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span x-text="skFileName"></span>
                            <a :href="skFile" target="_blank"
                                class="ml-2 text-blue-500 hover:text-blue-700 underline">
                                Lihat PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECTION 2: PRIVACY POLICY -->
        <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
            <form action="{{ route('super-admin.landing-page.sk-pp.update-pp') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Header dengan Tombol -->
                <div class="pb-5 mb-5 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Privacy Policy</h1>
                    <div>
                        <button type="submit"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                            Simpan Privacy Policy
                        </button>
                    </div>
                </div>

                <!-- PP Upload & Preview Section -->
                <div>
                    <label for="pp-file" class="mb-2 text-sm font-medium text-gray-700">
                        Document PDF
                    </label>
                    <div class="mt-2">
                        <input type="file" name="pp_file" id="pp-file" accept="application/pdf"
                            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                    </div>

                    {{-- Notes --}}
                    <div class="mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal ukuran file 10MB, format document: PDF.
                        </p>
                    </div>

                    <!-- Current File Info -->
                    <div class="mt-4" x-show="ppFileName">
                        <p class="text-sm font-medium text-gray-700 mb-2">File saat ini:</p>
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span x-text="ppFileName"></span>
                            <a :href="ppFile" target="_blank"
                                class="ml-2 text-blue-500 hover:text-blue-700 underline">
                                Lihat PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('errors', @json($errors->messages()));

            Alpine.data('skPpSettings', () => ({
                // SK data
                skFile: @json($sk['file'] ?? ''),
                skFilePath: @json($sk['file_path'] ?? ''),
                skFileName: @json($sk['file_name'] ?? ''),

                // PP data
                ppFile: @json($pp['file'] ?? ''),
                ppFilePath: @json($pp['file_path'] ?? ''),
                ppFileName: @json($pp['file_name'] ?? ''),

                notification: {
                    type: "{{ session('success') ? 'success' : (session('error') ? 'error' : null) }}",
                    message: "{{ session('success') ?? (session('error') ?? null) }}"
                }
            }));
        });
    </script>
@endsection

@section('style')
    <style>
        .animate-progress {
            width: 100%;
            animation: progressBar 3s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }
    </style>
@endsection
