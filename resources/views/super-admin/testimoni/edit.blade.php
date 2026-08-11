@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('super-admin.testimoni.update', $testimoni->id) }}" method="POST" x-data="userSelect({{ $testimoni->user_id }}, '{{ $testimoni->user->name }}', true)">
                @csrf
                @method('PUT')

                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="user" :value="__('Pilih User')" required />

                        <div class="relative z-20 bg-transparent">
                            <input type="text"
                                class="w-full px-4 py-3 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-brand-300 focus:outline-none"
                                placeholder="Cari user..." x-model="query" :disabled="isEditMode" />

                            <div class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-md"
                                x-show="open && !isEditMode" @click.away="open = false">
                                <template x-for="user in users" :key="user.id">
                                    <div class="p-2 cursor-pointer hover:bg-gray-100" @click="selectUser(user)">
                                        <span x-text="user.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <input type="hidden" name="user" x-model="selectedUser.id">
                        <x-input-error :messages="$errors->get('user')" />
                    </div>

                    <!-- Rating Bintang -->
                    <div class="w-full px-2.5 xl:w-full" x-data="ratingSystem({{ $testimoni->rating }})">
                        <x-input-label for="rating" :value="__('Rating')" required />
                        <div class="flex space-x-2 mt-2">
                            <template x-for="star in 5" :key="star">
                                <svg class="w-8 h-8 cursor-pointer"
                                    :class="{ 'text-yellow-400': star <= rating, 'text-gray-300': star > rating }"
                                    fill="currentColor" viewBox="0 0 20 20" @click="setRating(star)">
                                    <path
                                        d="M10 2l2.39 4.85L18 7.62l-3.9 3.8L14.78 17 10 14.27 5.22 17l.68-5.58L2 7.62l5.61-.77L10 2z" />
                                </svg>
                            </template>
                        </div>
                        <input type="hidden" name="rating" x-model="rating">
                        <x-input-error :messages="$errors->get('rating')" />
                    </div>

                    <div class="w-full px-2.5 xl:w-full">
                        <x-input-label for="testimoni" :value="__('Testimoni')" required />
                        <x-textarea placeholder="Masukkan Testimoni" name="testimoni" :rows="5"
                            required>{{ $testimoni->testimoni }}</x-textarea>
                        <x-input-error :messages="$errors->get('testimoni')" />
                    </div>

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Simpan
                            </button>
                            <a href="{{ route('super-admin.testimoni.index') }}"
                                class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                                Cancel
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
        document.addEventListener("alpine:init", () => {
            Alpine.data("userSelect", (selectedUserId = null, selectedUserName = '', isEditMode = false) => ({
                query: selectedUserName,
                users: [],
                selectedUser: {
                    id: selectedUserId,
                    name: selectedUserName
                },
                open: false,
                isEditMode: isEditMode,

                searchUsers() {
                    if (this.isEditMode || this.query.length < 2) {
                        this.users = [];
                        this.open = false;
                        return;
                    }

                    fetch("{{ route('super-admin.testimoni.search-users') }}?q=" + this.query)
                        .then(response => response.json())
                        .then(data => {
                            this.users = data;
                            this.open = this.users.length > 0;
                        })
                        .catch(error => console.error('Error fetching users:', error));
                },

                selectUser(user) {
                    if (this.isEditMode) return;
                    this.selectedUser = user;
                    this.query = user.name;
                    this.open = false;
                }
            }));

            // Alpine.js untuk rating bintang
            Alpine.data("ratingSystem", (initialRating = 0) => ({
                rating: initialRating,
                setRating(value) {
                    this.rating = value;
                }
            }));
        });
    </script>
@endsection
