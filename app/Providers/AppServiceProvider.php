<?php

namespace App\Providers;

use App\Models\BarangBeli;
use App\Models\BarangJualDetail;
use App\Models\Notification;
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
        // Auto-create notification when stock-in happens
        BarangBeli::created(function ($barangBeli) {
            Notification::create([
                'type' => 'masuk',
                'notifiable_id' => $barangBeli->id,
                'notifiable_type' => BarangBeli::class,
            ]);
        });

        // Auto-create notification when stock-out happens
        BarangJualDetail::created(function ($detail) {
            Notification::create([
                'type' => 'keluar',
                'notifiable_id' => $detail->id,
                'notifiable_type' => BarangJualDetail::class,
            ]);
        });

        View::composer('layouts.app', function ($view) {
            $notifMasuk = Notification::where('type', 'masuk')
                ->with('notifiable.barang', 'notifiable.user')
                ->latest()
                ->take(5)
                ->get();

            $notifKeluar = Notification::where('type', 'keluar')
                ->with('notifiable.barang', 'notifiable.barangJual.user')
                ->latest()
                ->take(5)
                ->get();

            $latestNotif = Notification::latest()->first()?->created_at;

            $view->with(compact('notifMasuk', 'notifKeluar', 'latestNotif'));
        });
    }
}
