@extends('layouts.tailadmin')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6">
            <form action="{{ route('testimoni.update', $testimoni->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="-mx-2.5 flex flex-wrap gap-y-5">
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
                        <x-textarea placeholder="Masukkan Testimoni" name="testimoni"
                            required>{{ $testimoni->testimoni }}</x-textarea>
                        <x-input-error :messages="$errors->get('testimoni')" />
                    </div>

                    <div class="w-full px-2.5">
                        <div class="flex items-center gap-3 mt-1">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                                Update
                            </button>
                            <a href="{{ route('testimoni.index') }}"
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
            Alpine.data("ratingSystem", (initialRating = 0) => ({
                rating: initialRating,
                setRating(value) {
                    this.rating = value;
                }
            }));
        });
    </script>
@endsection
