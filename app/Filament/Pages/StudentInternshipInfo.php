<?php

namespace App\Filament\Pages;

use App\Models\Company;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class StudentInternshipInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.student-internship-info';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Pembimbing & Info PKL';

    protected static ?string $title = 'Informasi Bimbingan & Tempat PKL';

    protected static string | UnitEnum | null $navigationGroup = 'Informasi Siswa';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isStudent() || auth()->user()?->isAdmin();
    }

    public function mount(): void
    {
        $user = auth()->user();
        if ($user?->isStudent() && $user->student) {
            $this->form->fill([
                'company_id' => $user->student->company_id,
                'start_date' => $user->student->start_date,
                'end_date'   => $user->student->end_date,
            ]);
        }
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->model(auth()->user()?->student ?? \App\Models\Student::class)
            ->schema([
                Select::make('company_id')
                    ->label('Nama Perusahaan / Tempat PKL')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Perusahaan / Instansi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('pic_name')
                            ->label('Nama PIC / Mentor Lapangan')
                            ->maxLength(255),
                        TextInput::make('pic_contact')
                            ->label('Nomor WhatsApp PIC')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->createOptionAction(function ($action) {
                        return $action
                            ->modalHeading('Tambah Perusahaan Baru')
                            ->modalSubmitActionLabel('Simpan & Pilih')
                            ->modalWidth('md');
                    }),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai PKL')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai PKL')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function savePlacement(): void
    {
        $data = $this->form->getState();
        $student = auth()->user()?->student;

        if ($student) {
            $student->update([
                'company_id' => $data['company_id'],
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'status'     => 'active',
            ]);

            Notification::make()
                ->title('Informasi PKL Berhasil Disimpan!')
                ->success()
                ->send();
        }
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $student = $user?->student;
        $student?->load(['company', 'teacher.user']);
        $placement = $student;

        // Hitung masa & progres PKL
        $startDate = $placement?->start_date;
        $endDate = $placement?->end_date;
        $now = Carbon::today();

        $totalDays = ($startDate && $endDate) ? $startDate->diffInDays($endDate) + 1 : 0;
        $daysPassed = ($startDate && $now->greaterThanOrEqualTo($startDate))
            ? min($totalDays, $startDate->diffInDays($now) + 1)
            : 0;
        $daysRemaining = max(0, $totalDays - $daysPassed);
        $progressPercent = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;

        // WhatsApp Guru Pembimbing Link Generator
        $teacherPhone = $placement?->teacher?->phone_number;
        $waTeacherUrl = null;
        if ($teacherPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $teacherPhone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }
            $studentName = $user?->name ?? 'Siswa';
            $companyName = $placement?->company?->name ?? 'Tempat PKL';
            $msg = urlencode("Halo Bapak/Ibu {$placement?->teacher?->user?->name}, saya {$studentName} ({$student?->class_name}) siswa bimbingan PKL di {$companyName}. Izin menghubungi terkait pelaksanaan PKL.");
            $waTeacherUrl = "https://wa.me/{$cleanPhone}?text={$msg}";
        }

        // WhatsApp PIC DU-DI / Industri Link Generator
        $picContact = $placement?->company?->pic_contact;
        $waPicUrl = null;
        if ($picContact) {
            $cleanPicPhone = preg_replace('/[^0-9]/', '', $picContact);
            if (str_starts_with($cleanPicPhone, '0')) {
                $cleanPicPhone = '62' . substr($cleanPicPhone, 1);
            }
            $studentName = $user?->name ?? 'Siswa';
            $companyName = $placement?->company?->name ?? 'Tempat PKL';
            $msgPic = urlencode("Halo Bapak/Ibu {$placement?->company?->pic_name}, saya {$studentName} siswa PKL di {$companyName}. Izin menghubungi terkait kegiatan PKL.");
            $waPicUrl = "https://wa.me/{$cleanPicPhone}?text={$msgPic}";
        }

        return [
            'hasPlacement' => $placement && $placement->company_id,
            'student' => $student,
            'placement' => $placement,
            'teacher' => $placement?->teacher,
            'company' => $placement?->company,
            'startDate' => $startDate ? $startDate->locale('id')->translatedFormat('d F Y') : '-',
            'endDate' => $endDate ? $endDate->locale('id')->translatedFormat('d F Y') : '-',
            'totalDays' => $totalDays,
            'daysPassed' => $daysPassed,
            'daysRemaining' => $daysRemaining,
            'progressPercent' => $progressPercent,
            'waTeacherUrl' => $waTeacherUrl,
            'waPicUrl' => $waPicUrl,
        ];
    }
}
