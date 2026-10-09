<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Mitra DU-DI';

    protected static ?string $modelLabel = 'Mitra DU-DI';

    protected static ?string $pluralModelLabel = 'Mitra DU-DI (Tempat PKL)';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Perusahaan / Instansi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('Kota / Wilayah')
                    ->maxLength(100),
                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),
                TextInput::make('pic_name')
                    ->label('Nama Pembimbing Lapangan (PIC)')
                    ->maxLength(255),
                TextInput::make('pic_contact')
                    ->label('No. Kontak / WA PIC')
                    ->tel()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pic_name')
                    ->label('PIC Lapangan')
                    ->searchable(),
                TextColumn::make('pic_contact')
                    ->label('Kontak PIC'),
                TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Jumlah Siswa PKL'),
            ])
            ->actions([
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
