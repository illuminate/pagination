<?php namespace Illuminate\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\View\Environment as ViewEnvironment;
use Symfony\Component\Translation\TranslatorInterface;

class Environment {

	/**
	 * The request instance.
	 *
	 * @var Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * The view environment instance.
	 *
	 * @var Illuminate\View\Environment
	 */
	protected $view;

	/**
	 * The translator implementation.
	 *
	 * @var Symfony\Component\Translation\TranslatorInterface
	 */
	protected $trans;

	/**
	 * The locale to be used by the translator.
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 * Create a new pagination environment.
	 *
	 * @param  Symfony\Component\HttpFoundation\Request  $request
	 * @param  Illuminate\View\Environment  $view
	 * @param  Illuminate\Translation\TranslatorInterface  $trans
	 * @return void
	 */
	public function __construct(Request $request, ViewEnvironment $view, TranslatorInterface $trans)
	{
		$this->view = $view;
		$this->trans = $trans;
		$this->request = $request;
	}

	/**
	 * Get a new paginator instance.
	 *
	 * @param  array  $items
	 * @param  int    $perPage
	 * @return Illuminate\Pagination\Paginator
	 */
	public function make(array $items, $perPage)
	{
		$paginator = new Paginator($this, $items, $perPage);

		return $paginator->setupPaginationContext();
	}

	/**
	 * Get the current page from the request query.
	 *
	 * @return int
	 */
	public function getCurrentPage()
	{
		return $this->request->query->get('page', 1);
	}

	/**
	 * Get the locale of the paginator.
	 *
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * Set the locale of the paginator.
	 *
	 * @param  string  $locale
	 * @return void
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}

	/**
	 * Set the current view driver.
	 *
	 * @param  Illuminate\View\Environment  $view
	 * @return void
	 */
	public function setViewDriver(ViewEnvironment $view)
	{
		$this->view = $view;
	}

}