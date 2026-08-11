{{-- filepath: c:\laragon\www\work\tumbuh-kembang\resources\views\partials\patient-antro.blade.php --}}
<div class="rounded-2xl border border-gray-200 bg-white">
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <!-- Table Four -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
            <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Data Antro</h3>
                </div>
                <div class="text-sm text-gray-600">
                    <span x-text="'Poin terpilih: ' + selectedPoints.length + '/4'"></span>
                </div>
            </div>

            {{-- Antro Table --}}
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <!-- table header start -->
                    <thead class="border-gray-100 border-y bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <div>
                                        <span class="block font-medium text-gray-500 text-theme-xs">
                                            No
                                        </span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <div>
                                        <span class="block font-medium text-gray-500 text-theme-xs">
                                            Tgl Periksa / Usia
                                        </span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <div>
                                        <span class="block font-medium text-gray-500 text-theme-xs">
                                            Usia Koreksi / Usia Paska Menstruasi
                                        </span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        Created By
                                    </p>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        BB
                                    </p>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        TB
                                    </p>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        LK
                                    </p>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        Action
                                    </p>
                                </div>
                            </th>
                            <th class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <p class="font-medium text-gray-500 text-theme-xs">
                                        Poin
                                    </p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <!-- table header end -->

                    <!-- table body start -->
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($dataAntro as $antro)
                            <tr>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>

                                <td class="px-3 py-3 whitespace-nowrap">
                                    @php
                                        // Only calculate age conversion once per row
                                        [$tahun, $bulan, $hari] = convertDaysToYear(
                                            $antro->tgl_periksa ?? now(),
                                            $antro->total_usia_hari ?? 0,
                                        );
                                    @endphp
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ \Carbon\Carbon::parse($antro->tgl_periksa)->translatedFormat('d M y') }}
                                            / {{ $tahun }} th {{ $bulan }} bl {{ $hari }}
                                            hr
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex justify-center text-gray-700 text-theme-sm">
                                        <p>
                                            @if ($antro->usia_koreksi_total_hari && $antro->usia_koreksi_total_hari != 0)
                                                @php
                                                    // Only calculate correction age when needed
                                                    [$tahunKoreksi, $bulanKoreksi, $hariKoreksi] = convertDaysToYear(
                                                        $antro->tgl_periksa ?? now(),
                                                        $antro->usia_koreksi_total_hari,
                                                    );
                                                @endphp
                                                {{ $tahunKoreksi }} th {{ $bulanKoreksi }} bl
                                                {{ $hariKoreksi }}
                                                hr
                                            @elseif($antro->total_usia_hari == 0 || $antro->usia_koreksi_total_hari == 0 || is_null($antro->usia_koreksi_total_hari))
                                                0
                                            @else
                                                0
                                            @endif
                                            /
                                            @if (
                                                !is_null($antro->usia_gestasi_total_hari) &&
                                                    $antro->usia_gestasi_total_hari != 0 &&
                                                    $antro->usia_gestasi_total_hari <= 448)
                                                @php
                                                    // Only calculate gestational age when needed
                                                    [$mingguGestasi, $hariGestasi] = convertDaysToWeek(
                                                        $antro->tgl_periksa ?? now(),
                                                        $antro->usia_gestasi_total_hari,
                                                    );
                                                @endphp
                                                {{ $mingguGestasi }} mg {{ $hariGestasi }} hr
                                            @elseif(
                                                !is_null($antro->usia_gestasi_total_hari) &&
                                                    $antro->usia_gestasi_total_hari != 0 &&
                                                    $antro->usia_gestasi_total_hari > 448)
                                                PMA > 64 mg
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $antro->created_by_name }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $antro->berat_badan ? $antro->berat_badan . ' kg' : '-' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $antro->tinggi_badan ? $antro->tinggi_badan . ' cm' : '-' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-700 text-theme-sm">
                                            {{ $antro->lingkar_kepala ? $antro->lingkar_kepala . ' cm' : '-' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-2 justify-center">
                                        @if (Auth::user()->roles()->first()->name === 'super-admin')
                                            <!-- filepath: d:\laragon\www\closing\ekurva\resources\views\partials\patient-antro.blade.php -->
                                            <div x-data="{ notesModal: false }">
                                                <button type="button" @click="notesModal = true"
                                                    class="relative inline-block" type="button">
                                                    <i class="fa-solid fa-note-sticky text-gray-500"></i>

                                                    @if ($antro->notes && !is_null($antro->notes))
                                                        <span
                                                            class="absolute top-0 -right-1 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                                                    @endif
                                                </button>
                                                <!-- Modal -->
                                                <div x-show="notesModal" x-cloak
                                                    class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                                                    style="z-index: 99999 !important"
                                                    x-transition:enter="transition ease duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease duration-300"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0">

                                                    @php
                                                        // Properly prepare the notes for JavaScript
                                                        $notesContent = $antro->notes ?? '';
                                                        // No need to use addslashes since we'll use JSON encoding
                                                    @endphp

                                                    <div @click.outside="notesModal = false"
                                                        class="relative w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg"
                                                        x-data="{
                                                            notes: {{ Js::from($notesContent) }},
                                                            errors: {},
                                                            message: '',
                                                            status: '',
                                                            loading: false,
                                                            submitForm() {
                                                                this.loading = true;
                                                                this.errors = {};
                                                                this.message = '';
                                                                this.status = '';

                                                                fetch('{{ route('super-admin.patient.antro.update-notes') }}', {
                                                                        method: 'POST',
                                                                        headers: {
                                                                            'Content-Type': 'application/json',
                                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                        },
                                                                        body: JSON.stringify({
                                                                            notes: this.notes,
                                                                            antro_id: {{ $antro->id ?? 0 }}
                                                                        })
                                                                    })
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        this.loading = false;
                                                                        if (data.errors) {
                                                                            this.errors = data.errors;
                                                                            this.status = 'error';
                                                                        } else if (data.success) {
                                                                            this.message = data.success;
                                                                            this.status = 'success';
                                                                            setTimeout(() => {
                                                                                this.notesModal = false;
                                                                                // Optionally reload the data or update the UI
                                                                                window.location.reload();
                                                                            }, 1500);
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        this.loading = false;
                                                                        this.status = 'error';
                                                                        this.message = 'Terjadi kesalahan, silakan coba lagi.';
                                                                        console.error('Error:', error);
                                                                    });
                                                            }
                                                        }"
                                                        @keydown.enter.prevent="!loading && submitForm()">

                                                        <!-- Tombol Close -->
                                                        <button @click="notesModal = false"
                                                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                                                            ✖
                                                        </button>

                                                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Edit
                                                            Notes
                                                        </h4>

                                                        <!-- Alert message -->
                                                        <div x-show="status === 'success'" x-cloak
                                                            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"
                                                            x-transition:enter="transition ease duration-300"
                                                            x-transition:enter-start="opacity-0"
                                                            x-transition:enter-end="opacity-100">
                                                            <span x-text="message"></span>
                                                        </div>

                                                        <div x-show="status === 'error' && message" x-cloak
                                                            class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"
                                                            x-transition:enter="transition ease duration-300"
                                                            x-transition:enter-start="opacity-0"
                                                            x-transition:enter-end="opacity-100">
                                                            <span x-text="message"></span>
                                                        </div>

                                                        <div>
                                                            <x-textarea x-model="notes" id="notes" name="notes"
                                                                rows="5"
                                                                placeholder="Masukkan catatan tambahan (opsional)"></x-textarea>
                                                            <div x-show="errors.notes" x-cloak
                                                                class="text-red-500 text-sm mt-2">
                                                                <span x-text="errors.notes?.[0] || ''"></span>
                                                            </div>
                                                        </div>

                                                        <div class="flex justify-end mt-5">
                                                            <button @click="notesModal = false"
                                                                class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                                                                :disabled="loading">
                                                                Cancel
                                                            </button>

                                                            <button @click="submitForm()"
                                                                class="ml-3 px-4 py-2 text-sm text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                                                                :disabled="loading">
                                                                <template x-if="loading">
                                                                    <span
                                                                        class="inline-block animate-spin mr-1">⟳</span>
                                                                </template>
                                                                <span
                                                                    x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="{{ route('super-admin.patient.antro.edit', $antro->id) }}"
                                                type="button"><x-svg-edit /></a>
                                            <x-modal-delete title="Hapus Data Antro"
                                                message="Apakah Anda yakin ingin menghapus data antro?"
                                                confirmText="Hapus" cancelText="Batal"
                                                url="{{ route('super-admin.patient.antro.destroy', $antro->id) }}" />
                                        @else
                                            <!-- filepath: d:\laragon\www\closing\ekurva\resources\views\partials\patient-antro.blade.php -->
                                            <div x-data="{ notesModal: false }">
                                                <button type="button" @click="notesModal = true"
                                                    class="relative inline-block" type="button">
                                                    <i class="fa-solid fa-note-sticky text-gray-500"></i>

                                                    @if ($antro->notes && !is_null($antro->notes))
                                                        <span
                                                            class="absolute top-0 -right-1 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                                                    @endif
                                                </button>
                                                @if (Auth::user()->is_nakes && $antro->created_by == Auth::user()->id)
                                                    <!-- Modal -->
                                                    <div x-show="notesModal" x-cloak
                                                        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                                                        style="z-index: 99999 !important"
                                                        x-transition:enter="transition ease duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="transition ease duration-300"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0">

                                                        @php
                                                            // Properly prepare the notes for JavaScript
                                                            $notesContent = $antro->notes ?? '';
                                                            // No need to use addslashes since we'll use JSON encoding
                                                        @endphp

                                                        <div @click.outside="notesModal = false"
                                                            class="relative w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg"
                                                            x-data="{
                                                                notes: {{ Js::from($notesContent) }},
                                                                errors: {},
                                                                message: '',
                                                                status: '',
                                                                loading: false,
                                                                submitForm() {
                                                                    this.loading = true;
                                                                    this.errors = {};
                                                                    this.message = '';
                                                                    this.status = '';

                                                                    fetch('{{ route('patient.antro.update-notes') }}', {
                                                                            method: 'POST',
                                                                            headers: {
                                                                                'Content-Type': 'application/json',
                                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                            },
                                                                            body: JSON.stringify({
                                                                                notes: this.notes,
                                                                                antro_id: {{ $antro->id ?? 0 }}
                                                                            })
                                                                        })
                                                                        .then(response => response.json())
                                                                        .then(data => {
                                                                            this.loading = false;
                                                                            if (data.errors) {
                                                                                this.errors = data.errors;
                                                                                this.status = 'error';
                                                                            } else if (data.success) {
                                                                                this.message = data.success;
                                                                                this.status = 'success';
                                                                                setTimeout(() => {
                                                                                    this.notesModal = false;
                                                                                    // Optionally reload the data or update the UI
                                                                                    window.location.reload();
                                                                                }, 1500);
                                                                            }
                                                                        })
                                                                        .catch(error => {
                                                                            this.loading = false;
                                                                            this.status = 'error';
                                                                            this.message = 'Terjadi kesalahan, silakan coba lagi.';
                                                                            console.error('Error:', error);
                                                                        });
                                                                }
                                                            }"
                                                            @keydown.enter.prevent="!loading && submitForm()">

                                                            <!-- Tombol Close -->
                                                            <button @click="notesModal = false"
                                                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                                                                ✖
                                                            </button>

                                                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Edit
                                                                Notes
                                                            </h4>

                                                            <!-- Alert message -->
                                                            <div x-show="status === 'success'" x-cloak
                                                                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"
                                                                x-transition:enter="transition ease duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100">
                                                                <span x-text="message"></span>
                                                            </div>

                                                            <div x-show="status === 'error' && message" x-cloak
                                                                class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"
                                                                x-transition:enter="transition ease duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100">
                                                                <span x-text="message"></span>
                                                            </div>

                                                            <div>
                                                                <x-textarea x-model="notes" id="notes"
                                                                    name="notes" rows="5"
                                                                    placeholder="Masukkan catatan tambahan (opsional)"></x-textarea>
                                                                <div x-show="errors.notes" x-cloak
                                                                    class="text-red-500 text-sm mt-2">
                                                                    <span x-text="errors.notes?.[0] || ''"></span>
                                                                </div>
                                                            </div>

                                                            <div class="flex justify-end mt-5">
                                                                <button @click="notesModal = false"
                                                                    class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                                                                    :disabled="loading">
                                                                    Cancel
                                                                </button>

                                                                <button @click="submitForm()"
                                                                    class="ml-3 px-4 py-2 text-sm text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                                                                    :disabled="loading">
                                                                    <template x-if="loading">
                                                                        <span
                                                                            class="inline-block animate-spin mr-1">⟳</span>
                                                                    </template>
                                                                    <span
                                                                        x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Modal -->
                                                    <div x-show="notesModal" x-cloak
                                                        class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
                                                        style="z-index: 99999 !important"
                                                        x-transition:enter="transition ease duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="transition ease duration-300"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0">

                                                        @php
                                                            // Properly prepare the notes for JavaScript
                                                            $notesContent = $antro->notes ?? '';
                                                            // No need to use addslashes since we'll use JSON encoding
                                                        @endphp

                                                        <div @click.outside="notesModal = false"
                                                            class="relative w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg"
                                                            x-data="{
                                                                notes: {{ Js::from($notesContent) }},
                                                                errors: {},
                                                                message: '',
                                                                status: '',
                                                                loading: false,
                                                            }"
                                                            @keydown.enter.prevent="!loading && submitForm()">

                                                            <!-- Tombol Close -->
                                                            <button @click="notesModal = false"
                                                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-700">
                                                                ✖
                                                            </button>

                                                            <h4 class="text-lg font-semibold text-gray-800 mb-4">
                                                                Notes
                                                            </h4>

                                                            <!-- Alert message -->
                                                            <div x-show="status === 'success'" x-cloak
                                                                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"
                                                                x-transition:enter="transition ease duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100">
                                                                <span x-text="message"></span>
                                                            </div>

                                                            <div x-show="status === 'error' && message" x-cloak
                                                                class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"
                                                                x-transition:enter="transition ease duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100">
                                                                <span x-text="message"></span>
                                                            </div>

                                                            <div>
                                                                <template x-if="notes">
                                                                    <x-textarea x-model="notes" id="notes"
                                                                        rows="5" readonly disabled></x-textarea>
                                                                </template>

                                                                <template x-if="!notes">
                                                                    <x-textarea id="notes" rows="5"
                                                                        class="!bg-gray-100" readonly disabled>Tidak
                                                                        ada notes</x-textarea>
                                                                </template>
                                                                <div x-show="errors.notes" x-cloak
                                                                    class="text-red-500 text-sm mt-2">
                                                                    <span x-text="errors.notes?.[0] || ''"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <a href="{{ route('patient.antro.edit', $antro->id) }}"
                                                type="button"><x-svg-edit /></a>
                                            <x-modal-delete title="Hapus Data Antro"
                                                message="Apakah Anda yakin ingin menghapus data antro?"
                                                confirmText="Hapus" cancelText="Batal"
                                                url="{{ route('patient.antro.destroy', $antro->id) }}" />
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div class="flex items-center gap-3">
                                            <div @click="togglePoint({{ $antro->id }}, '{{ $antro->tgl_periksa }}')"
                                                class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                                :class="isSelected({{ $antro->id }}) ? 'border-brand-500 bg-brand-500' :
                                                    'bg-white border-gray-300'">
                                                <svg :class="isSelected({{ $antro->id }}) ? 'block' : 'hidden'"
                                                    width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="block">
                                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500 text-theme-xs">
                                            Tidak ada data
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- table body end -->
                </table>
            </div>

            {{-- Antro Pagination --}}
            {{-- <div class="border-t border-gray-200 px-6 py-4">
                {!! $antros->links() !!}
            </div> --}}
        </div>
        <!-- Table Four -->
    </div>
</div>
