<?php namespace Spajz\Msg;

use Illuminate\Support\ServiceProvider;

class MsgServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('spajz/msg');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app['msg'] = $this->app->share(function ($app) {
            return new Msg;
        });

        $this->app->after(function () use ($app) {
            $app['msg']->flashSession();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('msg');
    }

}
