<?php namespace Illuminate\Pagination;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class Paginator implements ArrayAccess, Countable, IteratorAggregate {

	/**
	 * The translator implementation.
	 *
	 * @var Symfony\Component\Translation\TranslatorInterface
	 */
	protected $trans;

	/**
	 * The request instance.
	 *
	 * @var Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * The items being paginated.
	 *
	 * @var array
	 */
	protected $items;

	/**
	 * The amount of items to show per page.
	 *
	 * @var int
	 */
	protected $perPage;

	/**
	 * The locale of the paginator.
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 * All of the additional query string values.
	 *
	 * @var array
	 */
	protected $query = array();

	/**
	 * Create a new Paginator instance.
	 *
	 * @param  Symfony\Component\Translation\TranslatorInterface  $trans
	 * @param  Symfony\Component\HttpFoundation\Request  $request
	 * @param  array  $items
	 * @param  int    $perPage
	 * @return void
	 */
	public function __construct(TranslatorInterface $trans, Request $request, array $items, $perPage)
	{
		$this->trans = $trans;
		$this->items = $items;
		$this->request = $request;
		$this->perPage = $perPage;
	}

	/**
	 * Setup the pagination context (current and last page).
	 *
	 * @return void
	 */
	public function setupPaginationContext()
	{
		$this->lastPage = ceil(count($this->items) / $this->perPage);

		$this->currentPage = $this->getCurrentPage($this->lastPage);
	}

	/**
	 * Get the current page for the request.
	 *
	 * @param  int  $lastPage
	 * @return int
	 */
	protected function getCurrentPage($lastPage)
	{
		$page = $this->request->query->get('page', 1);

		// The page number will get validated and adjusted if it either less than one
		// or greater than the last page available based on the count of the given
		// items array. If it's greater than the last, we'll give back the last.
		if (is_numeric($page) and $page > $lastPage)
		{
			return $lastPage > 0 ? $lastPage : 1;
		}

		return $this->isValidPageNumber($page) ? $page : 1;
	}

	/**
	 * Determine if the given value is a valid page number.
	 *
	 * @param  int  $page
	 * @return bool
	 */
	protected function isValidPageNumber($page)
	{
		return $page >= 1 and filter_var($page, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * Add a query string value to the paginator.
	 *
	 * @param  string  $key
	 * @param  string  $value
	 * @return Illuminate\Pagination\Paginator
	 */
	public function addQuery($key, $value)
	{
		$this->query[$key] = $value;

		return $this;
	}

	/**
	 * Get the items being paginated.
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
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
	 * Get an iterator for the items.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->items);
	}

	/**
	 * Get the count of the items being paginated.
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

	/**
	 * Determine if the given item exists.
	 *
	 * @param  mixed  $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->items);
	}

	/**
	 * Get the item at the given offset.
	 *
	 * @param  mixed  $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->items[$key];
	}

	/**
	 * Set the item at the given offset.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$this->items[$key] = $value;
	}

	/**
	 * Unset the item at the given key.
	 *
	 * @param  mixed  $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->items[$key]);
	}

}