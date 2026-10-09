<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Guru Pembimbing';

    protected static ?string $modelLabel = 'Guru Pembimbing';

    protected static ?string $pluralModelLabel = 'Guru Pembimbing';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Akun')
                    ->description('Informasi login dan identitas guru pembimbing.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('user.email')
                            ->label('Email (untuk login)')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record?->user)
                            ->maxLength(255),
                        TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Kosongkan jika tidak ingin mengubah password (saat edit).')
                            ->maxLength(255),
                    ]),

                Section::make('Data Kepegawaian')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nip')
                            ->label('NIP (Nomor Induk Pegawai)')
                            ->maxLength(30),
                        TextInput::make('phone_number')
                            ->label('No. Handphone / WhatsApp')
                            ->tel()
                            ->maxLength(20),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('No. HP'),
                TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Total Siswa Dibimbing'),
            ])
            ->actions([
                \Filament\Actions\Action::make('printAssignmentLetter')
                    ->label('Surat Tugas')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => route('reports.teacher.letter', $record), shouldOpenInNewTab: true),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
