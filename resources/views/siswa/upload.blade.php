@extends('layouts.siswa')

@section('title', 'Kirim Project')
@section('page_title', 'Kirim Project')

@section('content')
    @php
        // Label & placeholder per jurusan. Jurusan yang tidak terdaftar memakai $default.
        $default = [
            'title_label' => 'Judul Karya *',
            'title_ph'    => 'Contoh: Aplikasi Kasir Berbasis Web',
            'tech_label'  => 'Teknologi / Alat yang Digunakan *',
            'tech_ph'     => 'Sebutkan teknologi atau alat yang kamu pakai',
            'desc_label'  => 'Deskripsi Karya *',
            'desc_ph'     => 'Jelaskan fitur dan cara kerja karya yang kamu buat.....',
            'live_label'  => 'Link Live Demo / Portfolio (Opsional)',
            'file_label'  => 'Foto / Hasil Karya',
            'agree'       => 'Saya menyetujui semua syarat dan ketentuan di atas serta menjamin bahwa project yang saya upload adalah karya asli saya sendiri.',
        ];

        $overrides = [
            'PPLG' => [
                'tech_label' => 'Bahasa Pemrograman / Framework yang Digunakan *',
                'tech_ph'    => 'Contoh: Laravel, React, Flutter',
                'live_label' => 'Link Live Demo / Preview (Opsional)',
                'file_label' => 'Screenshot Tampilan Aplikasi',
            ],
            'TKJ' => [
                'title_label' => 'Judul Karya / Project *',
                'title_ph'    => 'Contoh: Perancangan Topologi Jaringan Fiber Optic',
                'tech_label'  => 'Perangkat / Software Jaringan *',
                'tech_ph'     => 'Contoh: Mikrotik RouterOS, Cisco Packet Tracer, Linux Server',
                'desc_label'  => 'Deskripsi Topologi & Sistem *',
                'desc_ph'     => 'Jelaskan konfigurasi, keunggulan, dan arsitektur jaringan yang dibuat.....',
                'live_label'  => 'Link Dokumentasi / Video Simulation (YouTube/Drive)',
                'file_label'  => 'Foto / Skema Topologi Jaringan',
                'agree'       => 'Saya menyetujui semua syarat dan ketentuan di atas serta menjamin bahwa project jaringan ini asli buatan saya.',
            ],
            'DKV' => [
                'title_ph'   => 'Contoh: Desain Poster Mascot',
                'tech_label' => 'Software Desain yang Digunakan *',
                'tech_ph'    => 'Contoh: Adobe Photoshop, Figma, CorelDRAW',
                'live_label' => 'Link Portfolio (Behance/Dribbble/Drive)',
                'file_label' => 'File Hasil Desain',
            ],
            'TOI' => [
                'tech_label' => 'Jenis Alat / Mesin Otomasi *',
                'tech_ph'    => 'Contoh: PLC, Arduino, Sensor IoT',
                'live_label' => 'Link Video Demo Alat (YouTube/Drive)',
                'file_label' => 'Foto Alat / Mesin',
            ],
            'TSM' => [
                'title_ph'   => 'Contoh: Modifikasi Sistem Injeksi Sepeda Motor',
                'tech_label' => 'Komponen / Alat / Teknologi yang Digunakan *',
                'tech_ph'    => 'Contoh: Sistem EFI, Scanner OBD, Dynotest',
                'desc_ph'    => 'Jelaskan proses pengerjaan, komponen yang dipakai, dan hasil karya kamu.....',
                'live_label' => 'Link Video Demo / Dokumentasi (YouTube/Drive)',
                'file_label' => 'Foto Hasil Karya / Kendaraan',
            ],
        ];

        // Kelas input neo-brutalist (dipakai berulang)
        $input = 'w-full px-4 py-2.5 neo-input rounded-lg outline-none font-semibold bg-white';
        $errorText = 'text-xs font-extrabold text-rose-600 mt-2';
    @endphp

    {{-- Penanda session success dan jurusan aktif jika terjadi error validasi --}}
    <div id="pageData"
        data-success="{{ session('success') ? 'true' : 'false' }}"
        data-active-jurusan="{{ session('active_jurusan', old('jurusan', $jurusanSiswa)) }}"
        class="hidden"></div>

    <div class="p-4 sm:p-6 md:p-8 w-full flex justify-center items-start">
        <div class="max-w-2xl w-full bg-white neo-border neo-shadow-lg rounded-2xl p-4 sm:p-6 md:p-8 my-2 sm:my-6">

            {{-- Jurusan dikunci sesuai akun siswa (tidak ada pilihan jurusan lagi) --}}
            @if ($jurusanSiswa)
                <p class="text-center text-sm font-bold text-slate-500">
                    Karya akan dikirim atas nama jurusan
                    <span class="inline-block ml-1 px-3 py-1 rounded-lg text-white font-black {{ $jurusanBadge[$jurusanSiswa] }}">
                        {{ $jurusanSiswa === 'TKJ' ? 'TJKT' : $jurusanSiswa }}
                    </span>
                </p>
            @else
                <p class="text-center text-sm font-extrabold text-rose-600 py-6">
                    Jurusan akun kamu belum diatur atau tidak dikenali, jadi belum bisa mengirim karya. Hubungi admin jurusan.
                </p>
            @endif

            <hr class="my-6 border-t-[3px] border-slate-900 border-dashed" />

            @foreach ($jurusanList as $jurusan)
                @php
                    $key = $jurusan === 'TJKT' ? 'TKJ' : $jurusan;
                    $f = array_merge($default, $overrides[$key] ?? []);
                    $isActiveForm = old('jurusan') == $jurusan;
                @endphp

                <form id="form_{{ $jurusan }}" method="POST" action="{{ url('/siswa/upload') }}"
                    data-jurusan-label="{{ $key === 'TKJ' ? 'TJKT' : $jurusan }}"
                    data-submit-class="{{ $jurusanColor[$jurusan] }}"
                    enctype="multipart/form-data" class="hidden space-y-6 mt-4" novalidate>
                    @csrf

                    <div class="text-center mb-4">
                        <span class="inline-block text-sm font-black px-4 py-1.5 rounded-full text-white neo-border {{ $jurusanBadge[$jurusan] }}">
                            Form Kirim Karya - {{ $key === 'TKJ' ? 'TJKT' : $jurusan }}
                        </span>
                    </div>

                    <input type="hidden" name="jurusan" value="{{ $jurusan }}" />

                    {{-- Judul Karya --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">{{ $f['title_label'] }}</label>
                        <input type="text" name="title" required value="{{ $isActiveForm ? old('title') : '' }}"
                            placeholder="{{ $f['title_ph'] }}" class="{{ $input }}" />
                        @if ($isActiveForm)
                            @error('title')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Technology Stack / Tools --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">{{ $f['tech_label'] }}</label>
                        <input type="text" name="technology_stack" required value="{{ $isActiveForm ? old('technology_stack') : '' }}"
                            placeholder="{{ $f['tech_ph'] }}" class="{{ $input }}" />
                        @if ($isActiveForm)
                            @error('technology_stack')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">{{ $f['desc_label'] }}</label>
                        <textarea name="description" required rows="4" placeholder="{{ $f['desc_ph'] }}"
                            class="{{ $input }} resize-none">{{ $isActiveForm ? old('description') : '' }}</textarea>
                        @if ($isActiveForm)
                            @error('description')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- KHUSUS PPLG WAJIB ISI LINK GITHUB --}}
                    @if ($jurusan === 'PPLG')
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800 mb-2">
                                Link Repository GitHub <span class="text-[#F43F5E]">* (Wajib Khusus PPLG)</span>
                            </label>
                            <input type="url" name="github_link" required value="{{ $isActiveForm ? old('github_link') : '' }}"
                                placeholder="https://github.com/username/repository-kamu" class="{{ $input }}" />
                            @if ($isActiveForm)
                                @error('github_link')
                                    <p class="{{ $errorText }}">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    @endif

                    {{-- Live Demo / Link Portfolio Umum --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">{{ $f['live_label'] }}</label>
                        <input type="url" name="live_link" value="{{ $isActiveForm ? old('live_link') : '' }}"
                            placeholder="https://" class="{{ $input }}" />
                        @if ($isActiveForm)
                            @error('live_link')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- LINK IFRAME (OPSIONAL / WAJIB JIKA TIDAK UPLOAD FOTO) --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">
                            Link Embed / Iframe <span class="text-xs text-slate-500 font-normal">(Opsional jika mengunggah foto)</span>
                        </label>
                        <input type="url" name="iframe_link" value="{{ $isActiveForm ? old('iframe_link') : '' }}"
                            placeholder="https://www.youtube.com/embed/xxxxx" class="{{ $input }}" />
                        @if ($isActiveForm)
                            @error('iframe_link')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- FOTO KARYA --}}
                    <div>
                        <label class="block text-sm font-extrabold text-slate-800 mb-2">
                            {{ $f['file_label'] }}
                            <span class="text-xs text-slate-500 font-normal">(Wajib diisi jika tidak menyertakan link iframe)</span>
                        </label>
                        <input type="file" name="file_path" accept="image/*" class="{{ $input }}" />
                        @if ($isActiveForm)
                            @error('file_path')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Syarat & Ketentuan --}}
                    <div class="neo-border rounded-lg bg-[#F1F5F9] p-4 space-y-3">
                        <h4 class="text-sm font-black text-slate-900">Syarat & Ketentuan Upload Karya:</h4>
                        <ul class="text-xs font-bold text-slate-600 list-disc list-inside space-y-1">
                            <li>Karya atau kode program harus asli hasil buatan sendiri/tim kelompok (bukan plagiat).</li>
                            <li>Link yang dicantumkan harus bersifat publik agar bisa diperiksa oleh Admin.</li>
                            <li>Karya yang melanggar hak cipta atau mengandung konten negatif akan langsung dihapus.</li>
                            <li>Karya adalah buatan siswa SMKN 4 Tasikmalaya aktif dan alumni.</li>
                            <li>Karya tidak mengandung SARA.</li>
                        </ul>

                        <hr class="border-slate-300 my-2" />

                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="agree_{{ $jurusan }}"
                                class="mt-1 h-4 w-4 accent-[#818CF8] cursor-pointer"
                                onchange="toggleSubmitButton('{{ $jurusan }}')" />
                            <label for="agree_{{ $jurusan }}"
                                class="text-xs font-bold text-slate-700 leading-normal cursor-pointer select-none">
                                {{ $f['agree'] }}
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="button" onclick="resetForm('{{ $jurusan }}')"
                            class="flex-1 px-6 py-3 bg-white neo-border neo-shadow-sm neo-btn text-slate-800 rounded-xl font-extrabold text-sm cursor-pointer">Reset</button>
                        <button type="submit" id="submit_{{ $jurusan }}" disabled
                            class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-xl font-extrabold text-sm neo-border neo-shadow-sm neo-btn cursor-not-allowed">Kirim Karya</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>

    {{-- MODAL SUCCESS --}}
    <div id="successModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white neo-border neo-shadow-lg rounded-2xl max-w-md w-full overflow-hidden p-6 text-center">
            <div class="w-16 h-16 bg-[#BBF7D0] neo-border rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-slate-900 mb-2">Berhasil Kirim Karya!</h3>
            <p class="text-slate-700 text-sm font-bold leading-relaxed mb-4">
                {{ session('success') }}
            </p>

            @if (session('foto_original_size') && session('foto_compressed_size'))
                <div class="bg-[#E0E7FF] neo-border text-slate-900 text-xs font-bold rounded-xl p-3 mb-4 space-y-1">
                    <p class="font-black">Info Kompresi Gambar:</p>
                    <p>Ukuran Asli: {{ round(session('foto_original_size') / 1024, 2) }} KB</p>
                    <p>Setelah Dikompres: {{ round(session('foto_compressed_size') / 1024, 2) }} KB</p>
                </div>
            @endif

            <a href="{{ url('/siswa/karya') }}"
                class="block w-full px-4 py-3 bg-[#818CF8] text-white rounded-xl text-sm font-extrabold neo-border neo-shadow-sm neo-btn">
                Lihat Karya Saya
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-jurusan {
            opacity: 0.55;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            border: 3px solid #1e293b;
            box-shadow: 3px 3px 0px 0px #1e293b;
        }

        .btn-jurusan.active {
            opacity: 1;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #1e293b;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/siswa/upload-project.js') }}"></script>
@endpush