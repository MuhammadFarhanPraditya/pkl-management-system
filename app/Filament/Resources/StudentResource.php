<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Siswa Kelas 11';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Siswa Kelas 11';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Guru hanya melihat siswa bimbingannya
        if (auth()->user()?->isTeacher()) {
            return $query->where('teacher_id', auth()->user()->teacher?->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pribadi & Akademik')
                    ->columns(2)
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Nama Lengkap Siswa')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10),
                        TextInput::make('nis')
                            ->label('NIS')
                            ->maxLength(20),
                        DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->maxDate(now()),
                        TextInput::make('class_name')
                            ->label('Kelas (Rombel)')
                            ->placeholder('Contoh: XI RPL 1')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('major')
                            ->label('Kompetensi Keahlian / Jurusan')
                            ->placeholder('Contoh: Rekayasa Perangkat Lunak')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('phone_number')
                            ->label('No. Handphone / WhatsApp')
                            ->tel()
                            ->maxLength(20),
                    ]),

                Section::make('Penugasan Guru Pembimbing')
                    ->description('Admin menentukan guru yang bertanggung jawab membimbing siswa ini.')
                    ->schema([
                        Select::make('teacher_id')
                            ->label('Guru Pembimbing')
                            ->options(fn () => \App\Models\Teacher::with('user')->get()->pluck('user.name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Guru Pembimbing (bisa diatur belakangan)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                TextColumn::make('class_name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('major')
                    ->label('Jurusan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Tempat PKL')
                    ->default('-'),
                TextColumn::make('teacher.user.name')
                    ->label('Guru Pembimbing')
                    ->default('-'),
                IconColumn::make('user.email')
                    ->label('Akun Aktif')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->user?->email !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('assignTeacher')
                        ->label('Tugaskan Guru Pembimbing')
                        ->icon('heroicon-o-users')
                        ->color('primary')
                        ->form([
                            \Filament\Forms\Components\Select::make('teacher_id')
                                ->label('Pilih Guru Pembimbing')
                                ->options(fn () => \App\Models\Teacher::with('user')->get()->pluck('user.name', 'id'))
                                ->required()
                                ->searchable(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update(['teacher_id' => $data['teacher_id']]);
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Berhasil Menugaskan Guru')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                        
                    \Filament\Actions\BulkAction::make('assignCompany')
                        ->label('Tempatkan ke DU-DI')
                        ->icon('heroicon-o-building-office')
                        ->color('success')
                        ->form([
                            \Filament\Forms\Components\Select::make('company_id')
                                ->label('Pilih Tempat PKL (Mitra DU-DI)')
                                ->options(fn () => \App\Models\Company::pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                            \Filament\Forms\Components\DatePicker::make('start_date')
                                ->label('Tanggal Mulai PKL')
                                ->required(),
                            \Filament\Forms\Components\DatePicker::make('end_date')
                                ->label('Tanggal Selesai PKL')
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'company_id' => $data['company_id'],
                                    'start_date' => $data['start_date'],
                                    'end_date' => $data['end_date'],
                                ]);
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Berhasil Menempatkan Siswa ke DU-DI')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
