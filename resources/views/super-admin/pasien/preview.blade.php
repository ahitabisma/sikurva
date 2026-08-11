@extends('layouts.tailadmin')

@section('content')
    {{-- Data Kurva --}}
    @php
        // Use pre-processed chart data from controller
        $dataTable1 = $chartData['dataTable1'];
        $dataTable2 = $chartData['dataTable2'];
        $dataTable3 = $chartData['dataTable3'];
        $dataTable4 = $chartData['dataTable4'];
        $dataTable5 = $chartData['dataTable5'];
        $dataTable6 = $chartData['dataTable6'];
        $dataTable7 = $chartData['dataTable7'];
        $dataTable8 = $chartData['dataTable8'];
        $dataTable9 = $chartData['dataTable9'];
        $dataTable10 = $chartData['dataTable10'];
        $dataTable11 = $chartData['dataTable11'];
        $dataTable12 = $chartData['dataTable12'];
    @endphp

    <div x-data="antroPointSelection()">
        {{-- Modal Notification --}}
        @include('partials.notification-modal')

        <!-- Add this custom modal for validation messages -->
        <div x-ref="validationModal" x-show="showValidationModal" x-cloak
            class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
            style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @keydown.enter.window.prevent="if(showValidationModal) { closeModal(); }">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto z-10 shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 x-text="modalTitle" class="text-lg font-semibold"></h3>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <p x-text="modalMessage" class="text-gray-700"></p>
                </div>
                <div class="flex justify-end">
                    <button @click="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200">
                        OK
                    </button>
                </div>
            </div>
        </div>

        <!-- Add this confirmation modal with Yes/No buttons -->
        <div x-ref="confirmationModal" x-show="showConfirmModal" x-cloak
            class="fixed inset-0 flex items-center justify-center p-5 bg-gray-400/50 backdrop-blur-lg"
            style="z-index: 99999 !important" x-transition:enter="transition ease duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.enter.window.prevent="if(showConfirmModal) { confirmYesCallback(); closeConfirmationModal(); }">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto z-10 shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 x-text="confirmModalTitle" class="text-lg font-semibold"></h3>
                    <button @click="closeConfirmationModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <p x-text="confirmModalMessage" class="text-gray-700"></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button @click="confirmNoCallback(); closeConfirmationModal();"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                        Tidak
                    </button>
                    <button @click="confirmYesCallback(); closeConfirmationModal();"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200">
                        Ya
                    </button>
                </div>
            </div>
        </div>

        {{-- Detail Pasien --}}
        @include('partials.patient-detail', ['patient' => $patient])

        {{-- Grafik --}}
        @include('partials.patient-grafik', [
            'kurvaTableSettings' => $kurvaTableSettings,
            'patient' => $patient,
            'dataAntro' => $dataAntro,
            'kurvaData' => $kurvaData,
            'superAdmin' => $superAdmin,
        ])

        {{-- Data Antro --}}
        @include('partials.patient-antro', ['dataAntro' => $dataAntro])
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/chart-style.css') }}">
@endsection

