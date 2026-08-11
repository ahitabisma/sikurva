@extends('layouts.tailadmin')

@section('content')
    <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5" x-data="userLimitSettings">
        <form action="{{ route('super-admin.setting.user.update') }}" method="POST" id="user-setting-form">
            @csrf

            <div class="pb-5 mb-5 border-b border-gray-200">
                <button type="submit"
                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan
                </button>
            </div>

            <!-- User Limit Settings -->
            <div class="mb-6">
                <div class="space-y-4">
                    <template x-for="(setting, index) in userSettings" :key="index">
                        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label :for="'input-' + setting.key" class="text-sm font-medium block"
                                            x-text="getSettingName(setting.key)"></label>
                                    </div>
                                    <div class="mt-1 relative">
                                        <input type="number" :id="'input-' + setting.key"
                                            :name="'userSettings[' + index + '][value]'" x-model="setting.value"
                                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                            :placeholder="'Masukkan ' + getSettingName(setting.key)" min="1">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex gap-2">
                                            <button type="button" @click="clearSettingValue(index, setting.id)"
                                                class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <span x-show="setting.key === 'max_patients_admin_awam'">
                                            Jika nilai tidak diisi, maka tidak ada batasan untuk admin awam untuk menambah
                                            pasien baru.
                                        </span>
                                        <span x-show="setting.key === 'max_collab_admin_nakes'">
                                            Jika tidak diisi, maka default maksimal collaborator admin nakes adalah 3.
                                        </span>
                                    </p>
                                    <template x-if="$store.errors && $store.errors['userSettings.' + index + '.value']">
                                        <p class="mt-1 text-xs text-red-500"
                                            x-text="$store.errors['userSettings.' + index + '.value'][0]"></p>
                                    </template>
                                </div>
                            </div>

                            <input type="hidden" :name="'userSettings[' + index + '][id]'" x-model="setting.id" />
                            <input type="hidden" :name="'userSettings[' + index + '][key]'" x-model="setting.key" />
                        </div>
                    </template>

                    <div x-show="userSettings.length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Batasan pengguna belum dikonfigurasi.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('errors', @json($errors->messages()));

            Alpine.data('userLimitSettings', () => ({
                userSettings: @json($settings) || [],
                fixedSettings: @json($fixedSettings),
                notification: {
                    type: null,
                    message: null
                },

                init() {
                    // Show success message if exists in session
                    @if (session('success'))
                        this.notification = {
                            type: 'success',
                            message: '{{ session('success') }}'
                        };
                        setTimeout(() => {
                            this.notification.message = null;
                        }, 3000);
                    @endif

                    // Show error message if exists in session
                    @if (session('error'))
                        this.notification = {
                            type: 'error',
                            message: '{{ session('error') }}'
                        };
                        setTimeout(() => {
                            this.notification.message = null;
                        }, 3000);
                    @endif
                },

                getSettingName(key) {
                    return this.fixedSettings[key] || key;
                },

                clearSettingValue(index, settingId) {
                    if (settingId > 0) {
                        // For existing settings, redirect to clear route
                        window.location.href =
                            "{{ route('super-admin.setting.user.clear-value', ['id' => ':id']) }}"
                            .replace(':id', settingId);
                    } else {
                        // For new settings just clear the value in the form
                        this.userSettings[index].value = '';
                    }
                }
            }));
        });
    </script>
@endsection
