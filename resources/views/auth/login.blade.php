@extends('layouts.auth')

@section('title', 'Login - Karya PPLG')

@section('content')
    <div class="decorative-shapes">
      <div class="shape shape-1">✨</div>
      <div class="shape shape-2">MUSEUM VIRTUAL</div>
      <div class="shape shape-3"></div>
      <div class="shape shape-4">CURATOR ACCESS</div>
      <div class="shape shape-5"></div>
      <div class="shape shape-6"></div>
    </div>

    <div class="main-wrapper mt-8 sm:mt-12">
      <div class="login-container dark:bg-gray-900 dark:border-gray-700">
        <div class="login-left dark:bg-gray-900">
          <div class="login-logo">
            <div class="login-logo-circle">K</div>
            <div class="login-logo-text dark:text-white">Karya PPLG</div>
          </div>
          <h1 class="login-title dark:text-white">Selamat Datang</h1>
          <p class="login-subtitle dark:text-gray-400">Masuk untuk melanjutkan ke dashboard Anda</p>

          {{-- Tambahkan onsubmit="handleLoginSubmit(event)" pada form --}}
          <form class="login-form" action="{{ route('login') }}" method="POST" onsubmit="handleLoginSubmit(event)">
            @csrf
            <div class="login-form-group">
              <label class="login-label dark:text-gray-300" for="email">Email</label>
              <input type="email" id="email" name="email" placeholder="Masukan Email Anda" class="login-input dark:bg-gray-800 dark:text-white dark:border-gray-700" required autofocus />
            </div>

            <div class="login-form-group">
              <label class="login-label dark:text-gray-300" for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Masukkan password Anda"  class="login-input dark:bg-gray-800 dark:text-white dark:border-gray-700" required />
            </div>

            <div class="login-checkbox dark:text-gray-300">
              <input type="checkbox" id="show-password" />
              <label for="show-password">Tampilkan Password</label>
            </div>

            {{-- Berikan id="login-submit-btn" pada tombol login --}}
            <button type="submit" id="login-submit-btn" class="login-button flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
              <span id="login-btn-text">Login Sekarang</span>
            </button>
          </form>

          <div class="login-divider dark:before:bg-gray-700 dark:after:bg-gray-700"><span class="dark:bg-gray-900 dark:text-gray-400">Atau</span></div>
          <div class="login-social">
            <a href="{{ route('google.login') }}" class="login-social-btn dark:bg-gray-800 dark:text-white dark:border-gray-700">
              <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
                <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571.001-.001.002-.001.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
              </svg>
              <span>Google</span>
            </a>
            <button type="button" class="login-social-btn dark:bg-gray-800 dark:text-white dark:border-gray-700" onclick="showComingSoonModal()">
              <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
              </svg>
              <span>GitHub</span>
            </button>
          </div>
          <p class="login-register-text dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="login-register-link">Daftar disini</a>
          </p>
        </div>

        <div id="comingSoonModal" class="modal-overlay" onclick="if (event.target === this) closeComingSoonModal();">
          <div class="modal-box dark:bg-gray-800 dark:text-white">
            <div class="modal-icon-circle dark:bg-gray-700 dark:text-blue-400">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <h3 class="modal-title dark:text-white">Segera Hadir</h3>
            <p class="modal-text dark:text-gray-300">Login dengan GitHub belum tersedia saat ini. Silahkan login dengan Akun Google</p>
            <button type="button" class="modal-close-btn" onclick="closeComingSoonModal()">Oke, Mengerti</button>
          </div>
        </div>

        <div class="login-right">
          <div>
            <h2 class="right-title">Museum Karya SMK Negeri 4 Tasikmalaya</h2>
            <p class="right-subtitle">kamu siswa smk 4 kamu punya karya? pamerkan disini</p>
            <div class="right-features">
              <div class="right-feature-item">
                <div>
                  <div class="right-feature-title">Portofolio Siswa</div>
                  <p>Tunjukkan karya terbaik Anda kepada dunia</p>
                </div>
              </div>
              <div class="right-feature-item">
                <div>
                  <div class="right-feature-title">Apresiasi Karya</div>
                  <p>Dapatkan feedback dan apresiasi dari komunitas</p>
                </div>
              </div>
              <div class="right-feature-item">
                <div>
                  <div class="right-feature-title">Pengembangan Karir</div>
                  <p>Terhubung dengan peluang kerja yang relevan</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
    <script>
      const showPasswordCheckbox = document.getElementById('show-password');
      const passwordInput = document.getElementById('password');

      if (showPasswordCheckbox && passwordInput) {
        showPasswordCheckbox.addEventListener('change', function () {
          passwordInput.type = this.checked ? 'text' : 'password';
        });
      }

      function showComingSoonModal() {
        const modal = document.getElementById('comingSoonModal');
        if (modal) modal.style.display = 'flex';
      }

      function closeComingSoonModal() {
        const modal = document.getElementById('comingSoonModal');
        if (modal) modal.style.display = 'none';
      }

      // Fungsi untuk mengubah tombol login menjadi loading dengan spinner & teks "Tunggu Bentar...."
      function handleLoginSubmit(event) {
        const btn = document.getElementById('login-submit-btn');
        const btnText = document.getElementById('login-btn-text');
        
        if (btn && btnText) {
          btn.disabled = true;
          btnText.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Tunggu Bentar....
          `;
        }
      }
    </script>
@endpush