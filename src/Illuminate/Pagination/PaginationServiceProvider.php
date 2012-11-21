<?php namespace Illuminate\Pagination;

use Illuminate\Support\ServiceProvider;

class PaginationServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$app['pagination.driver'] = $app->share(function($app)
		{
			return $app['view']->driver('php');
		});

		$this->registerPaginationEnvironment($app);
	}

	/**
	 * Register the pagination environment.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function registerPaginationEnvironment($app)
	{
		$app['paginator'] = $app->share(function($app)
		{
			// The pagination driver is the View driver responsible for rendering the
			// pagination views. Typically, it will be the "PHP" driver as that is
			// what every default pagination views use, but it could be tweaked.
			$view = $app['pagination.driver'];

			$paginator = new Environment($app['request'], $view, $app['translator']);

			$paginator->setViewName($app['config']['view.pagination']);

			return $paginator;
		});
	}

}