<?php

namespace App\Providers;

use App\Models\BarangBeli;
use App\Models\BarangJualDetail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $notifMasuk = BarangBeli::with('barang', 'user')
                ->latest()
                ->take(5)
                ->get();

            $notifKeluar = BarangJualDetail::with('barang', 'barangJual.user')
                ->whereHas('barangJual')
                ->latest()
                ->take(5)
                ->get();

            $latestMasuk = $notifMasuk->first()?->created_at;
            $latestKeluar = $notifKeluar->first()?->created_at;
            $latestNotif = collect([$latestMasuk, $latestKeluar])->filter()->max();

            $view->with(compact('notifMasuk', 'notifKeluar', 'latestNotif'));
        });
    }
}
