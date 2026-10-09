<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make('importCsv')
                ->label('Import Data Siswa')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->visible(fn () => auth()->user()?->isAdmin())
                ->importer(StudentImporter::class)
                ->csvDelimiter(',')
                ->modalHeading('Import Data Siswa')
                ->modalDescription(
                    'Upload file CSV dari data sekolah. Sistem akan menampilkan pratinjau kolom dan meminta Anda memetakan kolom CSV ke kolom sistem. ' .
                    'Kolom umum seperti nama, nisn, kelas, jurusan, tanggal_lahir akan terdeteksi otomatis.'
                )
                ->modalSubmitActionLabel('Mulai Import'),
            CreateAction::make(),
        ];
    }
}
