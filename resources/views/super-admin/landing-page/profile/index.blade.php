@extends('layouts.tailadmin')

@section('content')
    <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5" x-data="profileSettings">
        <form action="{{ route('super-admin.landing-page.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="pb-5 mb-5 border-b border-gray-200">
                <button type="submit"
                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                    Simpan
                </button>
            </div>

            <div class="grid gap-8 md:grid-cols-2">
                <div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                        <div class="mb-4">
                            <label for="profile-image" class="mb-2 text-sm font-medium block">
                                Profile Photo
                            </label>
                            <div class="mt-1 mb-4">
                                <div
                                    class="overflow-hidden bg-gray-100 border border-gray-200 rounded-full w-32 h-32 mx-auto relative">
                                    <img :src="profileData.photo" alt="Profile" class="object-cover w-full h-full">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden"
                                    @change="handleImageUpload($event)" />
                            </div>

                            {{-- Notes --}}
                            <div class="mt-1">
                                <p class="text-xs text-gray-500">
                                    Maksimal ukuran file 2MB, format gambar: JPG, JPEG, PNG.<br>
                                    Ukuran gambar yang disarankan adalah ukuran
                                    gambar dengan rasio 1:1.
                                </p>
                            </div>

                            <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="text-sm font-medium block">Nama</label>
                                <input id="name" x-model="profileData.name" name="name"
                                    class="mt-1 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                    placeholder="Masukkan Nama" />

                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <div>
                                <label for="subtitle" class="text-sm font-medium block">Subtitle/Position</label>
                                <input id="subtitle" x-model="profileData.subtitle" name="subtitle"
                                    class="mt-1 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10"
                                    placeholder="Masukkan Subtitle/Position" />

                                <x-input-error :messages="$errors->get('subtitle')" class="mt-1" />
                            </div>

                            <div>
                                <label for="description" class="text-sm font-medium block">Description</label>
                                <textarea id="description" x-model="profileData.description" name="description"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10"
                                    rows="4" placeholder="Masukkan Deskripsi"></textarea>

                                <x-input-error :messages="$errors->get('description')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                        <div>
                            <label for="skills" class="mb-2 text-sm font-medium block">Skills</label>
                            <div class="flex flex-wrap gap-2 mt-2 mb-3">
                                <template x-for="(skill, index) in profileData.skills" :key="index">
                                    <div
                                        class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 hover:bg-blue-200">
                                        <span x-text="skill"></span>
                                        <button @click="handleRemoveSkill(index)" type="button"
                                            class="ml-1 text-blue-800 hover:text-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                <path d="M18 6 6 18"></path>
                                                <path d="m6 6 12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center gap-2">
                                <input id="newSkill" x-model="newSkill" placeholder="Add a skill"
                                    @keydown.enter="handleAddSkill()"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10" />
                                <button @click="handleAddSkill()" type="button"
                                    class="text-theme-sm shadow-theme-xs inline-flex h-10 items-center gap-2 rounded-lg border bg-blue-500 px-4 py-2.5 font-medium text-white hover:bg-blue-700 transition ease-in-out duration-300">
                                    Add
                                </button>

                                <input type="hidden" name="skills" x-model="JSON.stringify(profileData.skills)">

                            </div>

                            <x-input-error :messages="$errors->get('skills')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileSettings', () => ({
                profileData: {
                    name: '{{ $profile->name ?? '' }}',
                    subtitle: '{{ $profile->subtitle ?? '' }}',
                    description: '{{ $profile->description ?? '' }}',
                    skills: @json(isset($profile) && $profile->skills ? json_decode($profile->skills, true) : []),
                    photo: '{{ isset($profile) && $profile->photo ? asset($profile->photo) : '' }}'
                },
                newSkill: '',
                isUploading: false,

                handleAddSkill() {
                    const skillToAdd = this.newSkill.trim();
                    if (skillToAdd && !this.profileData.skills.includes(skillToAdd)) {
                        this.profileData.skills.push(skillToAdd);
                        this.newSkill = ''; // Clear input after adding
                    }
                },

                handleRemoveSkill(index) {
                    this.profileData.skills.splice(index, 1);
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.isUploading = true;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.profileData.photo = e.target.result;
                            this.isUploading = false;
                        };
                        reader.onerror = () => {
                            this.isUploading = false;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }));
        });
    </script>
@endsection
