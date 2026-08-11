<!-- resources/views/super-admin/landing-page/service/index.blade.php -->
@extends('layouts.tailadmin')

@section('content')
    <div class="mt-2">
        <div x-data="serviceSettings">
            <!-- Notification -->
            <div x-show="notification.message" class="mb-3">
                <template x-if="notification.type === 'success'">
                    <div class="relative flex items-center justify-between gap-3 w-full rounded-md bg-white p-3 shadow-theme-sm overflow-hidden"
                        x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        <div class="absolute bottom-0 left-0 h-1 bg-success-500 animate-progress" x-show="show"></div>
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg text-success-600 bg-success-50">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.55078 12C3.55078 7.33417 7.3332 3.55176 11.999 3.55176C16.6649 3.55176 20.4473 7.33417 20.4473 12C20.4473 16.6659 16.6649 20.4483 11.999 20.4483C7.3332 20.4483 3.55078 16.6659 3.55078 12ZM11.999 2.05176C6.50477 2.05176 2.05078 6.50574 2.05078 12C2.05078 17.4943 6.50477 21.9483 11.999 21.9483C17.4933 21.9483 21.9473 17.4943 21.9473 12C21.9473 6.50574 17.4933 2.05176 11.999 2.05176ZM15.5126 10.6333C15.8055 10.3405 15.8055 9.86558 15.5126 9.57269C15.2197 9.27979 14.7448 9.27979 14.4519 9.57269L11.1883 12.8364L9.54616 11.1942C9.25327 10.9014 8.7784 10.9014 8.4855 11.1942C8.19261 11.4871 8.19261 11.962 8.4855 12.2549L10.6579 14.4273C10.7986 14.568 10.9894 14.647 11.1883 14.647C11.3872 14.647 11.578 14.568 11.7186 14.4273L15.5126 10.6333Z"
                                        fill=""></path>
                                </svg>
                            </div>
                            <h4 class="sm:text-base text-sm text-gray-800" x-text="notification.message"></h4>
                        </div>
                        <button class="text-gray-400 hover:text-gray-800" @click="show = false">
                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                                    fill=""></path>
                            </svg>
                        </button>
                    </div>
                </template>
                <template x-if="notification.type === 'error'">
                    <div class="relative flex items-center justify-between gap-3 w-full rounded-md bg-white p-3 shadow-theme-sm overflow-hidden"
                        x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        <div class="absolute bottom-0 left-0 h-1 bg-error-500 animate-progress" x-show="show"></div>
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-error-50 text-error-600">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.12454 4.53906L15.8736 4.53906C16.1416 4.53906 16.3892 4.68201 16.5231 4.91406L20.3977 11.625C20.5317 11.857 20.5317 12.1429 20.3977 12.375L16.5231 19.0859C16.3892 19.3179 16.1416 19.4609 15.8736 19.4609H8.12454C7.85659 19.4609 7.609 19.3179 7.47502 19.0859L3.60048 12.375C3.46651 12.1429 3.46651 11.857 3.60048 11.625L7.47502 4.91406C7.609 4.68201 7.85659 4.53906 8.12454 4.53906ZM15.8736 3.03906H8.12454C7.3207 3.03906 6.57791 3.46791 6.17599 4.16406L2.30144 10.875C1.89952 11.5711 1.89952 12.4288 2.30144 13.125L6.17599 19.8359C6.57791 20.532 7.32069 20.9609 8.12454 20.9609H15.8736C16.6775 20.9609 17.4203 20.532 17.8222 19.8359L21.6967 13.125C22.0987 12.4288 22.0987 11.5711 21.6967 10.875L17.8222 4.16406C17.4203 3.46791 16.6775 3.03906 15.8736 3.03906ZM12.0007 7.81075C12.4149 7.81075 12.7507 8.14653 12.7507 8.56075V12.7803C12.7507 13.1945 12.4149 13.5303 12.0007 13.5303C11.5865 13.5303 11.2507 13.1945 11.2507 12.7803V8.56075C11.2507 8.14653 11.5865 7.81075 12.0007 7.81075ZM10.9998 15.3303C10.9998 14.778 11.4475 14.3303 11.9998 14.3303H12.0005C12.5528 14.3303 13.0005 14.778 13.0005 15.3303C13.0005 15.8826 12.5528 16.3303 12.0005 16.3303H11.9998C11.4475 16.3303 10.9998 15.8826 10.9998 15.3303Z"
                                        fill=""></path>
                                </svg>
                            </div>
                            <h4 class="sm:text-base text-sm text-gray-800" x-text="notification.message"></h4>
                        </div>
                        <button class="text-gray-400 hover:text-gray-800" @click="show = false">
                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                                    fill=""></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            <form action="{{ route('super-admin.landing-page.layanan.update') }}" method="POST"
                enctype="multipart/form-data" id="service-form">
                @csrf
                <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between pb-5 mb-5 border-b border-gray-200">
                        <div>
                            <button type="button" @click="handleAddService()"
                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-green-500 px-4 py-2.5 font-medium text-white hover:bg-green-700 transition ease-in-out duration-300">
                                + Tambah Layanan
                            </button>
                            {{-- <button type="submit"
                                class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                                Simpan
                            </button> --}}
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(layanan, index) in layanans" :key="index">
                            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                                <div class="flex flex-col md:flex-row">
                                    <div class="p-4 border-b md:border-r md:border-b-0 border-gray-200 md:w-1/3 bg-gray-50">
                                        <div class="text-center">
                                            <label class="mb-2 text-sm font-medium">Icon</label>
                                            <div class="mt-2 mb-4">
                                                <div
                                                    class="overflow-hidden bg-white border border-gray-200 rounded-md w-24 h-24 mx-auto">
                                                    <img :src="layanan.image" :alt="layanan.title"
                                                        class="object-cover w-full h-full">
                                                </div>
                                            </div>
                                            <input type="file" :name="'layanans[' + index + '][icon_file]'"
                                                :id="'service-icon-' + index" accept="image/*"
                                                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                                                @change="handleIconUpload(index, $event)" />
                                            <input type="hidden" :name="'layanans[' + index + '][id]'"
                                                x-model="layanan.id" />
                                            <input type="hidden" :name="'layanans[' + index + '][image]'"
                                                x-model="layanan.image" />

                                            {{-- Notes --}}
                                            <div class="mt-1 w-full text-left">
                                                <p class="text-xs text-gray-500">
                                                    Maksimal ukuran file 2MB, format gambar: JPG, JPEG, PNG.<br>
                                                    Ukuran gambar yang disarankan: 512 x 512 px, atau
                                                    ukuran
                                                    lain dengan rasio 1:1.
                                                </p>
                                            </div>
                                            <template
                                                x-if="$store.errors && $store.errors['layanans.' + index + '.icon_file']">
                                                <p class="mt-1 text-sm text-red-500"
                                                    x-text="$store.errors['layanans.' + index + '.icon_file'][0]"></p>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="p-6 md:w-2/3">
                                        <div class="flex items-start justify-between mb-4">
                                            <label :for="'title-' + index" class="text-sm font-medium">
                                                Title
                                            </label>
                                            <button @click="handleRemoveService(index, layanan.id)" type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-500 rounded-md hover:text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M3 7h18" />
                                                </svg>
                                            </button>
                                        </div>

                                        <input :id="'title-' + index" x-model="layanan.title"
                                            :name="'layanans[' + index + '][title]'"
                                            class="mt-1 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10">
                                        <template x-if="$store.errors && $store.errors['layanans.' + index + '.title']">
                                            <p class="mt-1 text-sm text-red-500"
                                                x-text="$store.errors['layanans.' + index + '.title'][0]"></p>
                                        </template>

                                        <label :for="'description-' + index" class="text-sm font-medium">
                                            Description
                                        </label>
                                        <textarea :id="'description-' + index" x-model="layanan.description" :name="'layanans[' + index + '][description]'"
                                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10"
                                            rows="3"></textarea>
                                        <template
                                            x-if="$store.errors && $store.errors['layanans.' + index + '.description']">
                                            <p class="mt-1 text-sm text-red-500"
                                                x-text="$store.errors['layanans.' + index + '.description'][0]"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan
                        </button>
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

            Alpine.data('serviceSettings', () => ({
                layanans: @json($layanans) ?? [{
                    id: null,
                    image: '/placeholder.svg',
                    title: '',
                    description: ''
                }],
                isUploading: false,
                notification: {
                    type: null,
                    message: null
                },
                handleAddService() {
                    this.layanans.push({
                        id: null,
                        image: '/placeholder.svg',
                        title: 'New Service',
                        description: 'Description of your new service.'
                    });
                },
                async handleRemoveService(index, serviceId) {
                    if (this.layanans.length <= 1) {
                        this.notification = {
                            type: 'error',
                            message: 'Tidak bisa menghapus layanan karena minimal layanan berjumlah 1'
                        };
                        setTimeout(() => this.notification = {
                            type: null,
                            message: null
                        }, 3000);
                        return;
                    }

                    if (serviceId) {
                        try {
                            const response = await fetch(
                                `/super-admin/landing-page/layanan/destroy/${serviceId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });

                            const data = await response.json();
                            if (!response.ok) {
                                this.notification = {
                                    type: 'error',
                                    message: data.message || 'Gagal menghapus service'
                                };
                                setTimeout(() => this.notification = {
                                    type: null,
                                    message: null
                                }, 3000);
                                return;
                            }

                            this.notification = {
                                type: 'success',
                                message: data.message || 'Service berhasil dihapus!'
                            };
                            this.layanans.splice(index, 1);
                            setTimeout(() => this.notification = {
                                type: null,
                                message: null
                            }, 3000);
                        } catch (error) {
                            console.error('Error deleting service:', error);
                            this.notification = {
                                type: 'error',
                                message: 'Terjadi kesalahan saat menghapus service'
                            };
                            setTimeout(() => this.notification = {
                                type: null,
                                message: null
                            }, 3000);
                            return;
                        }
                    } else {
                        this.layanans.splice(index, 1);
                        this.notification = {
                            type: 'success',
                            message: 'Service removed'
                        };
                        setTimeout(() => this.notification = {
                            type: null,
                            message: null
                        }, 3000);
                    }
                },
                handleIconUpload(index, event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.isUploading = true;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.layanans[index].image = e.target.result;
                            this.isUploading = false;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                handleSave() {
                    document.getElementById('service-form').submit();
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
