<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class CustomLogin extends BaseLogin
{
    protected string $view = 'filament.pages.auth.student-login';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getExtraBodyAttributes(): array
    {
        $isActivationMode = request()->query('mode') === 'activation' ? 'true' : 'false';

        return [
            'class' => 'student-login-page',
            'x-data' => "{ activationMode: {$isActivationMode}, toggleActivation() { this.activationMode = ! this.activationMode; const url = new URL(window.location); if (this.activationMode) { url.searchParams.set('mode', 'activation'); } else { url.searchParams.delete('mode'); } window.history.replaceState({}, '', url); } }",
            'x-bind:class' => "{ 'student-login-activation': activationMode }",
        ];
    }

    public function getHeading(): ?string
    {
        return 'STUDENT LOGIN';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->hint(filament()->hasPasswordReset() ? new \Illuminate\Support\HtmlString('<a href="' . filament()->getRequestPasswordResetUrl() . '" style="color: var(--nb-primary); font-size: 0.8rem; font-weight: 700; text-decoration: underline;">Lupa Kata Sandi?</a>') : null)
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required();
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('LOGIN')
            ->submit('authenticate');
    }
}
