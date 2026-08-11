{{-- filepath: c:\laragon\www\work\tumbuh-kembang\resources\views\partials\patient-graph.blade.php --}}
<div class="flex h-screen flex-col rounded-2xl border border-gray-200 bg-white xl:h-full xl:w-full mb-5">
    <div class="flex flex-col justify-between border-b border-gray-200 sm:flex-row">
        <div class="flex w-full items-center justify-between gap-3 px-4 py-4 sm:justify-normal">
            <h3 class="text-lg font-semibold text-gray-800">Grafik</h3>
        </div>
    </div>

    <!-- ====== Inbox Details Box Start -->
    <div class="h-screen overflow-y-auto">
        <div class="custom-scrollbar block h-full max-h-full overflow-auto p-5 xl:p-6">
            <div class="mb-9 flex items-center gap-3">
                <div class="h-12 w-12 rounded-full mt-3">
                    <img src="{{ asset('img-public/header/' . ($superAdmin->header ?? 'default-header.png')) }}"
                        alt="user" id="header-logo">
                </div>

                <div>
                    <span class="mb-0.5 block text-sm font-medium text-gray-800">
                        {{ config('app.name') }}
                    </span>
                    <span class="block text-theme-xs text-gray-500">
                        {{ $superAdmin->email }}
                    </span>
                </div>
            </div>

            @php
                $jenisKelamin = $patient->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan';
                $nomor = 1;
                $adaGrafik = false;
            @endphp

            <div class="mb-7 text-sm text-gray-500 w-full overflow-x-auto whitespace-nowrap">
                @foreach (range(1, 12) as $i)
                    @php
                        $namaTabel = 'table' . $i;
                        $judul =
                            $kurvaTableSettings->where('nama_tabel', $namaTabel)->first()->judul ??
                            'Judul Tidak Ditemukan';
                        $dataVariable = 'dataTable' . $i;
                        $dataTable = $$dataVariable;
                    @endphp

                    @if (!empty($dataTable) && count($dataTable) > 0)
                        @php $adaGrafik = true; @endphp
                        <div class="w-full min-w-[1000px] mb-20">
                            <p class="text-lg mb-4 font-semibold text-gray-800">
                                {{ $nomor++ }}. {{ $judul }} Anak {{ $jenisKelamin }}
                            </p>

                            <div class="w-full min-w-[950px] mx-auto overflow-x-auto">
                                <div
                                    class="w-full min-w-[950px] min-h-[450px] bg-white mx-auto lg:border lg:shadow rounded-[20px] p-3">
                                    <canvas id="chart-table-{{ $i }}"
                                        class="w-full mx-auto chartjs-canvas"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if (!$adaGrafik)
                    <p class="text-lg text-center text-gray-500 text-wrap mt-30">Grafik belum tersedia. Silakan tambah
                        data
                        terlebih dahulu.
                    </p>
                @endif

            </div>

        </div>
        <!-- ====== Inbox Details Box End -->
    </div>
</div>

{{-- Script anti klik kanan --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvases = document.querySelectorAll(".chartjs-canvas");
        canvases.forEach(canvas => {
            canvas.addEventListener("contextmenu", function(e) {
                e.preventDefault(); // Cegah klik kanan
            });
        });
    });
</script>