@section('script')
    {{-- Alpine JS --}}
    <script>
        function antroPointSelection() {
            return {
                selectedPoints: [],
                maxPoints: 4,
                minPoints: 1,
                dates: [
                    @foreach ($dataAntro as $antro)
                        {
                            id: {{ $antro->id }},
                            date: '{{ $antro->tgl_periksa }}'
                        },
                    @endforeach
                ],
                showValidationModal: false,
                modalTitle: '',
                modalMessage: '',
                showConfirmModal: false,
                confirmModalTitle: '',
                confirmModalMessage: '',
                pendingPoint: null,
                confirmYesCallback: () => {},
                confirmNoCallback: () => {},

                init() {
                    // Sort dates by most recent first (if not already sorted)
                    this.dates.sort((a, b) => moment(b.date).valueOf() - moment(a.date).valueOf());

                    // Auto-select the 2 default points initially if we have data
                    if (this.dates.length > 0) {
                        // Point 1: The most recent date
                        const mostRecentPoint = this.dates[0];
                        this.selectedPoints.push({
                            id: mostRecentPoint.id,
                            date: mostRecentPoint.date
                        });

                        // Point 2: Find the date closest to 90 days before the most recent
                        const recentMoment = moment(mostRecentPoint.date);
                        const target90DaysAgo = moment(mostRecentPoint.date).subtract(90, 'days');

                        let candidatePoints = [];
                        // Filter points that are at least 90 days apart from the most recent
                        this.dates.slice(1).forEach(point => {
                            const pointMoment = moment(point.date);
                            // Calculate days difference between this point and most recent
                            const diffDays = recentMoment.diff(pointMoment, 'days');

                            if (diffDays >= 90) {
                                candidatePoints.push({
                                    point,
                                    diffFromTarget: Math.abs(pointMoment.diff(target90DaysAgo, 'days'))
                                });
                            }
                        });

                        // Sort by closest to 90 days target
                        candidatePoints.sort((a, b) => a.diffFromTarget - b.diffFromTarget);

                        // Select the closest valid point (if any)
                        if (candidatePoints.length > 0) {
                            const closestPoint = candidatePoints[0].point;
                            this.selectedPoints.push({
                                id: closestPoint.id,
                                date: closestPoint.date
                            });
                        }
                    }
                },

                // Method to show regular modal
                showModal(title, message) {
                    this.modalTitle = title;
                    this.modalMessage = message;
                    this.showValidationModal = true;
                },

                // Method to close regular modal
                closeModal() {
                    this.showValidationModal = false;
                },

                // New method to show confirmation modal with Yes/No options
                showConfirmationModal(title, message, yesCallback, noCallback) {
                    this.confirmModalTitle = title;
                    this.confirmModalMessage = message;
                    this.confirmYesCallback = yesCallback || (() => {});
                    this.confirmNoCallback = noCallback || (() => {});
                    this.showConfirmModal = true;
                },

                // Method to close confirmation modal
                closeConfirmationModal() {
                    this.showConfirmModal = false;
                },

                togglePoint(id, date) {
                    if (this.isSelected(id)) {
                        // Remove from selection if it doesn't go below minimum
                        if (this.selectedPoints.length > this.minPoints) {
                            this.selectedPoints = this.selectedPoints.filter(point => point.id !== id);
                        } else {
                            this.showModal('Peringatan', `Minimal harus ada ${this.minPoints} poin yang terpilih.`);
                        }
                    } else {
                        // Add to selection if we haven't reached maximum
                        if (this.selectedPoints.length < this.maxPoints) {
                            const newPoint = {
                                id,
                                date
                            };

                            // If adding this would make 2+ points, check date differences
                            if (this.selectedPoints.length >= 1) {
                                // Check if the new point would have less than 90 days difference with ANY existing points
                                const momentNewDate = moment(date);
                                let hasClosePoint = false;

                                // Check against all existing points
                                for (const existingPoint of this.selectedPoints) {
                                    const momentExistingDate = moment(existingPoint.date);
                                    const diffDays = Math.abs(momentExistingDate.diff(momentNewDate, 'days'));

                                    // console.log(
                                    //     `Checking date difference: ${momentExistingDate.format('YYYY-MM-DD')} - ${momentNewDate.format('YYYY-MM-DD')} = ${diffDays} days`
                                    // );

                                    if (diffDays < 90) {
                                        hasClosePoint = true;

                                        // Store the pending point temporarily
                                        this.pendingPoint = newPoint;

                                        // Show confirmation modal
                                        this.showConfirmationModal(
                                            'Peringatan',
                                            'Poin yang dipilih kurang dari 3 bulan dari poin sebelumnya, dapat menyebabkan perhitungan laju pertumbuhan kurang akurat. Apakah anda tetap mau melanjutkan?',
                                            () => {
                                                // Callback for "Ya" - add point and close modal
                                                this.selectedPoints.push(this.pendingPoint);
                                                this.pendingPoint = null;
                                                this.closeConfirmationModal();
                                            },
                                            () => {
                                                // Callback for "Tidak" - just close modal
                                                this.pendingPoint = null;
                                                this.closeConfirmationModal();
                                            }
                                        );
                                        return; // Exit function after showing modal
                                    }
                                }

                                // If no close points were found (all distances >= 90 days), add normally
                                if (!hasClosePoint) {
                                    this.selectedPoints.push(newPoint);
                                }
                            } else {
                                // First point, add normally
                                this.selectedPoints.push(newPoint);
                            }
                        } else {
                            this.showModal('Peringatan', `Maksimal hanya ${this.maxPoints} poin yang dapat dipilih.`);
                        }
                    }
                },

                isSelected(id) {
                    return this.selectedPoints.some(point => point.id === id);
                },

                // The rest of your methods would follow...
                validateDateDifference() {
                    // No longer needed to block submission with < 90 day differences
                    // Since we now allow it with confirmation
                    return true;
                },

                updateHiddenInput() {
                    // Masukkan data array ke dalam input hidden dalam bentuk JSON
                    this.$refs.hiddenInput.value = JSON.stringify(this.selectedPoints);
                },

                async sendPenilaian(patientId) {
                    try {
                        // Ambil selectedPoints yang diperlukan (pastikan sudah didefinisikan sebelumnya)
                        const selectedPoints = this.selectedPoints || [];

                        // Menampilkan loading jika diperlukan
                        loadingOverlay.classList.remove('hidden');

                        const response = await fetch(`/patient/antro/penilaian/${patientId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                selectedPoints: selectedPoints
                            })
                        });

                        if (!response.ok) {
                            throw new Error("Gagal mengirim data.");
                        }

                        const result = await response.json();

                        // Menampilkan pesan dan redirect jika berhasil
                        if (result.status === 'success') {
                            // showNotification(result.message, false);
                            window.location.href = result.redirect; // Redirect jika ada
                        }

                    } catch (error) {
                        alert("Terjadi kesalahan: " + error.message);
                    } finally {
                        loadingOverlay.classList.add('hidden'); // Menyembunyikan loading jika selesai
                    }
                },

                // New methods for chart images and PDF generation
                async saveChartImages() {
                    let foundCharts = false;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    for (let i = 1; i <= 12; i++) {
                        const canvas = document.getElementById(`chart-table-${i}`);

                        if (!canvas) {
                            console.warn(`Canvas with ID "chart-table-${i}" not found. Skipping...`);
                            continue; // Skip loop iteration kalau canvas nggak ada
                        }

                        foundCharts = true;
                        const dataUrl = canvas.toDataURL('image/png');
                        await fetch('/save-chart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                image: dataUrl,
                                filename: `chart-${patientId}-table${i}.png`
                            })
                        });
                    }

                    return foundCharts;
                },

                generatePDF: async function() {
                    try {
                        loadingOverlay.classList.remove('hidden');

                        // Step 1: Save all chart images
                        const chartsExist = await this.saveChartImages();

                        if (!chartsExist) {
                            throw new Error('Belum ada grafik yang tersedia untuk di-export');
                        }

                        // Step 2: Request PDF generation
                        // console.log(this.selectedPoints);
                        const response = await fetch(`/super-admin/generate-chart-pdf/${patientId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                selectedPoints: this.selectedPoints
                            })
                        });

                        if (!response.ok) throw new Error('Gagal generate PDF');

                        // Step 3: Download the PDF
                        const blob = await response.blob();
                        const url = URL.createObjectURL(blob);

                        // Get filename from response headers
                        const contentDisposition = response.headers.get('Content-Disposition');
                        const fileNameMatch = contentDisposition && contentDisposition.match(/filename="(.+)"/);
                        const fileName = fileNameMatch ? fileNameMatch[1] : 'grafik-kurva.pdf';

                        // Create download link
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = fileName;
                        document.body.appendChild(link);
                        link.click();

                        // Clean up
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);

                        showNotification('PDF berhasil dibuat dan diunduh!', false);
                    } catch (error) {
                        console.error('Terjadi kesalahan saat generate PDF:', error);
                        showNotification(error.message || 'Gagal generate PDF. Silakan coba lagi.', true);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                },

                generateAndSendPDFSuperAdmin: async function(displayName) {
                    try {
                        loadingOverlay.classList.remove('hidden');

                        // Step 1: Save all chart images
                        const chartsExist = await this.saveChartImages();

                        if (!chartsExist) {
                            throw new Error('Belum ada grafik yang tersedia untuk dikirim via email');
                        }

                        // Step 2: Request PDF generation and email sending
                        // console.log(this.selectedPoints);
                        const response = await fetch(`/super-admin/generate-and-send-pdf/${patientId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                selectedPoints: this.selectedPoints,
                                displayName: displayName
                            })
                        });

                        // Ambil isi JSON dari response
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal generate dan kirim email PDF');

                        // Tampilkan pesan dari controller
                        showNotification(data.message, false);
                    } catch (error) {
                        console.error('Terjadi kesalahan saat kirim email:', error);
                        showNotification(error.message || 'Gagal mengirim email. Silakan coba lagi.', true);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                },

                generateAndSendWaSuperAdmin: async function(waNumber) {
                    try {
                        loadingOverlay.classList.remove('hidden');

                        // Step 1: Save all chart images
                        const chartsExist = await this.saveChartImages();

                        if (!chartsExist) {
                            throw new Error('Belum ada grafik yang tersedia untuk dikirim via WhatsApp');
                        }

                        // Step 2: Request PDF generation and WhatsApp sending
                        const response = await fetch(`/super-admin/generate-and-send-wa/${patientId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                selectedPoints: this.selectedPoints,
                                whatsappNumber: waNumber, // Use the parameter passed from the modal
                            })
                        });

                        // Parse JSON response
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal generate dan kirim WhatsApp PDF');

                        // Show success notification
                        showNotification(data.message, false);
                    } catch (error) {
                        console.error('Terjadi kesalahan saat kirim WhatsApp:', error);
                        showNotification(error.message || 'Gagal mengirim WhatsApp. Silakan coba lagi.', true);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                },

                generateAndSendCustomPDFSuperAdmin: async function(emailAddress, displayName) {
                    try {
                        loadingOverlay.classList.remove('hidden');

                        // Step 1: Save all chart images
                        const chartsExist = await this.saveChartImages();

                        if (!chartsExist) {
                            throw new Error('Belum ada grafik yang tersedia untuk dikirim via email');
                        }

                        // Step 2: Request PDF generation and custom email sending
                        const response = await fetch(`/super-admin/generate-and-send-custom-pdf/${patientId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                selectedPoints: this.selectedPoints,
                                emailAddress: emailAddress,
                                displayName: displayName
                            })
                        });

                        // Parse JSON response
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal generate dan kirim custom email PDF');

                        // Show success notification
                        showNotification(data.message, false);
                    } catch (error) {
                        console.error('Terjadi kesalahan saat kirim custom email:', error);
                        showNotification(error.message || 'Gagal mengirim custom email. Silakan coba lagi.', true);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                }

            };
        }
    </script>
    <script>
        // // Nonaktifkan klik kanan
        // document.addEventListener('contextmenu', e => e.preventDefault());

        // // Nonaktifkan F12 dan inspect shortcuts
        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "F12" ||
        //         (e.ctrlKey && e.shiftKey && ['I', 'C', 'J'].includes(e.key.toUpperCase())) ||
        //         (e.ctrlKey && e.key === 'u')) {
        //         e.preventDefault();
        //     }
        // });
    </script>

    {{-- Alpine Notification --}}
    <script src="{{ asset('js/notification.js') }}"></script>

    {{-- Generate PDF and Send Email PDF --}}
    <script>
        // Initialize main variables
        const loadingOverlay = document.getElementById('loading-overlay');
        const patientId = {{ $patient->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    {{-- Generate PDF and Send Email PDF --}}
    {{-- <script src="{{ asset('js/chart-pdf.js') }}"></script> --}}

    {{-- Plugin ChartJs General --}}
    <script>
        const jenisKelamin = "{{ $patient->jenis_kelamin }}";
        const color = jenisKelamin === 'L' ? 'blue' : 'red';
        const backgroundColor = jenisKelamin === 'L' ? 'skyblue' : 'pink';
    </script>
    {{-- Plugin ChartJs General --}}
    <script src="{{ asset('js/chart-plugin.js') }}"></script>

    {{-- Function Generate Label dan Dataset --}}
    <script src="{{ asset('js/chart-label-dataset.js') }}"></script>
    {{-- Funsgsi Init Chart WHO dan IG --}}
    <script src="{{ asset('js/chart-init.js') }}"></script>
    <script>
        // Kurva Table Setting
        const kurvaTableSettings = @json($kurvaTableSettings);

        const settingsMap = {};
        kurvaTableSettings.forEach(item => {
            settingsMap[item.nama_tabel] = item;
        });

        // Kurva Data
        const kurvaData = @json($kurvaData);

        // Data Table 1 (Berat Badan)
        const dataTable1 = @json($dataTable1->toArray());
        const arrayTable1 = Object.entries(dataTable1).map(([berat_badan, usia_hari]) => ({
            x: parseFloat(berat_badan),
            y: parseFloat(usia_hari),
        }));

        // Data Table 2 (Tinggi Badan)
        const dataTable2 = @json($dataTable2->toArray());
        const arrayTable2 = Object.entries(dataTable2).map(([tinggi_badan, usia_hari]) => ({
            x: parseFloat(tinggi_badan),
            y: parseFloat(usia_hari),
        }));

        // Data Table 3 (Lingkar Kepala)
        const dataTable3 = @json($dataTable3->toArray());
        const arrayTable3 = Object.entries(dataTable3).map(([lingkar_kepala, usia_hari]) => ({
            x: parseFloat(lingkar_kepala),
            y: parseFloat(usia_hari),
        }));

        // Data Table 4 (Berat Badan => Tinggi Badan)
        const dataTable4 = @json($dataTable4->toArray());
        const arrayTable4 = Array.isArray(dataTable4) ? dataTable4.map((item) => ({
            x: (item.tinggi_badan),
            y: (item.berat_badan),
        })) : Object.values(dataTable4).map((item) => ({
            x: (item.tinggi_badan),
            y: (item.berat_badan),
        }));

        // Data Table 5 (IMT => Usia Hari)
        const dataTable5 = @json($dataTable5->toArray());
        const arrayTable5 = Object.entries(dataTable5).map(([imt, usia_hari]) => ({
            x: parseFloat(imt),
            y: parseFloat(usia_hari),
        }));

        // Data Table 6 (Usia Hari => Berat Badan )
        const dataTable6 = @json($dataTable6->toArray());
        const arrayTable6 = Object.entries(dataTable6).map(([tinggi, berat]) => ({
            x: parseFloat(tinggi),
            y: parseFloat(berat),
        }));

        // Data Table 7 (IMT => Usia Hari)
        const dataTable7 = @json($dataTable7->toArray());
        const arrayTable7 = Object.entries(dataTable7).map(([imt, usia_bulan]) => ({
            x: parseFloat(imt),
            y: parseFloat(usia_bulan),
        }));

        // Data Table 8 (Usia Bulan => Tinggi)
        const dataTable8 = @json($dataTable8->toArray());
        const arrayTable8 = Object.entries(dataTable8).map(([usia_bulan, tinggi]) => ({
            x: parseFloat(usia_bulan),
            y: parseFloat(tinggi),
        }));

        // Data Table 9 (Usia Gestasi => Berat)
        const dataTable9 = @json($dataTable9->toArray());
        const arrayTable9 = Object.entries(dataTable9).map(([usia_gestasi, berat_badan]) => ({
            x: parseFloat(usia_gestasi),
            y: parseFloat(berat_badan),
        }));

        // Data Table 10 (Usia Gestasi => Tinggi)
        const dataTable10 = @json($dataTable10->toArray());
        const arrayTable10 = Object.entries(dataTable10).map(([usia_gestasi, tinggi_badan]) => ({
            x: parseFloat(usia_gestasi),
            y: parseFloat(tinggi_badan),
        }));

        // Data Table 11 (Usia Gestasi => Lingkar Kepala)
        const dataTable11 = @json($dataTable11->toArray());
        const arrayTable11 = Object.entries(dataTable11).map(([usia_gestasi, lingkar_kepala]) => ({
            x: parseFloat(usia_gestasi),
            y: parseFloat(lingkar_kepala),
        }));

        // Data Table 12 (Panjang Badan => Berat Badan)
        const dataTable12 = @json($dataTable12->toArray());
        const arrayTable12 = Object.entries(dataTable12).map(([tinggi_badan, berat_badan]) => ({
            x: parseFloat(tinggi_badan).toFixed(1),
            y: parseFloat(berat_badan),
        }));

        // Generate Dataset for Table WHO 1-8
        // Labels Table 1
        const maxDays = kurvaData.table1.length - 1;
        const month = ((maxDays) / 60);

        const labelsTable1 = kurvaData.table1
            .map(item => item.day);

        labelsTable1.push(...Array(60).fill(''));

        // Dataset Table 1
        const datasetsTable1 = generateDatasetsWho('table1', arrayTable1, 'Berat Badan');

        // Labels Table 2
        const maxDaysTable2 = kurvaData.table2.length - 1;
        const monthTable2 = ((maxDaysTable2) / 60);

        const labelsTable2 = kurvaData.table2
            .map(item => item.day);

        labelsTable2.push(...Array(60).fill(''));

        // Dataset Table 2
        const datasetsTable2 = generateDatasetsWho('table2', arrayTable2, 'Tinggi Badan');

        // Labels Table 3
        const maxDaysTable3 = kurvaData.table3.length - 1;
        const monthTable3 = ((maxDaysTable3) / 60);

        const labelsTable3 = kurvaData.table3
            .map(item => item.day);

        labelsTable3.push(...Array(60).fill(''));

        // Dataset Table 3
        const datasetsTable3 = generateDatasetsWho('table3', arrayTable3, 'Lingkar Kepala');

        // Labels Table 4
        const maxDaysTable4 = kurvaData.table4.length - 1;
        const monthTable4 = ((maxDaysTable4) / 60);

        const labelsTable4 = kurvaData.table4
            .map(item => item.length);

        labelsTable4.push(...Array(20).fill(''));

        // Dataset Table 4
        const datasetsTable4 = generateDatasetsWho('table4', arrayTable4, 'Berat Badan');
        // Labels Table 5
        const maxDaysTable5 = kurvaData.table5.length - 1;
        const monthTable5 = ((maxDaysTable5) / 60);

        const labelsTable5 = kurvaData.table5
            .map(item => item.day);

        labelsTable5.push(...Array(60).fill(''));

        // Dataset Table 5
        const datasetsTable5 = generateDatasetsWho('table5', arrayTable5, 'IMT');

        // Labels Table 6
        const maxDaysTable6 = kurvaData.table6.length - 1;
        const monthTable6 = ((maxDaysTable6) / 60);

        const labelsTable6 = kurvaData.table6
            .map(item => item.month);

        // Tambah data kosong di awal array biar mulainya ga dari titik awal
        labelsTable6.unshift(60);

        labelsTable6.push(...Array(3).fill(''));

        // Dataset Table 6
        const datasetsTable6 = generateDatasetsWho('table6', arrayTable6, 'Berat Badan');

        // Labels Table 7
        const maxDaysTable7 = kurvaData.table7.length - 1;
        const monthTable7 = ((maxDaysTable7) / 60);

        const labelsTable7 = kurvaData.table7
            .map(item => item.month);

        // Tambah data kosong di awal array biar mulainya ga dari titik awal
        labelsTable7.unshift(60);

        labelsTable7.push(...Array(3).fill(''));

        // Dataset Table 7
        const datasetsTable7 = generateDatasetsWho('table7', arrayTable7, 'IMT');

        // Labels Table 8
        const maxDaysTable8 = kurvaData.table8.length - 1;
        const monthTable8 = ((maxDaysTable8) / 60);

        const labelsTable8 = kurvaData.table8
            .map(item => item.month);

        // Tambah data kosong di awal array biar mulainya ga dari titik awal
        labelsTable8.unshift(60);
        labelsTable8.push(...Array(3).fill(''));

        // Dataset Table 8
        const datasetsTable8 = generateDatasetsWho('table8', arrayTable8, 'IMT');

        // For Kurva InterGrowth table 9, 10, 11
        // Labels Table 9
        const labelsTable9 = generateLabelsFromTable(kurvaData.table9);
        const datasetsTable9 = generateDatasetsIg('table9', labelsTable9, arrayTable9, 'Berat Badan');

        // Labels Table 10
        const labelsTable10 = generateLabelsFromTable(kurvaData.table10);
        const datasetsTable10 = generateDatasetsIg('table10', labelsTable10, arrayTable10, 'Tinggi Badan');

        // Labels Table 11
        const labelsTable11 = generateLabelsFromTable(kurvaData.table11);
        const datasetsTable11 = generateDatasetsIg('table11', labelsTable11, arrayTable11, 'Lingkar Kepala');

        // Labels Table 12
        const labelsTable12 = kurvaData.table12
            .map(item => item.length);

        labelsTable12.push(...Array(10).fill(''));
        const datasetsTable12 = generateDatasetsIg('table12', labelsTable12, arrayTable12, 'Berat Badan');
        // Konfigurasi chart dalam array atau objek
        const chartSettings = Array.from({
            length: 12
        }, (_, i) => ({
            // ID Canvas
            id: `chart-table-${i + 1}`,
            // Table Settings
            settings: settingsMap[`table${i + 1}`],
        }));

        // Fungsi untuk menghitung stepSize
        const calculateStepSize = (yMayor, yMinor) => {
            return Number(yMinor);
        };

        // Inisialisasi chart
        const charts = chartSettings
            .filter(chart => document.getElementById(chart.id)) // hanya ambil yang ada
            .map(chart => {
                const canvas = document.getElementById(chart.id);
                const ctx = canvas.getContext('2d');
                const stepSize = calculateStepSize(chart.settings.y_mayor, chart.settings.y_minor);
                const minor = Number(chart.settings.y_minor);
                const mayor = Number(chart.settings.y_mayor);
                const x_mayor = Number(chart.settings.x_mayor);
                const x_minor = Number(chart.settings.x_minor);

                return {
                    ctx: ctx,
                    stepSize: stepSize,
                    id: chart.id,
                    settings: chart.settings,
                    minor: minor,
                    mayor: mayor,
                    x_mayor: x_mayor,
                    x_minor: x_minor
                };
            });

        // Debug element canvas
        // chartSettings.forEach(chart => {
        //     if (!document.getElementById(chart.id)) {
        //         console.warn(`Canvas element with ID "${chart.id}" not found.`);
        //     }
        // });
    </script>

    {{-- Chart --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Pembulatan mod pkae Big.js biar hasil mod lebih presisi
            const isMultipleOf = (value, base) => {
                const val = new Big(value);
                const mod = val.mod(base);
                return mod.eq(0);
            };

            // Table 1
            if (arrayTable1.length != 0) {
                const charts1 = charts.find(c => c.id === 'chart-table-1');
                const totalDayTable1 = kurvaData.table1.length - 1;
                const dayInYearTable1 = Math.round(1825 / 5);


                const chartTable1 = new Chart(charts1.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable1,
                        datasets: datasetsTable1
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts1.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const day = labelsTable1[index];

                                        // Jika day adalah 0, tampilkan "0"
                                        if (day === 0) {
                                            return '0';
                                        }

                                        // Hitung tahun berdasarkan kelipatan 365 hari
                                        const tahun = Math.round(day /
                                            dayInYearTable1); // Tahun ke-1, ke-2, dst.
                                        const sisaHari = Math.round(day %
                                            dayInYearTable1); // Sisa hari dalam tahun tersebut
                                        // Tampilkan label untuk 2, 4, 6, 8, 10 dalam setiap tahun (dalam bulan, 1 bulan = 60 hari)
                                        if (day && sisaHari === 0) {
                                            return `${tahun} tahun`; // Tampilkan "1 tahun", "2 tahun", dst. pada kelipatan 360
                                        } else if (day && ((Math.abs(day % (dayInYearTable1 / 6)) <
                                                    1) ||
                                                sisaHari === (
                                                    Math
                                                    .round(dayInYearTable1 / 3)) || sisaHari === (Math
                                                    .round(
                                                        dayInYearTable1 /
                                                        2)) ||
                                                sisaHari === (Math.round(dayInYearTable1 / 1.5)) ||
                                                sisaHari === (Math
                                                    .round(
                                                        dayInYearTable1 /
                                                        1.2)))) {
                                            const bulan = Math.round(sisaHari / (Math.round(
                                                    dayInYearTable1 / 6)) *
                                                2); // Konversi ke bulan (2, 4, 6, 8, 10)
                                            if (bulan === 0) {
                                                return '';
                                            } else if (tahun === 1) {
                                                return `${bulan}`; // Hanya angka untuk tahun pertama
                                            } else {
                                                return `${bulan}`; // Angka + tahun untuk tahun berikutnya
                                            }
                                        }

                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    font: (context) => {
                                        const day = labelsTable1[context.index];
                                        if (day % dayInYearTable1 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const day = labelsTable1[context.index];
                                        // Garis tebal untuk kelipatan 2 bulan (60 hari)
                                        if (day % 360 === 0) {
                                            return '';
                                        }
                                        if (day && day % (Math.round(dayInYearTable1)) === 0) {
                                            return 'black';
                                        }
                                        if (day && (Math.abs(day % (dayInYearTable1 / 6)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis tebal untuk kelipatan 1 bulan (30 atau 31 hari), kecuali jika sudah kelipatan 60
                                        else if (day && (Math.abs(day % (dayInYearTable1 / 12)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis default untuk lainnya
                                        return '#fff';
                                    },
                                    lineWidth: (context) => {
                                        const day = labelsTable1[context.index];
                                        return day && (day % Math.round(dayInYearTable1) === 0) ? 0.5 :
                                            0.2; // Lebih tebal setiap 2 bulan
                                    },
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts1.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts1.settings.y_min), // Misalnya 90
                                max: Number(charts1.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts1.stepSize, // Interval
                                    callback: (value, index) => {

                                        if (value % charts1.mayor === 0) {
                                            return value;
                                        } else if ((value - charts1.minor) % charts1.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts1.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts1.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts1.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts1.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts1.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts1.settings.y_min), // Misalnya 90
                                max: Number(charts1.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts1.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts1.mayor === 0) {
                                            return value;
                                        } else if ((value - charts1.minor) % charts1.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts1.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts1.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts1.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts1.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 2
            if (arrayTable2.length != 0) {
                const charts2 = charts.find(c => c.id === 'chart-table-2');
                const totalDayTable2 = kurvaData.table2.length - 1;
                const dayInYearTable2 = Math.round(1825 / 5);
                const chartTable2 = new Chart(charts2.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable2,
                        datasets: datasetsTable2
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts2.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const day = labelsTable2[index];

                                        // Jika day adalah 0, tampilkan "0"
                                        if (day === 0) {
                                            return '0';
                                        }

                                        // Hitung tahun berdasarkan kelipatan 360 hari
                                        const tahun = Math.round(day /
                                            dayInYearTable2); // Tahun ke-1, ke-2, dst.
                                        const sisaHari = Math.round(day %
                                            dayInYearTable2); // Sisa hari dalam tahun tersebut
                                        // Tampilkan label untuk 2, 4, 6, 8, 10 dalam setiap tahun (dalam bulan, 1 bulan = 60 hari)
                                        if (day && sisaHari === 0) {
                                            return `${tahun} tahun`; // Tampilkan "1 tahun", "2 tahun", dst. pada kelipatan 360
                                        } else if (day && ((Math.abs(day % (dayInYearTable2 / 6)) <
                                                    1) ||
                                                sisaHari === (
                                                    Math
                                                    .round(dayInYearTable2 / 3)) || sisaHari === (Math
                                                    .round(
                                                        dayInYearTable2 /
                                                        2)) ||
                                                sisaHari === (Math.round(dayInYearTable2 / 1.5)) ||
                                                sisaHari === (Math
                                                    .round(
                                                        dayInYearTable2 /
                                                        1.2)))) {
                                            const bulan = Math.round(sisaHari / (Math.round(
                                                    dayInYearTable2 / 6)) *
                                                2); // Konversi ke bulan (2, 4, 6, 8, 10)
                                            if (bulan === 0) {
                                                return '';
                                            } else if (tahun === 1) {
                                                return `${bulan}`; // Hanya angka untuk tahun pertama
                                            } else {
                                                return `${bulan}`; // Angka + tahun untuk tahun berikutnya
                                            }
                                        }

                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    font: (context) => {
                                        const day = labelsTable2[context.index];
                                        if (day % dayInYearTable2 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const day = labelsTable2[context.index];
                                        // Garis tebal untuk kelipatan 2 bulan (60 hari)
                                        if (day % 360 === 0) {
                                            return '';
                                        }
                                        if (day && day % (Math.round(dayInYearTable2)) === 0) {
                                            return 'black';
                                        }
                                        if (day && (Math.abs(day % (dayInYearTable2 / 6)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis tebal untuk kelipatan 1 bulan (30 atau 31 hari), kecuali jika sudah kelipatan 60
                                        else if (day && (Math.abs(day % (dayInYearTable2 / 12)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis default untuk lainnya
                                        return '#fff';
                                    },
                                    lineWidth: (context) => {
                                        const day = labelsTable2[context.index];
                                        return day && (day % Math.round(dayInYearTable2) === 0) ? 0.5 :
                                            0.2; // Lebih tebal setiap 2 bulan
                                    },
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts2.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts2.settings.y_min), // Misalnya 90
                                max: Number(charts2.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts2.stepSize, // Interval
                                    callback: (value, index) => {

                                        if (value % charts2.mayor === 0) {
                                            return value;
                                        } else if ((value - charts2.minor) % charts2.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts2.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts2.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts2.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts2.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts2.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts2.settings.y_min), // Misalnya 90
                                max: Number(charts2.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts2.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts2.mayor === 0) {
                                            return value;
                                        } else if ((value - charts2.minor) % charts2.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts2.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts2.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts2.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts2.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 3
            if (arrayTable3.length != 0) {
                const charts3 = charts.find(c => c.id === 'chart-table-3');
                const totalDayTable3 = kurvaData.table3.length - 1;
                const dayInYearTable3 = Math.round(1825 / 5);
                const chartTable3 = new Chart(charts3.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable3,
                        datasets: datasetsTable3
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts3.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const day = labelsTable3[index];

                                        // Jika day adalah 0, tampilkan "0"
                                        if (day === 0) {
                                            return '0';
                                        }

                                        // Hitung tahun berdasarkan kelipatan 360 hari
                                        const tahun = Math.round(day /
                                            dayInYearTable3); // Tahun ke-1, ke-2, dst.
                                        const sisaHari = Math.round(day %
                                            dayInYearTable3); // Sisa hari dalam tahun tersebut
                                        // Tampilkan label untuk 2, 4, 6, 8, 10 dalam setiap tahun (dalam bulan, 1 bulan = 60 hari)
                                        if (day && sisaHari === 0) {
                                            return `${tahun} tahun`; // Tampilkan "1 tahun", "2 tahun", dst. pada kelipatan 360
                                        } else if (day && ((Math.abs(day % (dayInYearTable3 / 6)) <
                                                    1) ||
                                                sisaHari === (
                                                    Math
                                                    .round(dayInYearTable3 / 3)) || sisaHari === (Math
                                                    .round(
                                                        dayInYearTable3 /
                                                        2)) ||
                                                sisaHari === (Math.round(dayInYearTable3 / 1.5)) ||
                                                sisaHari === (Math
                                                    .round(
                                                        dayInYearTable3 /
                                                        1.2)))) {
                                            const bulan = Math.round(sisaHari / (Math.round(
                                                    dayInYearTable3 / 6)) *
                                                2); // Konversi ke bulan (2, 4, 6, 8, 10)
                                            if (bulan === 0) {
                                                return '';
                                            } else if (tahun === 1) {
                                                return `${bulan}`; // Hanya angka untuk tahun pertama
                                            } else {
                                                return `${bulan}`; // Angka + tahun untuk tahun berikutnya
                                            }
                                        }

                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    font: (context) => {
                                        const day = labelsTable3[context.index];
                                        if (day % dayInYearTable3 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const day = labelsTable3[context.index];
                                        // Garis tebal untuk kelipatan 2 bulan (60 hari)
                                        if (day % 360 === 0) {
                                            return '';
                                        }
                                        if (day && day % (Math.round(dayInYearTable3)) === 0) {
                                            return 'black';
                                        }
                                        if (day && (Math.abs(day % (dayInYearTable3 / 6)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis tebal untuk kelipatan 1 bulan (30 atau 31 hari), kecuali jika sudah kelipatan 60
                                        else if (day && (Math.abs(day % (dayInYearTable3 / 12)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis default untuk lainnya
                                        return '#fff';
                                    },
                                    lineWidth: (context) => {
                                        const day = labelsTable3[context.index];
                                        return day && (day % Math.round(dayInYearTable3) === 0) ? 0.5 :
                                            0.2; // Lebih tebal setiap 2 bulan
                                    },
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts3.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts3.settings.y_min), // Misalnya 90
                                max: Number(charts3.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts3.stepSize, // Interval
                                    callback: (value, index) => {

                                        if (value % charts3.mayor === 0) {
                                            return value;
                                        } else if ((value - charts3.minor) % charts3.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts3.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts3.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts3.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts3.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts3.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts3.settings.y_min), // Misalnya 90
                                max: Number(charts3.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts3.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts3.mayor === 0) {
                                            return value;
                                        } else if ((value - charts3.minor) % charts3.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts3.mayor === 0 || value % 1 === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts3.minor) === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts3.mayor === 0 || value % 1 === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts3.minor) === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 4
            if (arrayTable4.length != 0) {
                const charts4 = charts.find(c => c.id === 'chart-table-4');
                const chartTable4 = new Chart(charts4.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable4,
                        datasets: datasetsTable4
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts4.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    padding: {
                                        top: 20 // Tambahkan jarak dari chart ke judul x-axis
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        // Tampilkan label hanya pada kelipatan 10
                                        const length = labelsTable4[index];
                                        return length && length % charts4.x_mayor === 0 ? Number(
                                                length) :
                                            '';
                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const length = labelsTable4[context.index];
                                        return length && length % charts4.x_minor === 0 ? 'black' :
                                            '#fff'; // Garis Tahun lebih tebal (Hitam)
                                    },
                                    lineWidth: (context) => {
                                        const length = labelsTable4[context.index];
                                        return length && length % charts4.x_mayor === 0 ? 0.5 :
                                            0.1; // Lebih tebal di setiap 12 bulan
                                    }
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts4.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts4.settings.y_min), // Misalnya 90
                                max: Number(charts4.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts4.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts4.mayor === 0) {
                                            return value;
                                        } else if ((value - charts4.minor) % charts4.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        return '#ccc';
                                        const value = context.tick.value;

                                        if (value % charts4.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts4.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts4.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts4.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts4.settings.y_min), // Misalnya 90
                                max: Number(charts4.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts4.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts4.mayor === 0) {
                                            return value;
                                        } else if ((value - charts4.minor) % charts4.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts4.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts4.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan mayor
                                        } else if (value % charts4.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan minor
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 5
            if (arrayTable5.length != 0) {
                const charts5 = charts.find(c => c.id === 'chart-table-5');
                const totalDayTable5 = kurvaData.table5.length - 1;
                const dayInYearTable5 = Math.round(1825 / 5);
                const chartTable5 = new Chart(charts5.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable5,
                        datasets: datasetsTable5
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts5.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const day = labelsTable5[index];

                                        // Jika day adalah 0, tampilkan "0"
                                        if (day === 0) {
                                            return '0';
                                        }

                                        // Hitung tahun berdasarkan kelipatan 360 hari
                                        const tahun = Math.round(day /
                                            dayInYearTable5); // Tahun ke-1, ke-2, dst.
                                        const sisaHari = Math.round(day %
                                            dayInYearTable5); // Sisa hari dalam tahun tersebut
                                        // Tampilkan label untuk 2, 4, 6, 8, 10 dalam setiap tahun (dalam bulan, 1 bulan = 60 hari)
                                        if (day && sisaHari === 0) {
                                            return `${tahun} tahun`; // Tampilkan "1 tahun", "2 tahun", dst. pada kelipatan 360
                                        } else if (day && ((Math.abs(day % (dayInYearTable5 / 6)) <
                                                    1) ||
                                                sisaHari === (
                                                    Math
                                                    .round(dayInYearTable5 / 3)) || sisaHari === (Math
                                                    .round(
                                                        dayInYearTable5 /
                                                        2)) ||
                                                sisaHari === (Math.round(dayInYearTable5 / 1.5)) ||
                                                sisaHari === (Math
                                                    .round(
                                                        dayInYearTable5 /
                                                        1.2)))) {
                                            const bulan = Math.round(sisaHari / (Math.round(
                                                    dayInYearTable5 / 6)) *
                                                2); // Konversi ke bulan (2, 4, 6, 8, 10)
                                            if (bulan === 0) {
                                                return '';
                                            } else if (tahun === 1) {
                                                return `${bulan}`; // Hanya angka untuk tahun pertama
                                            } else {
                                                return `${bulan}`; // Angka + tahun untuk tahun berikutnya
                                            }
                                        }

                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    font: (context) => {
                                        const day = labelsTable5[context.index];
                                        if (day % dayInYearTable5 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const day = labelsTable5[context.index];
                                        // Garis tebal untuk kelipatan 2 bulan (60 hari)
                                        if (day % 360 === 0) {
                                            return '';
                                        }
                                        if (day && day % (Math.round(dayInYearTable5)) === 0) {
                                            return 'black';
                                        }
                                        if (day && (Math.abs(day % (dayInYearTable5 / 6)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis tebal untuk kelipatan 1 bulan (30 atau 31 hari), kecuali jika sudah kelipatan 60
                                        else if (day && (Math.abs(day % (dayInYearTable5 / 12)) < 1)) {
                                            return 'black';
                                        }
                                        // Garis default untuk lainnya
                                        return '#fff';
                                    },
                                    lineWidth: (context) => {
                                        const day = labelsTable5[context.index];
                                        return day && (day % Math.round(dayInYearTable5) === 0) ? 0.5 :
                                            0.2; // Lebih tebal setiap 2 bulan
                                    },
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts5.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts5.settings.y_min), // Misalnya 90
                                max: Number(charts5.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts5.stepSize, // Interval
                                    callback: (value, index) => {

                                        if (value % charts5.mayor === 0) {
                                            return value;
                                        } else if ((value - charts5.minor) % charts5.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts5.mayor === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts5.minor) < charts5
                                            .minor) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts5.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts5.minor) < charts5
                                            .minor) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts5.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts5.settings.y_min), // Misalnya 90
                                max: Number(charts5.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts5.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts5.mayor === 0) {
                                            return value;
                                        } else if ((value - charts5.minor) % charts5.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts5.mayor === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (Math.abs(value % charts5.minor) < charts5
                                            .minor) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts5.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (Math.abs(value % charts5.minor) < charts5
                                            .minor) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 6
            if (arrayTable6.length != 0) {
                const charts6 = charts.find(c => c.id === 'chart-table-6');
                const chartTable6 = new Chart(charts6.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable6,
                        datasets: datasetsTable6
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts6.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    padding: {
                                        top: -2 // Tambahkan jarak dari chart ke judul x-axis
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const month = labelsTable6[index];

                                        if (month && month % 12 === 0) {
                                            return `${month /12} tahun`;
                                        } else if (month && month % charts6.x_mayor === 0) {
                                            return `${month % 12}`;
                                        }

                                        return ''; // Kosong untuk nilai lain

                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    font: (context) => {
                                        const month = labelsTable6[context.index];
                                        if (month && month % 12 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const month = labelsTable6[context.index];
                                        return month && month % charts6.x_minor === 0 ? 'black' :
                                            '#fff'; // Garis lebih tebal setiap 2 bulan
                                    },
                                    lineWidth: (context) => {
                                        const month = labelsTable6[context.index];

                                        if (month && month % 12 === 0) {
                                            return 0.6
                                        } else if (month && month % charts6.x_mayor === 0) {
                                            return 0.4
                                        } else {
                                            return 0.2
                                        }
                                    },

                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts6.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts6.settings.y_min), // Misalnya 90
                                max: Number(charts6.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts6.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts6.mayor === 0) {
                                            return value;
                                        } else if ((value - charts6.minor) % charts6.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        return '#ccc';
                                        const value = context.tick.value;

                                        if (value % charts6.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts6.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts6.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts6.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts6.settings.y_min), // Misalnya 90
                                max: Number(charts6.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts6.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts6.mayor === 0) {
                                            return value;
                                        } else if ((value - charts6.minor) % charts6.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts6.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts6.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan mayor
                                        } else if (value % charts6.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan minor
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 7
            if (arrayTable7.length != 0) {
                const charts7 = charts.find(c => c.id === 'chart-table-7');
                const chartTable7 = new Chart(charts7.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable7,
                        datasets: datasetsTable7
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts7.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    padding: {
                                        top: 12 // Tambahkan jarak dari chart ke judul x-axis
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const month = labelsTable7[index];

                                        if (month && month % 12 === 0) {
                                            return `${month /12}`;
                                        } else if (month && month % charts7.x_mayor === 0) {
                                            return `${month % 12}`;
                                        }

                                        return ''; // Kosong untuk nilai lain

                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    padding: 10, // Tambahkan padding untuk memberi ruang lebih pada label
                                    font: (context) => {
                                        const month = labelsTable7[context.index];
                                        if (month && month % 12 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const month = labelsTable7[context.index];
                                        return month && month % charts7.x_mayor === 0 ? 'black' :
                                            '#fff'; // Garis lebih tebal setiap mayor bulan
                                    },
                                    lineWidth: (context) => {
                                        const month = labelsTable7[context.index];
                                        if (month && month % 12 === 0) {
                                            return 0.5
                                        } else if (month && month % charts7.x_mayor === 0) {
                                            return 0.2
                                        } else {
                                            return 0.2
                                        }
                                    }
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts7.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts7.settings.y_min), // Misalnya 90
                                max: Number(charts7.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts7.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts7.mayor === 0) {
                                            return value;
                                        } else if ((value - charts7.minor) % charts7.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        return '#ccc';
                                        const value = context.tick.value;

                                        if (value % charts7.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts7.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts7.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts7.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts7.settings.y_min), // Misalnya 90
                                max: Number(charts7.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts7.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts7.mayor === 0) {
                                            return value;
                                        } else if ((value - charts7.minor) % charts7.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts7.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts7.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts7.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Table 8
            if (arrayTable8.length != 0) {
                const charts8 = charts.find(c => c.id === 'chart-table-8');
                const chartTable8 = new Chart(charts8.ctx, {
                    type: 'line',
                    data: {
                        labels: labelsTable8,
                        datasets: datasetsTable8
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 0,
                                bottom: 10,
                                left: 0,
                                right: 0
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            customCanvasBackgroundColor: {
                                color: 'white',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: charts8.settings.ket_x,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    padding: {
                                        top: 12 // Tambahkan jarak dari chart ke judul x-axis
                                    }
                                },
                                ticks: {
                                    callback: (value, index) => {
                                        const month = labelsTable8[index];

                                        if (month && month % 12 === 0) {
                                            return `${month /12}`;
                                        } else if (month && month % charts8.x_mayor === 0) {
                                            return `${month % 12}`;
                                        }

                                        return ''; // Kosong untuk nilai lain

                                    },
                                    autoSkip: false, // Pastikan semua label dipertimbangkan
                                    padding: 10, // Tambahkan padding untuk memberi ruang lebih pada label
                                    font: (context) => {
                                        const month = labelsTable8[context.index];
                                        if (month && month % 12 === 0) {
                                            return {
                                                size: 12,
                                                weight: 'bold'
                                            }; // Label tahun lebih besar/tebal
                                        }
                                        return {
                                            size: 10
                                        }; // Label bulan normal
                                    }
                                },
                                grid: {
                                    drawTicks: false,
                                    color: (context) => {
                                        const month = labelsTable8[context.index];
                                        return month && month % charts8.x_mayor === 0 ? 'black' :
                                            '#fff'; // Garis lebih tebal setiap mayor bulan
                                    },
                                    lineWidth: (context) => {
                                        const month = labelsTable8[context.index];
                                        if (month && month % 12 === 0) {
                                            return 0.5
                                        } else if (month && month % charts8.x_mayor === 0) {
                                            return 0.2
                                        } else {
                                            return 0.2
                                        }
                                    }
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: charts8.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts8.settings.y_min), // Misalnya 90
                                max: Number(charts8.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts8.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts8.mayor === 0) {
                                            return value;
                                        } else if ((value - charts8.minor) % charts8.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts8.mayor === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (value % charts8.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts8.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts8.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: charts8.settings.ket_y,
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    }
                                },
                                min: Number(charts8.settings.y_min), // Misalnya 90
                                max: Number(charts8.settings.y_max), // Misalnya 200
                                ticks: {
                                    stepSize: charts8.stepSize, // Interval
                                    callback: (value, index) => {
                                        // Pastikan hanya menampilkan label pada kelipatan 5 dari min
                                        if (value % charts8.mayor === 0) {
                                            return value;
                                        } else if ((value - charts8.minor) % charts8.minor === 0) {
                                            return ''; // Tampilkan nilai pada kelipatan 5
                                        }
                                        return ''; // Kosong untuk nilai lain
                                    },
                                    autoSkip: false, // Matikan autoSkip agar semua label muncul
                                    padding: 5, // Tambahkan padding untuk ruang
                                    font: {
                                        size: 10 // Ukuran font konsisten
                                    }
                                },
                                grid: {
                                    color: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts8.mayor === 0) {
                                            return '#ccc'; // Garis hitam untuk kelipatan 10
                                        } else if (value % charts8.minor === 0) {
                                            return '#ccc'; // Garis abu-abu untuk kelipatan 5
                                        }
                                        return 'rgba(0, 0, 0, 0)'; // Tidak ada garis untuk lainnya
                                    },
                                    lineWidth: (context) => {
                                        const value = context.tick.value;
                                        if (value % charts8.mayor === 0) {
                                            return 1; // Tebal untuk kelipatan 10
                                        } else if (value % charts8.minor === 0) {
                                            return 0.5; // Tipis untuk kelipatan 5
                                        }
                                        return 0;
                                    },
                                    display: true
                                }
                            }
                        }
                    },
                    plugins: [plugin, zScoreLabelPlugin,
                        footerPlugin
                    ] // Tambahkan plugin untuk menggambar label SD
                });
            }

            // Weeks For Table 9, 10, 11
            const weeks = [27, 31, 35, 39, 43, 47, 51, 55, 59, 63];

            // Chart 9

            if (arrayTable9.length != 0) {
                const charts9 = charts.find(c => c.id === 'chart-table-9');
                const chartTable9 = initializeChartIg({
                    ctx: charts9.ctx,
                    labels: labelsTable9,
                    datasets: datasetsTable9,
                    settings: charts9.settings,
                    xTickCallback: (value, index) => {
                        const days = labelsTable9[index];
                        if (days && days % 7 === 0) {
                            return (labelsTable9[index] / 7);
                        }

                        return '';
                    },
                    xGridColor: (context) => {
                        const days = labelsTable9[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 'black';
                        if (days && days % 7 === 0) return 'black';
                        if (days) return 'black';
                        return '#ccc';
                    },
                    xGridLineWidth: (context) => {
                        const days = labelsTable9[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 0.5;
                        if (days && days % 7 === 0) return 0.25;
                        if (days) return 0.15;
                        return 0.1;
                    },
                    stepSize: 0.1,
                    yMin: 0,
                    yMax: 12,
                    yTickCallback: (value) => {
                        if (value && value === 0) return '0';
                        if (value && isMultipleOf(value, "0.5")) return value;
                        return '';
                    },
                    yGridColor: (context) => {
                        const value = context.tick.value;
                        if (value && isMultipleOf(value, "0.5")) return '#ccc';
                        if (value && isMultipleOf(value, "0.1")) return '#ccc';
                        return '#ccc';
                    },
                    yGridLineWidth: (context) => {
                        const value = context.tick.value;
                        if (value && isMultipleOf(value, "0.5")) return 0.5;
                        if (value && isMultipleOf(value, "0.1")) return 0.25;
                        return 0;
                    },
                    plugins: [plugin, zScoreLabelPlugin, footerPlugin]
                });
            }

            // Chart 10
            if (arrayTable10.length != 0) {
                const charts10 = charts.find(c => c.id === 'chart-table-10');
                const chartTable10 = initializeChartIg({
                    ctx: charts10.ctx,
                    labels: labelsTable10,
                    datasets: datasetsTable10,
                    settings: charts10.settings,
                    xTickCallback: (value, index) => {
                        const days = labelsTable10[index];
                        if (days && days % 7 === 0) {
                            return (labelsTable10[index] / 7);
                        }

                        return '';
                    },
                    xGridColor: (context) => {
                        const days = labelsTable10[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 'black';
                        if (days && days % 7 === 0) return 'black';
                        if (days) return 'black';
                        return '#ccc';
                    },
                    xGridLineWidth: (context) => {
                        const days = labelsTable10[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 0.5;
                        if (days && days % 7 === 0) return 0.25;
                        if (days) return 0.15;
                        return 0.1;
                    },
                    stepSize: 0.2,
                    yMin: 26,
                    yMax: 76,
                    yTickCallback: (value) => {
                        if (value === 0) return '0';
                        if (value % 2 === 0) return value;
                        return '';
                    },
                    yGridColor: (context) => {
                        const value = context.tick.value;
                        if (value % 1 === 0) return "#ccc";
                        if (value && isMultipleOf(length, "0.5")) return '#ccc';
                        if (value && isMultipleOf(length, "0.2")) return '#ccc';
                        return '#fff';
                    },
                    yGridLineWidth: (context) => {
                        const value = context.tick.value;
                        if (value % 1 === 0) return 0.5;
                        if (value && isMultipleOf(length, "0.5")) return 0.5;
                        if (value && isMultipleOf(length, "0.2")) return 0.2;
                        return 0;
                    },
                    plugins: [plugin, zScoreLabelPlugin, footerPlugin]
                });
            }

            // Chart 11
            if (arrayTable11.length != 0) {
                const charts11 = charts.find(c => c.id === 'chart-table-11');
                const chartTable11 = initializeChartIg({
                    ctx: charts11.ctx,
                    labels: labelsTable11,
                    datasets: datasetsTable11,
                    settings: charts11.settings,
                    xTickCallback: (value, index) => {
                        const days = labelsTable11[index];
                        if (days && days % 7 === 0) {
                            return (labelsTable11[index] / 7);
                        }

                        return '';
                    },
                    xGridColor: (context) => {
                        const days = labelsTable11[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 'black';
                        if (days && days % 7 === 0) return 'black';
                        if (days) return 'black';
                        return '#ccc';
                    },
                    xGridLineWidth: (context) => {
                        const days = labelsTable11[context.index];
                        if (days && days % 7 === 0 && weeks.includes(days / 7)) return 0.5;
                        if (days && days % 7 === 0) return 0.25;
                        if (days) return 0.15;
                        return 0.1;
                    },
                    stepSize: 0.2,
                    yMin: 19,
                    yMax: 48,
                    yTickCallback: (value) => {
                        if (value === 0) return '0';
                        if (value % 1 === 0) return value;
                        return '';
                    },
                    yGridColor: (context) => {
                        const value = context.tick.value;
                        if (isMultipleOf(length, "0.2")) return '#ccc';
                        return '#ccc';
                    },
                    yGridLineWidth: (context) => {
                        const value = context.tick.value;
                        if (value % 1 === 0) return 0.5;
                        if (isMultipleOf(length, "0.2")) return 0.2;
                        return 0;
                    },
                    plugins: [plugin, zScoreLabelPlugin, footerPlugin]
                });
            }

            // Chart 12
            if (arrayTable12.length != 0) {
                const charts12 = charts.find(c => c.id === 'chart-table-12');
                const chartTable12 = initializeChartIg({
                    ctx: charts12.ctx,
                    labels: labelsTable12,
                    datasets: datasetsTable12,
                    settings: charts12.settings,
                    xTickCallback: (value, index) => {
                        const length = Number(labelsTable12[index]).toFixed(1);
                        if (length && length % 1 === 0 && length != 0) return parseFloat(length);
                        return '';
                    },
                    xGridColor: (context) => {
                        const length = labelsTable12[context.index];
                        if (length && isMultipleOf(length, 1)) return 'black';
                        if (length && isMultipleOf(length, "0.2")) return 'black';
                        return '#ccc';
                    },

                    xGridLineWidth: (context) => {
                        const length = labelsTable12[context.index];
                        if (length && isMultipleOf(length, 1)) return 0.5;
                        if (length && isMultipleOf(length, "0.2")) return 0.2;
                        return 0;
                    },
                    stepSize: 0.1,
                    yMin: 0,
                    yMax: 14,
                    yTickCallback: (value) => {
                        if (value === 0) return '0';
                        if (value % 1 === 0) return value;
                        return '';
                    },
                    yGridColor: (context) => {
                        const value = context.tick.value;
                        if (value % 1 === 0) return '#ccc';
                        if (value % isMultipleOf(value, "0.5")) return '#ccc';
                        if (value && isMultipleOf(value, "0.1")) return '#ccc';
                        return '#fff';
                    },
                    yGridLineWidth: (context) => {
                        const value = context.tick.value;
                        if (value % 1 === 0) return 0.5;
                        if (value % isMultipleOf(value, "0.5")) return 0.5;
                        if (value && isMultipleOf(value, "0.1")) return 0.3;
                        return 0;
                    },
                    plugins: [plugin, zScoreLabelPlugin, footerPlugin]
                });
            }
        })
    </script>
@endsection
