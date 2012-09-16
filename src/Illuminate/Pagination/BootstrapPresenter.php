<?php namespace Illuminate\Pagination;

class BootstrapPresenter {

	/**
	 * The paginator instance being rendered.
	 *
	 * @var Illuminate\Pagination\Paginator
	 */
	protected $pager;

	/**
	 * The current page of the paginator.
	 *
	 * @var int
	 */
	protected $currentPage;

	/**
	 * The last available page of the paginator.
	 *
	 * @var int
	 */
	protected $lastPage;

	/**
	 * Create a new Bootstrap presenter instance.
	 *
	 * @param  Illuminate\Pagination\Paginator  $paginator
	 * @return void
	 */
	public function __construct(Paginator $paginator)
	{
		$this->pager = $paginator;
		$this->lastPage = $this->pager->getLastPage();
		$this->currentPage = $this->pager->getCurrentPage();
	}

	/**
	 * Render the Bootstrap pagination contents.
	 *
	 * @return string
	 */
	public function render()
	{
		// The hard-coded thirteen represents the minimum number of pages we need to
		// ba able to create a sliding page window. If we have less than that, we
		// will just render a simple range of page links insteadof the sliding.
		if ($this->lastPage < 13)
		{
			$content = $this->getPageRange(1, $this->lastPage);
		}
		else
		{
			$content = $this->getPageSlider();
		}

		return $this->getPrevious().$content.$this->getNext();
	}

	/**
	 * Create a range of pagination links.
	 *
	 * @param  int  $start
	 * @param  int  $end
	 */
	protected function getPageRange($start, $end)
	{
		$pages = array();

		for ($page = $start; $page <= $end; $page++)
		{
			// If the current page is equal to the page we're iterating on, we will create a
			// disabled link for that page. Otherwise, we can create a typical active one
			// for the link. These views use the "Twitter Bootstrap" styles by default.
			if ($this->currentPage == $page)
			{
				$pages[] = '<li class="disabled"><a href="#">'.$page.'</a></li>';
			}
			else
			{
				$pages[] = $this->getLink($page);
			}
		}

		return implode('', $pages);
	}

	/**
	 * Create a pagination slider window.
	 *
	 * @return string
	 */
	protected function getPageSlider()
	{
		$window = 6;

		// If the current page is very close to the beginning of the page range, we will
		// just render the beginning of the page range, followed by the last 2 of the
		// links in this list, since we will not have room to create a full slider.
		if ($this->currentPage <= $window)
		{
			$ending = $this->getFinish();

			return $this->getPageRange(1, $window + 2).$ending;
		}

		// If the current page is close to the ending of the page range we will just get
		// this first couple pages, followed by a larger window of these ending pages
		// since we're too close to the end of the list to create a full on slider.
		elseif ($this->currentPage >= $this->lastPage - $window)
		{
			$start = $this->lastPage - $window - 2;

			$content = $this->getPageRange($start, $this->lastPage);

			return $this->getStart().$content;
		}

		// If we have enough room on both sides of the current page to build a slider we
		// will surround it with both the beginning and ending caps, with this window
		// of pages in the middle providing a Google style sliding paginator setup.
		else
		{
			$start = $this->currentPage - 3;

			$content = $this->getPageRange($start, $this->currentPage + 3);

			return $this->getStart().$content.$this->getFinish();
		}
	}

	/**
	 * Create the beginning leader of a pagination slider.
	 *
	 * @return string
	 */
	protected function getStart()
	{
		return $this->getPageRange(1, 2).$this->getDots();
	}

	/**
	 * Create the ending cap of a pagination slider.
	 *
	 * @return string
	 */
	protected function getFinish()
	{
		$content = $this->getPageRange($this->lastPage - 1, $this->lastPage);

		return $this->getDots().$content;
	}

	/**
	 * Get the previous page pagination element.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getPrevious($text = '&laquo;')
	{
		if ($this->currentPage <= 1)
		{
			return '<li class="disabled"><a href="#">'.$text.'</a></li>';
		}
		else
		{
			$url = $this->pager->getUrl($this->currentPage - 1);

			return '<li class="active"><a href="'.$url.'">'.$text.'</a></li>';
		}
	}

	/**
	 * Get the next page pagination element.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getNext($text = '&raquo;')
	{
		if ($this->currentPage <= 1)
		{
			return '<li class="disabled"><a href="#">'.$text.'</a></li>';
		}
		else
		{
			$url = $this->pager->getUrl($this->currentPage + 1);

			return '<li class="active"><a href="'.$url.'">'.$text.'</a></li>';
		}
	}

	/**
	 * Get a pagination "dot" element.
	 *
	 * @return string
	 */
	protected function getDots()
	{
		return '<li class="disabled"><a href="#">...</a></li>';
	}

	/**
	 * Create a pagination slider link.
	 *
	 * @param  mixed   $page
	 * @return string
	 */
	protected function getLink($page)
	{
		$url = $this->pager->getUrl($page);

		return '<li class="active"><a href="'.$url.'">'.$page.'</a></li>';
	}

}