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
		$app['paginator'] = $app->share(function($app)
		{
			$paginator = new Environment($app['request'], $app['view'], $app['translator']);

			$paginator->setViewName($app['config']['view.pagination']);

			return $paginator;
		});
	}

}