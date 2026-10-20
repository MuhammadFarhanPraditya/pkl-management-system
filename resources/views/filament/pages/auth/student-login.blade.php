<x-filament-panels::page.simple heading="" subheading="">
    <div class="student-login-slider">
        <section
            class="student-login-view student-login-view--login"
            x-show="! activationMode"
            x-transition.opacity.duration.300ms
            x-cloak
        >
            <header class="student-login-view-header">
                <h1 class="student-login-view-title">STUDENT LOGIN</h1>
                <div class="student-login-view-subheading">
                    Pengguna baru?
                    <a
                        href="{{ route('activate.form') }}"
                        class="student-login-link"
                        x-on:click.prevent="toggleActivation()"
                    >
                        Aktivasi Akun
                    </a>
                </div>
            </header>

            @if (session('status'))
                <p class="student-login-status" role="status">{{ session('status') }}</p>
            @endif

            <div class="student-login-form-panel">
                {{ $this->content }}
            </div>
        </section>

        <section
            class="student-login-view student-login-view--activation"
            x-show="activationMode"
            x-transition.opacity.duration.300ms
            x-cloak
        >
            <header class="student-login-view-header">
                <h1 class="student-login-view-title">AKTIVASI AKUN</h1>
                <div class="student-login-view-subheading">
                    Sudah punya akun?
                    <a
                        href="{{ url('/admin/login') }}"
                        class="student-login-link"
                        x-on:click.prevent="toggleActivation()"
                    >
                        Kembali ke Login
                    </a>
                </div>
            </header>

            <form action="{{ route('activate.verify') }}" method="POST" class="student-activation-form">
                @csrf
                <input type="hidden" name="role" value="siswa">

                @if ($errors->any())
                    <div class="student-activation-errors" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="student-activation-field">
                    <label for="activation-nisn">NISN</label>
                    <input
                        id="activation-nisn"
                        type="text"
                        name="nisn"
                        value="{{ old('nisn') }}"
                        maxlength="10"
                        inputmode="numeric"
                        autocomplete="off"
                        placeholder="Masukkan 10 digit NISN"
                        required
                    >
                </div>

                <div class="student-activation-field">
                    <label for="activation-date-of-birth">Tanggal Lahir</label>
                    <input
                        id="activation-date-of-birth"
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        required
                    >
                </div>

                <p class="student-activation-hint">
                    Pastikan data Anda sudah didaftarkan oleh admin sekolah.
                </p>

                <button type="submit" class="student-activation-submit">
                    Verifikasi &amp; Lanjutkan
                </button>
            </form>
        </section>
    </div>

    <button
        type="button"
        class="student-login-art-toggle"
        x-on:click="toggleActivation()"
        x-bind:aria-label="activationMode ? 'Kembali ke login' : 'Buka aktivasi akun'"
    >
        <span class="student-login-question-mark" aria-hidden="true">?</span>
        <span class="student-login-rocket" aria-hidden="true">🚀</span>
        <span class="student-login-art-label" x-show="! activationMode" x-transition.opacity.duration.250ms>
            Aktivasi Akun
        </span>
        <span class="student-login-art-label" x-show="activationMode" x-transition.opacity.duration.250ms>
            Kembali ke Login
        </span>
    </button>

    @vite('resources/js/student-login.js')
</x-filament-panels::page.simple>
