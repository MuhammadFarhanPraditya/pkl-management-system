<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyJournalResource\Pages;
use App\Models\DailyJournal;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DailyJournalResource extends Resource
{
    protected static ?string $model = DailyJournal::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Jurnal & Laporan';

    protected static ?string $navigationLabel = 'Jurnal Harian PKL';

    protected static ?string $modelLabel = 'Jurnal Harian';

    protected static ?string $pluralModelLabel = 'Jurnal Harian PKL';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['student.user', 'student.company', 'student.teacher.user']);

        // Siswa hanya melihat jurnal miliknya
        if (auth()->user()?->isStudent()) {
            return $query->where('student_id', auth()->user()->student?->id);
        }

        // Guru hanya melihat jurnal siswa bimbingannya
        if (auth()->user()?->isTeacher()) {
            return $query->whereHas('student', function ($q) {
                $q->where('teacher_id', auth()->user()->teacher?->id);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        $isStudent = auth()->user()?->isStudent();

        return $schema
            ->components([
                // Jika siswa, student_id otomatis diset
                $isStudent
                    ? Hidden::make('student_id')
                        ->default(fn () => auth()->user()->student?->id)
                        ->required()
                    : Select::make('student_id')
                        ->label('Siswa & Penempatan PKL')
                        ->relationship('student', 'id', fn ($query) => $query->with(['user', 'company']))
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user?->name} - {$record->company?->name}")
                        ->searchable()
                        ->preload()
                        ->required(),

                DatePicker::make('date')
                    ->label('Tanggal Kegiatan / Presensi')
                    ->default(now())
                    ->maxDate(now())
                    ->required(),

                Select::make('attendance_status')
                    ->label('Status Kehadiran / Presensi')
                    ->options([
                        'hadir' => '🟢 Hadir (Masuk PKL)',
                        'izin' => '🟡 Izin',
                        'sakit' => '🔴 Sakit',
                    ])
                    ->default('hadir')
                    ->required()
                    ->live(),

                TimePicker::make('start_time')
                    ->label('Jam Masuk / Mulai')
                    ->default('08:00:00')
                    ->visible(fn ($get) => ($get('attendance_status') ?? 'hadir') === 'hadir')
                    ->required(fn ($get) => ($get('attendance_status') ?? 'hadir') === 'hadir'),

                TimePicker::make('end_time')
                    ->label('Jam Selesai / Pulang')
                    ->default('16:00:00')
                    ->visible(fn ($get) => ($get('attendance_status') ?? 'hadir') === 'hadir')
                    ->required(fn ($get) => ($get('attendance_status') ?? 'hadir') === 'hadir'),

                Textarea::make('work_description')
                    ->label(fn ($get) => in_array($get('attendance_status'), ['izin', 'sakit']) ? 'Alasan / Keterangan Tidak Masuk' : 'Deskripsi Pekerjaan / Aktivitas Harian')
                    ->rows(4)
                    ->placeholder(fn ($get) => in_array($get('attendance_status'), ['izin', 'sakit']) ? 'Jelaskan alasan izin atau sakit secara rinci...' : 'Jelaskan secara rinci tugas atau materi yang Anda kerjakan hari ini di tempat PKL...')
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('documentation_path')
                    ->label(fn ($get) => in_array($get('attendance_status'), ['izin', 'sakit']) ? 'Foto Surat Izin / Keterangan Dokter (Opsional)' : 'Foto Dokumentasi Kegiatan')
                    ->image()
                    ->directory('journals')
                    ->visibility('public')
                    ->imagePreviewHeight('250')
                    ->helperText(fn ($get) => in_array($get('attendance_status'), ['izin', 'sakit']) ? 'Unggah bukti foto surat izin atau surat dokter bila ada.' : 'Upload foto bukti Anda sedang beraktivitas di tempat PKL.')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status Jurnal')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Menunggu Review Guru',
                        'approved' => 'Disetujui',
                        'revision' => 'Perlu Revisi',
                    ])
                    ->default('submitted')
                    ->disabled(fn () => $isStudent)
                    ->required(),

                Textarea::make('teacher_note')
                    ->label('Catatan Feedback Guru Pembimbing')
                    ->placeholder('Catatan atau arahan perbaikan dari guru...')
                    ->rows(2)
                    ->disabled(fn () => $isStudent)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isStudent = auth()->user()?->isStudent();

        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('attendance_status')
                    ->label('Presensi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        default => ucfirst($state ?? 'Hadir'),
                    }),

                TextColumn::make('student.user.name')
                    ->label('Nama Siswa')
                    ->visible(! $isStudent)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.company.name')
                    ->label('Mitra DU-DI')
                    ->visible(! $isStudent)
                    ->searchable(),

                TextColumn::make('work_description')
                    ->label('Ringkasan Kegiatan / Keterangan')
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->work_description),

                ImageColumn::make('documentation_path')
                    ->label('Foto / Bukti')
                    ->square(),

                TextColumn::make('status')
                    ->label('Status Review')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'submitted' => 'warning',
                        'revision' => 'danger',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'submitted' => 'Menunggu Review',
                        'revision' => 'Perlu Revisi',
                        'draft' => 'Draft',
                        default => $state,
                    }),

                TextColumn::make('teacher_note')
                    ->label('Catatan Guru')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->teacher_note)
                    ->default('-'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('attendance_status')
                    ->label('Presensi')
                    ->options([
                        'hadir' => '🟢 Hadir',
                        'izin' => '🟡 Izin',
                        'sakit' => '🔴 Sakit',
                    ]),
                SelectFilter::make('status')
                    ->label('Status Review')
                    ->options([
                        'submitted' => 'Menunggu Review',
                        'approved' => 'Disetujui',
                        'revision' => 'Perlu Revisi',
                        'draft' => 'Draft',
                    ]),
            ])
            ->actions([
                // Quick Action: Review & Setujui (Untuk Guru & Admin)
                Action::make('approve')
                    ->label('Review & Setujui')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->visible(fn ($record) => (auth()->user()?->isTeacher() || auth()->user()?->isAdmin()) && $record->status !== 'approved')
                    ->modalHeading('Review & Setujui Jurnal PKL')
                    ->modalWidth('lg')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('work_description')
                            ->label('Uraian Pekerjaan')
                            ->content(fn ($record) => $record->work_description),
                        \Filament\Forms\Components\Placeholder::make('documentation')
                            ->label('Bukti Dokumentasi')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                $record->documentation_path 
                                    ? '<img src="/storage/'.$record->documentation_path.'" style="max-width: 100%; border-radius: 8px;">' 
                                    : 'Tidak ada foto/bukti'
                            )),
                    ])
                    ->modalSubmitActionLabel('Ya, Setujui Jurnal')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Jurnal Disetujui')
                            ->body('Jurnal siswa berhasil disetujui.')
                            ->success()
                            ->send();
                    }),

                // Quick Action: Minta Revisi (Untuk Guru & Admin)
                Action::make('revision')
                    ->label('Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->visible(fn ($record) => (auth()->user()?->isTeacher() || auth()->user()?->isAdmin()) && $record->status !== 'revision' && $record->status !== 'approved')
                    ->modalHeading('Minta Revisi Jurnal')
                    ->modalWidth('lg')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('work_description')
                            ->label('Uraian Pekerjaan')
                            ->content(fn ($record) => $record->work_description),
                        \Filament\Forms\Components\Placeholder::make('documentation')
                            ->label('Bukti Dokumentasi')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                $record->documentation_path 
                                    ? '<img src="/storage/'.$record->documentation_path.'" style="max-width: 100%; border-radius: 8px;">' 
                                    : 'Tidak ada foto/bukti'
                            )),
                        \Filament\Forms\Components\Textarea::make('teacher_note')
                            ->label('Catatan Revisi untuk Siswa')
                            ->placeholder('Tuliskan perbaikan apa yang harus dilakukan siswa...')
                            ->required(),
                    ])
                    ->modalSubmitActionLabel('Kirim Revisi')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'revision',
                            'teacher_note' => $data['teacher_note'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revisi Dikirim')
                            ->body('Siswa harus memperbaiki jurnal ini sebelum dapat disetujui.')
                            ->warning()
                            ->send();
                    }),

                EditAction::make()
                    ->label(fn ($record) => $record->status === 'revision' ? 'Revisi Jurnal' : 'Edit')
                    ->icon(fn ($record) => $record->status === 'revision' ? 'heroicon-o-exclamation-circle' : 'heroicon-o-pencil')
                    ->color(fn ($record) => $record->status === 'revision' ? 'danger' : 'primary')
                    ->visible(fn ($record) => (auth()->user()?->isStudent() && $record->status !== 'approved') || auth()->user()?->isAdmin()),
                DeleteAction::make()
                    ->visible(fn ($record) => (auth()->user()?->isStudent() && $record->status !== 'approved') || auth()->user()?->isAdmin()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkApprove')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn () => auth()->user()?->isTeacher() || auth()->user()?->isAdmin())
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Jurnal Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui semua jurnal siswa yang telah dipilih?')
                        ->action(fn ($records) => $records->each->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ])),
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isAdmin()),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyJournals::route('/'),
            'create' => Pages\CreateDailyJournal::route('/create'),
            'edit' => Pages\EditDailyJournal::route('/{record}/edit'),
        ];
    }
}
