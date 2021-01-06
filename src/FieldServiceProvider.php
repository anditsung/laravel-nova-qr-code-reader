<?php

namespace Tsungsoft\QrCodeReader;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => App::resourcePath('lang/vendor/qr-code-reader'),
            ], ['qr-code-reader', 'lang', 'qr-code-reader-lang']);
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'qr-code-reader');

        Nova::serving(function (ServingNova $event) {
            Nova::script('qr-code-reader', __DIR__.'/../dist/js/field.js');
            Nova::style('qr-code-reader', __DIR__.'/../dist/css/field.css');

            Nova::translations(
                App::resourcePath('lang/vendor/qr-code-reader/'.app()->getLocale().'.json')
            );
        });
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
