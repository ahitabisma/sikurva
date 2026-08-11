{{-- filepath: c:\laragon\www\work\tumbuh-kembang\resources\views\partials\patient-detail.blade.php --}}
<div
    class="flex h-fit flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white xl:h-full xl:w-full mb-5 max-h-screen">
    <!-- Header Section -->
    <div class="px-4 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">Detail</h3>
    </div>

    {{-- Patient Data Section (Scrollable) --}}
    <div class="border-t border-gray-100 p-5 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-6 overflow-y-auto">
        {{-- Column 1: Basic Patient Info --}}
        <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed ">
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-40">Nama {{ Auth::user()->is_nakes ? 'Pasien' : 'Anak' }}</span>
                <span>: {{ $patient->nama }}</span>
            </div>
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-40">Jenis Kelamin / Tgl Lahir</span>
                <span>: {{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} /
                    {{ \Carbon\Carbon::parse($patient->tgl_lahir)->translatedFormat('d M y') }}</span>
            </div>
        </div>

        {{-- Column 2: Parent Heights --}}
        <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed">
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-36">Usia Kehamilan (GA)</span>
                <span>: {{ $patient->usia_kehamilan_minggu }} mg</span>
            </div>
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-36">Tinggi Ayah / Ibu</span>
                <span>: {{ $patient->tinggi_ayah ? $patient->tinggi_ayah : '-' }} /
                    {{ $patient->tinggi_ibu ? $patient->tinggi_ibu : '-' }} cm</span>
            </div>

        </div>

        {{-- Column 3: Contact Information --}}
        <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 leading-relaxed">
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-5">
                    <i class="fas fa-envelope mr-2"></i>
                </span>
                <span>: {{ $patient->email ?: '-' }}</span>
            </div>
            <div class="flex items-center">
                <span class="font-medium text-gray-900 w-5">
                    <i class="fab fa-whatsapp mr-2"></i>
                </span>
                <span>: {{ $patient->no_wa ? '+' . $patient->no_wa : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Action Section -->
    {{-- Jika user admin --}}
    @role('admin')
        <div class="px-4 py-4 border-t border-gray-100" x-data="{
            'penilaianPoint': {{ $pointSettings->where('name', 'PENILAIAN')->value('points') ?? 0 }},
            'downloadPoint': {{ $pointSettings->where('name', 'DOWNLOAD-GRAFIK')->value('points') ?? 0 }},
            'headerPoint': {{ $pointSettings->where('name', 'TAMBAH-HEADER')->value('points') ?? 0 }},
            'customEmailPoint': {{ $pointSettings->where('name', 'EMAIL-CUSTOM')->value('points') ?? 0 }},
            'customWaPoint': {{ $pointSettings->where('name', 'NO-WA-CUSTOM')->value('points') ?? 0 }},
            'pushEmailPoint': {{ $pointSettings->where('name', 'PUSH-EMAIL-GRAFIK')->value('points') ?? 0 }},
            'pushWhatsappPoint': {{ $pointSettings->where('name', 'PUSH-WHATSAPP-GRAFIK')->value('points') ?? 0 }},
            'copyPoint': {{ $pointSettings->where('name', 'COPY')->value('points') ?? 0 }},
            'isSupportHeader': {{ Auth::user()->isSupportHeader() || Auth::user()->isSupportHeader() == 1 ? 'true' : 'false' }},
            'hasCustomHeader': {{ (Auth::user()->instansi_id && Auth::user()->instansi->header) || Auth::user()->header ? 'true' : 'false' }},
            'displayName': '{{ Auth::user()->is_nakes && Auth::user()->instansi && Auth::user()->instansi->sender_name && Auth::user()->instansi->sender_name != '' ? Auth::user()->instansi->sender_name : '' }}',

            {{-- Download --}}
            openConfirmationDownloadModal() {
                const customMessage = `
                                                                                                                                                                                                    <p>Tindakan ini membutuhkan <strong class='text-blue-500'>${this.downloadPoint} poin</strong>. Apakah Anda yakin ingin melanjutkan?</p>
                                                                                                                                                                                                `;

                const customMessageHeader = `
                                                                                                                                                                                                    <p>Tindakan ini membutuhkan <strong class='text-blue-500'>${this.downloadPoint} poin</strong>. Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong class='text-blue-500'>${this.headerPoint} poin</strong> sehingga total point yang akan digunakan adalah <strong class='text-blue-500'>${this.downloadPoint + this.headerPoint} poin</strong>. Apakah Anda yakin ingin melanjutkan?</p>
                                                                                                                                                                                                `;
                window.dispatchEvent(
                    new CustomEvent('open-confirmation-modal', {
                        detail: {
                            title: 'Download',
                            points: this.downloadPoint,
                            callback: (skipConfirmation) => generatePDF(skipConfirmation),
                            message: this.hasCustomHeader && !this.isSupportHeader ? customMessageHeader : customMessage
                        }
                    })
                );
            },


        }">
            <div class="flex items-center gap-3">
                <div class="flex gap-3 flex-col lg:flex-row w-full">
                    <a href="{{ route('patient.antro.create', ['patientId' => $patient->id]) }}"
                        class="flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-700 transition ease-in-out duration-300">
                        + Antro
                    </a>

                    @if (Auth::user()->is_nakes)
                        <a href="{{ route('patient.antro.import', ['patientId' => $patient->id]) }}"
                            class="flex items-center justify-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-green-700 transition ease-in-out duration-300">
                            <i class="fa-solid fa-file-arrow-up"></i> Import Antro
                        </a>

                        {{-- Fungsi ada di patient-antro.blade.php --}}
                        <form action="{{ route('patient.penilaian.store', $patient->id) }}" method="post"
                            id="penilaianForm">
                            @csrf
                            <!-- Input hidden untuk menyimpan data selectedPoints -->
                            <input type="hidden" name="selectedPoints" x-ref="hiddenInput">

                            <button type="button"
                                class="w-full flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 transition ease-in-out duration-300"
                                @click="window.dispatchEvent(new CustomEvent('open-penilaian-modal', {
                                    detail: {
                                        points: penilaianPoint,
                                        callback: (skipConfirmation) => {
                                            updateHiddenInput();
                                            // If it is checked, add a hidden input to the form
                                            if (skipConfirmation) {
                                                const skipInput = document.createElement('input');
                                                skipInput.type = 'hidden';
                                                skipInput.name = 'skip_confirmation';
                                                skipInput.value = '1';
                                                document.getElementById('penilaianForm').appendChild(skipInput);
                                            }
                                            // Then submit the form
                                            document.getElementById('penilaianForm').submit();
                                        }
                                    }
                                }))">
                                Penilaian
                            </button>
                        </form>
                    @endif

                    {{-- Copy --}}
                    @if (Auth::user()->is_nakes && $patient->created_by != Auth::user()->id)
                        {{-- Fitur COPY IDA --}}
                        <form action="{{ route('patient.copy', ['patientId' => $patient->id]) }}" method="post"
                            id="copy-form">
                            @csrf
                            <button type="button"
                                @click="window.dispatchEvent(new CustomEvent('open-copy-modal', {
                                detail: {
                                    points: copyPoint,
                                    callback: (skipConfirmation) => {
                                        // If it is checked, add a hidden input to the form
                                        if (skipConfirmation) {
                                            const skipInput = document.createElement('input');
                                            skipInput.type = 'hidden';
                                            skipInput.name = 'skip_confirmation';
                                            skipInput.value = '1';
                                            document.getElementById('copy-form').appendChild(skipInput);
                                        }
                                        // Then submit the form
                                        document.getElementById('copy-form').submit();
                                    }
                                }
                            }))"
                                class="w-full flex items-center justify-center gap-2 rounded-lg bg-gray-500 hover:bg-gray-600 px-3 py-2 md:py-3 text-sm font-medium text-white shadow-theme-x transition ease-in-out duration-300">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </form>
                    @endif

                    @if ($patient->created_by === Auth::user()->id)
                        <a href="{{ route('patient.share', ['id' => $patient->id]) }}"
                            class="flex items-center justify-center rounded-lg bg-yellow-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-600 transition duration-200">
                            <i class="fa-solid fa-share"></i>
                        </a>
                    @endif

                    {{-- <button type="button"
                        class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 transition ease-in-out duration-300"
                        @click="updateHiddenInput(); debugPDF()">
                        Debug PDF
                    </button> --}}

                    <button
                        class="flex items-center justify-center rounded-lg bg-red-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-600 transition duration-200"
                        @click="
                    const customMessage = `<p>Tindakan ini membutuhkan <strong class='text-blue-500'>${downloadPoint} poin</strong>. Apakah Anda yakin ingin melanjutkan?</p>`;

                    const customMessageHeader = `<p>Tindakan ini membutuhkan <strong class='text-blue-500'>${downloadPoint} poin</strong>. Karena Anda sudah menambahkan header maka akan ada tambahan sebesar <strong class='text-blue-500'>${headerPoint} poin</strong> sehingga total point yang akan digunakan adalah <strong class='text-blue-500'>${downloadPoint + headerPoint} poin</strong>. Apakah Anda yakin ingin melanjutkan?</p>`;

                    window.dispatchEvent(new CustomEvent('open-download-modal', {
                        detail: {
                            points: downloadPoint,
                            callback: (skipConfirmation) => generatePDF(skipConfirmation),
                            message: hasCustomHeader && !isSupportHeader ? customMessageHeader : customMessage
                        }
                    }))">
                        <i class="fas fa-download"></i>
                    </button>

                    {{-- OLD PUSH EMAIL WITHOUT CUSTOM EMAIL --}}
                    {{-- <button
                        class="flex items-center justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-email-modal', {
                            detail: {
                                points: pushEmailPoint,
                                pointsHeader: (hasCustomHeader ? headerPoint : 0),
                                hasCustomHeader: (hasCustomHeader ? true : false),
                                isSupportHeader: isSupportHeader,
                                displayName: displayName,
                                pointsEmail: (displayName && displayName.trim() !== '' ? customEmailPoint : 0),
                                callback: (displayName, skipConfirmation) => generateAndSendPDF(displayName, skipConfirmation)
                            }
                        }))">
                        <i class="fas fa-envelope"></i>
                    </button> --}}

                    {{-- PUSH EMAIL WITH CUSTOM EMAIL --}}
                    <button
                        class="flex items-center justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-custom-email-modal', {
                            detail: {
                                points: pushEmailPoint,
                                pointsHeader: (hasCustomHeader ? headerPoint : 0),
                                pointsEmail: (displayName && displayName.trim() !== '' ? customEmailPoint : 0),
                                hasCustomHeader: (hasCustomHeader ? true : false),
                                isSupportHeader: isSupportHeader,
                                displayName: displayName,
                                patientEmail: '{{ $patient->email }}',
                                userEmail: '{{ Auth::user()->email }}',
                                callback: (emailAddress, displayName) => generateAndSendCustomPDF(emailAddress, displayName)
                            }
                        }))">
                        <i class="fas fa-envelope"></i>
                    </button>

                    <button
                        class="flex items-center justify-center rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-whatsapp-modal', {
                            detail: {
                                points: pushWhatsappPoint,
                                pointsHeader: (hasCustomHeader ? headerPoint : 0),
                                hasCustomHeader: (hasCustomHeader ? true : false),
                                patientWaNumber: '{{ $patient->no_wa }}',
                                isSupportHeader: isSupportHeader,
                                userWaNumber: '{{ Auth::user()->phone }}',
                                callback: (waNumber) => generateAndSendWa(waNumber)
                            }
                        }))">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                </div>
            </div>
        </div>
    @endrole

    {{-- Jika user super admin --}}
    @role('super-admin')
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex gap-3 flex-col lg:flex-row w-full">
                    <a href="{{ route('super-admin.patient.antro.create', ['patientId' => $patient->id]) }}"
                        class="flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-700 transition ease-in-out duration-300">+
                        Antro</a>

                    <a href="{{ route('super-admin.patient.antro.import', ['patientId' => $patient->id]) }}"
                        class="flex items-center justify-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-green-700 transition ease-in-out duration-300"><i
                            class="fa-solid fa-file-arrow-up"></i> Import Antro</a>

                    <x-export-button url="{{ route('super-admin.patient.antro.export', $patient->id) }}"
                        text="Export Antro" />

                    {{-- Fungsi ada di patient-antro.blade.php --}}
                    <form action="{{ route('super-admin.patient.penilaian.store', $patient->id) }}" method="post"
                        @submit="updateHiddenInput">
                        @csrf
                        <!-- Input hidden untuk menyimpan data selectedPoints -->
                        <input type="hidden" name="selectedPoints" x-ref="hiddenInput">

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 transition ease-in-out duration-300">
                            Penilaian
                        </button>
                    </form>

                    <!-- Download Button with Icon -->
                    <button
                        class="flex items-center justify-center rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-600 transition duration-200"
                        @click="generatePDF">
                        <i class="fas fa-download"></i>
                    </button>

                    <!-- Email Button with Icon -->
                    {{-- <button
                        class="flex items-center justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-email-modal', {
                            detail: {
                                points: 0,
                                pointsHeader: 0,
                                hasCustomHeader: false,
                                isSuperAdmin: true,
                                callback: (displayName) => generateAndSendPDFSuperAdmin(displayName)
                            }
                        }))">
                        <i class="fas fa-envelope"></i>
                    </button> --}}

                    <!-- Custom Email Button with Icon -->
                    <button
                        class="flex items-center justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-custom-email-modal', {
                            detail: {
                                points: 0,
                                pointsHeader: 0,
                                pointsEmail: 0,
                                hasCustomHeader: false,
                                isSuperAdmin: true,
                                displayName: '',
                                patientEmail: '{{ $patient->email }}',
                                userEmail: '{{ Auth::user()->email }}',
                                callback: (emailAddress, displayName) => generateAndSendCustomPDFSuperAdmin(emailAddress, displayName)
                            }
                        }))">
                        <i class="fas fa-envelope"></i>
                    </button>

                    <!-- WhatsApp Button with Green Color -->
                    <button
                        class="flex items-center justify-center rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-600 transition duration-200"
                        @click="window.dispatchEvent(new CustomEvent('open-push-whatsapp-modal', {
                            detail: {
                                points: 0,
                                pointsHeader: 0,
                                hasCustomHeader: false,
                                patientWaNumber: '{{ $patient->no_wa }}',
                                userWaNumber: '{{ Auth::user()->phone }}',
                                isSuperAdmin: true,
                                callback: (waNumber) => generateAndSendWaSuperAdmin(waNumber)
                            }
                        }))">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                </div>
            </div>
        </div>
    @endrole
</div>
<x-push-email-modal />
<x-push-custom-email-modal />
<x-push-whatsapp-modal />
<x-copy-modal />
<x-penilaian-modal />
<x-download-modal />
