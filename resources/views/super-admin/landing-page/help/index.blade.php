@extends('layouts.tailadmin')

@section('content')
    <div x-data="helpSettings">
        <form action="{{ route('super-admin.landing-page.help.update') }}" method="POST" id="help-form">
            @csrf
            <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5">
                <div class="pb-5 mb-5 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex gap-4">
                        <button type="button" @click="addHelpItem()"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-green-500 px-4 py-2.5 font-medium text-white hover:bg-green-700 transition ease-in-out duration-300">
                            + Tambah Menu
                        </button>
                        <button type="submit"
                            class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                            Simpan
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <template x-for="(help, index) in helpItems" :key="index">
                        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label :for="'title-' + index" class="block text-sm font-medium text-gray-700 mb-1">
                                        Title
                                    </label>
                                    <input :id="'title-' + index" x-model="help.title"
                                        :name="'helpItems[' + index + '][title]'"
                                        class="mt-1 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                        placeholder="Masukkan Title" />
                                    <template x-if="$store.errors && $store.errors['helpItems.' + index + '.title']">
                                        <p class="mt-1 text-sm text-red-500"
                                            x-text="$store.errors['helpItems.' + index + '.title'][0]"></p>
                                    </template>
                                </div>

                                <div>
                                    <label :for="'url-' + index" class="block text-sm font-medium text-gray-700 mb-1">
                                        URL
                                    </label>
                                    <input :id="'url-' + index" x-model="help.url" :name="'helpItems[' + index + '][url]'"
                                        class="mt-1 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                        placeholder="Masukkan URL" />
                                    <p class="text-xs text-gray-500 mt-1">Contoh url : https://www.who.int</p>
                                    <template x-if="$store.errors && $store.errors['helpItems.' + index + '.url']">
                                        <p class="mt-1 text-sm text-red-500"
                                            x-text="$store.errors['helpItems.' + index + '.url'][0]"></p>

                                    </template>
                                </div>
                            </div>

                            <input type="hidden" :name="'helpItems[' + index + '][id]'" x-model="help.id" />
                            <input type="hidden" :name="'helpItems[' + index + '][action]'" value="edit" />

                            <div class="mt-4 text-right">
                                <button type="button" @click="removeHelpItem(index, help.id)"
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                    Hapus Menu
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="helpItems.length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Belum ada item menu. Klik "Tambah Menu" untuk mulai menambahkan.</p>
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

            Alpine.data('helpSettings', () => ({
                helpItems: @json($helps) || [],
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

                addHelpItem() {
                    const tempId = -1 * (this.helpItems.length + 1);
                    this.helpItems.push({
                        id: tempId,
                        title: '',
                        url: ''
                    });
                },

                async removeHelpItem(index, helpId) {
                    if (helpId > 0) {
                        // Create a temporary form for deletion
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action =
                            `{{ route('super-admin.landing-page.help.destroy', ['id' => ':id']) }}`
                            .replace(':id', helpId);
                        form.style.display = 'none';

                        // Add CSRF token
                        const csrfField = document.createElement('input');
                        csrfField.type = 'hidden';
                        csrfField.name = '_token';
                        csrfField.value = '{{ csrf_token() }}';
                        form.appendChild(csrfField);

                        // Add method spoofing for DELETE
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        // Add the form to the document and submit it
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Just remove from array if it's a new item
                        this.helpItems.splice(index, 1);
                    }
                }
            }));
        });
    </script>
@endsection
