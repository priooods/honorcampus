<?php

namespace App\Filament\Widgets;

use App\Models\TMahasiswaTab;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Dosen', User::where('m_user_roles_id',3)->pluck('id')->count())
                ->color('success'),
            Stat::make('Mahasiswa Proposal', TMahasiswaTab::where('status_bimbingan_proposal',1)->count())
                ->color('danger'),
            Stat::make('Mahasiswa Skripsi', TMahasiswaTab::where('status_bimbingan_skripsi', 1)->count())
                ->color('success'),
        ];
    }
}
