<?php

namespace App\Providers;

use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
        Carbon::setLocale('id');

        View::composer('admin.*', function ($view): void {
            $unreadCount = 0;
            if (Schema::hasTable('contact_messages')) {
                $unreadCount = ContactMessage::unread()->count();
            }
            $view->with('unreadMessagesCount', $unreadCount);
        });
    }
}
