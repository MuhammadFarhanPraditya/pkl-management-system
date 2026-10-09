<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-xl border border-amber-300 bg-gradient-to-r from-amber-50 via-amber-50 to-orange-50 p-5 shadow-sm dark:border-amber-700/60 dark:from-amber-950/40 dark:via-amber-900/30 dark:to-orange-950/30">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-4">
                <div class="rounded-xl bg-amber-500/10 p-3 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                    <x-filament::icon icon="heroicon-o-exclamation-triangle" class="animate-pulse" style="width: 1.75rem; height: 1.75rem;" />
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                            Perlu Tindakan Guru
                        </span>
                        <h3 class="text-base font-bold text-amber-950 dark:text-amber-100">
                            Profil & Kontak WhatsApp Belum Lengkap
                        </h3>
                    </div>
                    <p class="text-xs text-amber-900/80 dark:text-amber-200/80 leading-relaxed max-w-2xl">
                        @if (! $hasPhone && ! $hasNip)
                            Nomor WhatsApp aktif dan NIP Anda belum terisi.
                        @elseif (! $hasPhone)
                            Nomor WhatsApp aktif Anda belum terisi.
                        @else
                            NIP (Nomor Induk Pegawai) Anda belum terisi.
                        @endif
                        Lengkapi data ini agar siswa bimbingan PKL kelas 11 dapat melihat informasi pembimbing dan menghubungi Anda secara langsung.
                    </p>
                </div>
            </div>

            <div class="flex-shrink-0 sm:self-center">
                <a
                    href="{{ $profileUrl }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:bg-amber-500 dark:hover:bg-amber-600"
                >
                    <x-filament::icon icon="heroicon-o-pencil-square" style="width: 1rem; height: 1rem;" />
                    Lengkapi Profil Sekarang
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
