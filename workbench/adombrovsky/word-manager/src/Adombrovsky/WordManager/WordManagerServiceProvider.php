<?php namespace Adombrovsky\WordManager;

use Illuminate\Support\ServiceProvider;

/**
 * Class WordManagerServiceProvider
 * @package Adombrovsky\WordManager
 */
class WordManagerServiceProvider extends ServiceProvider {

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
        $this->package('adombrovsky/word-manager');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['WordManager'] = $this->app->share(function($app)
        {
            return new WordManager();
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('WordManager', 'Adombrovsky\WordManager\Facades\WordManager');
        });

    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
