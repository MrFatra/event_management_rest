<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\EventRating;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalEvents = Event::count();
        $activeRegistrations = Registration::where('status', 'confirmed')->count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $averageRating = EventRating::avg('rating');

        return [
            Stat::make('Total Event', $totalEvents)
                ->description('Jumlah event yang telah dibuat')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success')
                ->chart([7, 12, 18, 25, 30, 35, $totalEvents]),

            Stat::make('Registrasi Aktif', $activeRegistrations)
                ->description('Peserta yang telah terdaftar')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info')
                ->chart([5, 10, 15, 20, 28, 32, $activeRegistrations]),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total revenue dari pembayaran')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([100000, 250000, 500000, 750000, 1000000, 1500000, $totalRevenue]),

            Stat::make('Rating Rata-rata', $averageRating ? number_format($averageRating, 1) . ' ⭐' : 'Belum ada')
                ->description('Rating rata-rata event')
                ->descriptionIcon('heroicon-o-star')
                ->color($averageRating >= 4 ? 'success' : ($averageRating >= 3 ? 'warning' : 'danger'))
                ->chart([3, 3.5, 4, 4.2, 4.5, 4.7, $averageRating ?? 0]),
        ];
    }
}
