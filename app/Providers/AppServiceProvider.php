<?php

namespace YellowProject\Providers;

use Illuminate\Support\ServiceProvider;
use YellowProject\SettingPhpMailer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $settingPhpMailer = SettingPhpMailer::first();
        config(['mail.driver' => $settingPhpMailer->mail_driver]);
        config(['mail.host' => $settingPhpMailer->mail_host]);
        config(['mail.port' => $settingPhpMailer->mail_port]);
        config(['mail.encryption' => $settingPhpMailer->mail_encryption]);
        config(['mail.username' => $settingPhpMailer->mail_username]);
        config(['mail.password' => $settingPhpMailer->mail_password]);
        config(['mail.from.name' => 'Reset Password']);
        config(['mail.from.address' => $settingPhpMailer->mail_username]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
