<?php

/**
 * Create a range of pagination links.
 *
 * @param  int  $start
 * @param  int  $end
 * @param  Illuminate\Pagination\Paginator
 */
function page_range($start, $end, $paginator)
{
	$pages = array();

	for ($page = $start; $page <= $end; $page++)
	{
		// If the current page is equal to the page we're iterating on, we will create a
		// disabled link for that page. Otherwise, we can create a typical active one
		// for the link. These views use the "Twitter Bootstrap" styles by default.
		if ($paginator->getCurrentPage() == $page)
		{
			$pages[] = '<li class="disabled"><a href="#">'.$page.'</a></li>';
		}
		else
		{
			$pages[] = page_link($page, $paginator);
		}
	}

	return implode('', $pages);
}

/**
 * Create a pagination slider window.
 *
 * @param  int     $currentPage
 * @param  int     $lastPage
 * @param  Illuminate\Pagination\Paginator  $paginator
 * @return string
 */
function page_slider($currentPage, $lastPage, $paginator)
{
	$window = 6;

	// If the current page is very close to the beginning of the page range, we will
	// just render the beginning of the page range, followed by the last 2 of the
	// links in this list, since we will not have room to create a full slider.
	if ($currentPage <= $window)
	{
		$ending = page_end($lastPage, $paginator);

		return page_range(1, $window + 2, $paginator).$ending;
	}

	// If the current page is close to the ending of the page range we will just get
	// this first couple pages, followed by a larger window of these ending pages
	// since we're too close to the end of the list to create a full on slider.
	elseif ($currentPage >= $lastPage - $window)
	{
		$content = page_range($lastPage - $window - 2, $lastPage, $paginator);

		return page_begin($paginator).$content;
	}

	// If we have enough room on both sides of the current page to build a slider we
	// will surround it with both the beginning and ending caps, with this window
	// of pages in the middle providing a Google style sliding paginator setup.
	else
	{
		$content = page_range($currentPage - 3, $currentPage + 3, $paginator);

		return page_begin($paginator).$content.page_end($lastPage, $paginator);
	}
}

/**
 * Create the beginning leader of a pagination slider.
 *
 * @param  Illuminate\Pagination\Paginator  $paginator
 * @return string
 */
function page_begin($paginator)
{
	return page_range(1, 2, $paginator).page_dots();
}

/**
 * Create the ending cap of a pagination slider.
 *
 * @param  int     $lastPage
 * @param  Illuminate\Pagination\Paginator  $paginator
 * @return string
 */
function page_end($lastPage, $paginator)
{
	$content = page_range($lastPage - 1, $lastPage, $paginator);

	return page_dots().$content;
}

/**
 * Get the previous page pagination element.
 *
 * @param  int  $currentPage
 * @param  Illuminate\Pagination\Paginator  $paginator
 */
function page_previous($currentPage, $paginator)
{
	if ($currentPage <= 1)
	{
		return '<li class="disabled"><a href="#">&laquo;</a></li>';
	}
	else
	{
		$url = $paginator->getUrl($currentPage - 1);

		return '<li class="active"><a href="'.$url.'">&laquo;</a></li>';
	}
}

/**
 * Get the next page pagination element.
 *
 * @param  int  $currentPage
 * @param  Illuminate\Pagination\Paginator  $paginator
 */
function page_next($currentPage, $lastPage)
{
	if ($currentPage <= 1)
	{
		return '<li class="disabled"><a href="#">&raquo;</a></li>';
	}
	else
	{
		$url = $paginator->getUrl($currentPage + 1);

		return '<li class="active"><a href="'.$url.'">&raquo;</a></li>';
	}
}

/**
 * Get a pagination "dot" element.
 *
 * @return string
 */
function page_dots()
{
	return '<li class="disabled"><a href="#">...</a></li>';
}

/**
 * Create a pagination slider link.
 *
 * @param  int  $page
 * @param  Illuminate\Pagination\Paginator  $paginator
 * @param  string  $status
 */
function page_link($page, $paginator, $status = 'active')
{
	$url = $paginator->getUrl($page);

	return '<li class="'.$status.'"><a href="'.$url.'">'.$page.'</a></li>';
}