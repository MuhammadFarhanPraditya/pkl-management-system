<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;
        $data['user']['name'] = $user?->name;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userData = $data['user'] ?? [];
        unset($data['user']);

        $user = $this->record->user;
        if ($user) {
            $user->update(['name' => $userData['name'] ?? $user->name]);
        }

        return $data;
    }
}
