<?php

namespace App\Filament\Resources\DailyJournalResource\Pages;

use App\Filament\Resources\DailyJournalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyJournal extends CreateRecord
{
    protected static string $resource = DailyJournalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan student_id terisi otomatis untuk siswa
        if (auth()->user()?->isStudent() && empty($data['student_id'])) {
            $data['student_id'] = auth()->user()->student?->id;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
