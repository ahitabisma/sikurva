@extends('layouts.tailadmin')

@section('content')
    {{-- Metric --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 md:gap-6">
        <!-- Metric Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                <svg class="fill-gray-800" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.8937 18.2112C13.9797 18.1175 14.017 18.0034 13.9838 17.8611C13.7971 17.0608 13.4132 16.0881 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208ZM3.8071 14.2549C4.87163 13.2009 6.45602 12.455 8.75042 12.455C11.0448 12.455 12.6292 13.2009 13.6937 14.2549C14.7397 15.2906 15.2207 16.5607 15.4446 17.5202C15.7658 18.8971 14.6071 19.8987 13.4249 19.8987H4.07591C2.89369 19.8987 1.73504 18.8971 2.05628 17.5202C2.28015 16.5607 2.76117 15.2906 3.8071 14.2549ZM15.3042 11.4955C14.4702 11.4955 13.7006 11.2193 13.0821 10.7533C13.3742 10.3314 13.6054 9.86419 13.7632 9.36432C14.1597 9.75463 14.7039 9.99545 15.3042 9.99545C16.5176 9.99545 17.5012 9.01185 17.5012 7.79851C17.5012 6.58517 16.5176 5.60156 15.3042 5.60156C14.7039 5.60156 14.1597 5.84239 13.7632 6.23271C13.6054 5.73284 13.3741 5.26561 13.082 4.84371C13.7006 4.37777 14.4702 4.10156 15.3042 4.10156C17.346 4.10156 19.0012 5.75674 19.0012 7.79851C19.0012 9.84027 17.346 11.4955 15.3042 11.4955ZM19.9248 19.8987H16.3901C16.7014 19.4736 16.9159 18.969 16.9827 18.3987H19.9248C20.1341 18.3987 20.2991 18.3141 20.3936 18.2112C20.4796 18.1175 20.5169 18.0034 20.4837 17.861C20.2969 17.0607 19.913 16.088 19.1382 15.3208C18.4047 14.5945 17.261 13.9921 15.4231 13.9566C15.2232 13.6945 14.9995 13.437 14.7491 13.1891C14.5144 12.9566 14.262 12.7384 13.9916 12.5362C14.3853 12.4831 14.8044 12.4549 15.2503 12.4549C17.5447 12.4549 19.1291 13.2008 20.1936 14.2549C21.2395 15.2906 21.7206 16.5607 21.9444 17.5202C22.2657 18.8971 21.107 19.8987 19.9248 19.8987Z"
                        fill="" />
                </svg>
            </div>

            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Total Pasien</span>
                    <h4 class="mt-2 text-title-sm font-bold text-gray-800">
                        {{ $metrics['patients'] }}
                    </h4>
                </div>
            </div>
        </div>
        <!-- Metric Item End -->

        <!-- Metric Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                <svg class="fill-gray-800" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.8937 18.2112C13.9797 18.1175 14.017 18.0034 13.9838 17.8611C13.7971 17.0608 13.4132 16.0881 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208ZM3.8071 14.2549C4.87163 13.2009 6.45602 12.455 8.75042 12.455C11.0448 12.455 12.6292 13.2009 13.6937 14.2549C14.7397 15.2906 15.2207 16.5607 15.4446 17.5202C15.7658 18.8971 14.6071 19.8987 13.4249 19.8987H4.07591C2.89369 19.8987 1.73504 18.8971 2.05628 17.5202C2.28015 16.5607 2.76117 15.2906 3.8071 14.2549ZM15.3042 11.4955C14.4702 11.4955 13.7006 11.2193 13.0821 10.7533C13.3742 10.3314 13.6054 9.86419 13.7632 9.36432C14.1597 9.75463 14.7039 9.99545 15.3042 9.99545C16.5176 9.99545 17.5012 9.01185 17.5012 7.79851C17.5012 6.58517 16.5176 5.60156 15.3042 5.60156C14.7039 5.60156 14.1597 5.84239 13.7632 6.23271C13.6054 5.73284 13.3741 5.26561 13.082 4.84371C13.7006 4.37777 14.4702 4.10156 15.3042 4.10156C17.346 4.10156 19.0012 5.75674 19.0012 7.79851C19.0012 9.84027 17.346 11.4955 15.3042 11.4955ZM19.9248 19.8987H16.3901C16.7014 19.4736 16.9159 18.969 16.9827 18.3987H19.9248C20.1341 18.3987 20.2991 18.3141 20.3936 18.2112C20.4796 18.1175 20.5169 18.0034 20.4837 17.861C20.2969 17.0607 19.913 16.088 19.1382 15.3208C18.4047 14.5945 17.261 13.9921 15.4231 13.9566C15.2232 13.6945 14.9995 13.437 14.7491 13.1891C14.5144 12.9566 14.262 12.7384 13.9916 12.5362C14.3853 12.4831 14.8044 12.4549 15.2503 12.4549C17.5447 12.4549 19.1291 13.2008 20.1936 14.2549C21.2395 15.2906 21.7206 16.5607 21.9444 17.5202C22.2657 18.8971 21.107 19.8987 19.9248 19.8987Z"
                        fill="" />
                </svg>
            </div>

            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Total Generate PDF</span>
                    <h4 class="mt-2 text-title-sm font-bold text-gray-800">
                        {{ $metrics['total_generate_pdf'] }}
                    </h4>
                </div>
            </div>
        </div>
        <!-- Metric Item End -->

        <!-- Metric Item Start -->
        @if (Auth::user()->is_nakes)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                    <svg class="fill-gray-800" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.665 3.75621C11.8762 3.65064 12.1247 3.65064 12.3358 3.75621L18.7807 6.97856L12.3358 10.2009C12.1247 10.3065 11.8762 10.3065 11.665 10.2009L5.22014 6.97856L11.665 3.75621ZM4.29297 8.19203V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0366V11.6513C11.1631 11.6205 11.0777 11.5843 10.9942 11.5426L4.29297 8.19203ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19202L13.0066 11.5426C12.9229 11.5844 12.8372 11.6208 12.75 11.6516V20.037ZM13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857L11.3296 20.9149L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z"
                            fill="" />
                    </svg>
                </div>

                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500">Total Generate Penilaian</span>
                        <h4 class="mt-2 text-title-sm font-bold text-gray-800">
                            {{ $metrics['total_generate_penilaian'] }}
                        </h4>
                    </div>
                </div>
            </div>
        @endif
        <!-- Metric Item End -->
    </div>

    {{-- Last Activity --}}
    <div class="rounded-2xl border border-gray-200 bg-white mt-5">
        <div class="border-t border-gray-100 p-5 sm:p-6">
            <!-- Table Four -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h6 class="font-bold text-gray-800">Aktivitas Terakhir</h6>
                    </div>

                    <div>
                        <div class="relative w-fit">
                            <input id="date-range"
                                class="datepicker h-10 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-[34px] pr-4 text-theme-sm font-medium text-gray-700 shadow-theme-xs focus:outline-hidden focus:ring-0 focus-visible:outline-hidden xl:max-w-fit xl:pl-11"
                                placeholder="Filter tanggal" data-class="flatpickr-right" readonly="readonly" />
                            <div class="absolute inset-0 right-auto flex items-center pointer-events-none left-4">
                                <svg class="fill-gray-700" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.66683 1.54199C7.08104 1.54199 7.41683 1.87778 7.41683 2.29199V3.00033H12.5835V2.29199C12.5835 1.87778 12.9193 1.54199 13.3335 1.54199C13.7477 1.54199 14.0835 1.87778 14.0835 2.29199V3.00033L15.4168 3.00033C16.5214 3.00033 17.4168 3.89576 17.4168 5.00033V7.50033V15.8337C17.4168 16.9382 16.5214 17.8337 15.4168 17.8337H4.5835C3.47893 17.8337 2.5835 16.9382 2.5835 15.8337V7.50033V5.00033C2.5835 3.89576 3.47893 3.00033 4.5835 3.00033L5.91683 3.00033V2.29199C5.91683 1.87778 6.25262 1.54199 6.66683 1.54199ZM6.66683 4.50033H4.5835C4.30735 4.50033 4.0835 4.72418 4.0835 5.00033V6.75033H15.9168V5.00033C15.9168 4.72418 15.693 4.50033 15.4168 4.50033H13.3335H6.66683ZM15.9168 8.25033H4.0835V15.8337C4.0835 16.1098 4.30735 16.3337 4.5835 16.3337H15.4168C15.693 16.3337 15.9168 16.1098 15.9168 15.8337V8.25033Z"
                                        fill="" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead class="border-gray-100 border-y bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            No
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Deskripsi
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Points
                                        </p>
                                    </div>
                                </th>
                                <th class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <p class="font-medium text-gray-500 text-theme-xs">
                                            Tipe
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <!-- table header end -->

                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($lastActivities as $lastActivity)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-theme-xs  rounded-full px-2 py-0.5 font-medium">
                                                {{ ($lastActivities->currentPage() - 1) * $lastActivities->perPage() + $loop->iteration }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p class="text-gray-700 text-theme-sm">
                                                {{ $lastActivity->description }}
                                                {{ $lastActivity->patient_name ? '(' . $lastActivity->patient_name . ')' : '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <p
                                                class="text-theme-xs rounded-full px-2 py-0.5 font-medium {{ $lastActivity->type === 'usage' ? 'text-error-500' : 'text-success-500' }}">
                                                {{ $lastActivity->type === 'usage' ? '-' : '+' }}{{ $lastActivity->points }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            @if ($lastActivity->type == 'bonus')
                                                <p
                                                    class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600">
                                                    {{ $lastActivity->type }}
                                                </p>
                                            @elseif($lastActivity->type == 'referral')
                                                <p
                                                    class="rounded-full bg-warning-50 px-2 py-0.5 text-theme-xs font-medium text-warning-600">
                                                    {{ $lastActivity->type }}
                                                </p>
                                            @elseif($lastActivity->type == 'referrer')
                                                <p
                                                    class="rounded-full bg-gray-50 px-2 py-0.5 text-theme-xs font-medium text-gray-600">
                                                    {{ $lastActivity->type }}
                                                </p>
                                            @elseif($lastActivity->type == 'purchase')
                                                <p
                                                    class="rounded-full bg-brand-50 px-2 py-0.5 text-theme-xs font-medium text-brand-600">
                                                    {{ $lastActivity->type }}
                                                </p>
                                            @elseif($lastActivity->type == 'usage')
                                                <p
                                                    class="rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600">
                                                    {{ $lastActivity->type }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-3 whitespace-nowrap">
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

                <div class="border-t border-gray-200 px-6 py-4">
                    {!! $lastActivities->links() !!}
                </div>
            </div>
            <!-- Table Four -->
        </div>
    </div>
    {{-- Last Activity --}}
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize flatpickr with range mode
            const dateRangePicker = flatpickr("#date-range", {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: ["{{ $startDate }}", "{{ $endDate }}"],
                altInput: true,
                altFormat: "d M Y",
                locale: {
                    firstDayOfWeek: 0,
                    weekdays: {
                        shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                        longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                    },
                    months: {
                        shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt",
                            "Nov", "Des"
                        ],
                        longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                            "Agustus", "September", "Oktober", "November", "Desember"
                        ]
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        // Get start and end dates
                        const startDate = formatDate(selectedDates[0]);
                        const endDate = formatDate(selectedDates[1]);

                        // Redirect with date range parameters
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('start_date', startDate);
                        currentUrl.searchParams.set('end_date', endDate);
                        window.location.href = currentUrl.toString();
                    }
                }
            });

            // Format date to YYYY-MM-DD
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Set initial values if they exist in URL
            const urlParams = new URLSearchParams(window.location.search);
            const startDate = urlParams.get('start_date');
            const endDate = urlParams.get('end_date');

            if (startDate && endDate) {
                dateRangePicker.setDate([startDate, endDate]);
            }
        });
    </script>
@endsection
