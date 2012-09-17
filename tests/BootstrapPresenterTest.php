<?php

use Mockery as m;
use Illuminate\Pagination\BootstrapPresenter;

class BootstrapPresenterTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testPresenterCanBeCreated()
	{
		$presenter = $this->getPresenter();
	}


	public function testPreviousLinkCanBeRendered()
	{
		$output = $this->getPresenter()->getPrevious();
		
		$this->assertEquals('<li class="disabled"><a href="#">&laquo;</a></li>', $output);

		$presenter = $this->getPresenter();
		$presenter->setCurrentPage(2);
		$output = $presenter->getPrevious();

		$this->assertEquals('<li class="active"><a href="http://foo.com?page=1">&laquo;</a></li>', $output);
	}


	public function testNextLinkCanBeRendered()
	{
		$presenter = $this->getPresenter();
		$presenter->setCurrentPage(2);
		$output = $presenter->getNext();

		$this->assertEquals('<li class="disabled"><a href="#">&raquo;</a></li>', $output);

		$presenter = $this->getPresenter();
		$presenter->setCurrentPage(1);
		$output = $presenter->getNext();

		$this->assertEquals('<li class="active"><a href="http://foo.com?page=2">&raquo;</a></li>', $output);
	}


	public function getPresenter()
	{
		$paginator = m::mock('Illuminate\Pagination\Paginator');
		$paginator->shouldReceive('getLastPage')->once()->andReturn(2);
		$paginator->shouldReceive('getCurrentPage')->once()->andReturn(1);
		$paginator->shouldReceive('getUrl')->andReturnUsing(function($page) { return 'http://foo.com?page='.$page; });

		return new BootstrapPresenter($paginator);
	}

}