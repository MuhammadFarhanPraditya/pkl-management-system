<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class CustomLogin extends BaseLogin
{
    /**
     * Tambahkan link Aktivasi Akun di bawah heading login.
     */
    public function getSubheading(): string|Htmlable|null
    {
        $parent = parent::getSubheading();

        if ($parent !== null) {
            return $parent;
        }

        $activateUrl = route('activate.form');

        return new HtmlString(
            'Belum punya akun? <a href="' . $activateUrl . '" class="text-primary-600 hover:text-primary-500 font-semibold underline">Aktivasi Akun</a>'
        );
    }
}
