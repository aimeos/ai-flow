<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014-2018
 */


namespace Aimeos\MW\Cache;


class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $mock;


	protected function setUp()
	{
		if( interface_exists( 'Neos\Cache\Frontend\FrontendInterface' ) === false ) {
			$this->markTestSkipped( 'Class \\Neos\\Cache\\Frontend\\FrontendInterface not found' );
		}

		$this->mock = $this->getMockBuilder( 'Neos\Cache\Frontend\FrontendInterface' )->getMock();
		$this->object = new \Aimeos\MW\Cache\Flow( [], $this->mock );
	}


	protected function tearDown()
	{
		unset( $this->mock, $this->object );
	}


	public function testCleanup()
	{
		$this->mock->expects( $this->once() )->method( 'collectGarbage' );
		$this->assertTrue( $this->object->cleanup() );
	}


	public function testClear()
	{
		$this->mock->expects( $this->once() )->method( 'flush' );
		$this->assertTrue( $this->object->clear() );
	}


	public function testClearWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'flushByTag' )->with( $this->equalTo( '1-siteid' ) );
		$this->assertTrue( $object->clear() );
	}


	public function testDelete()
	{
		$this->mock->expects( $this->once() )->method( 'remove' )->with( $this->equalTo( 'key' ) );
		$this->assertTrue( $this->object->delete( 'key' ) );
	}


	public function testDeleteWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'remove' )->with( $this->equalTo( '1-key' ) );
		$this->assertTrue( $object->delete( 'key' ) );
	}


	public function testDeleteMultiple()
	{
		$this->mock->expects( $this->exactly( 2 ) )->method( 'remove' )->with( $this->equalTo( 'key' ) );
		$this->assertTrue( $this->object->deleteMultiple( array( 'key', 'key' ) ) );
	}


	public function testDeleteMultipleWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'remove' )->with( $this->equalTo( '1-key' ) );
		$this->assertTrue( $object->deleteMultiple( array( 'key' ) ) );
	}


	public function testDeleteByTags()
	{
		$this->mock->expects( $this->exactly( 2 ) )->method( 'flushByTag' )->with( $this->equalTo( 'tag' ) );
		$this->assertTrue( $this->object->deleteByTags( array( 'tag', 'tag' ) ) );
	}


	public function testDeleteByTagsWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'flushByTag' )->with( $this->equalTo( '1-tag' ) );
		$this->assertTrue( $object->deleteByTags( array( 'tag' ) ) );
	}


	public function testGet()
	{
		$this->mock->expects( $this->once() )->method( 'get' )
			->with( $this->equalTo( 'key' ) )->will( $this->returnValue( 'value' ) );

		$this->assertEquals( 'value', $this->object->get( 'key', 'default' ) );
	}


	public function testGetDefault()
	{
		$this->mock->expects( $this->once() )->method( 'get' )
		->with( $this->equalTo( 'key' ) )->will( $this->returnValue( false ) );

		$this->assertEquals( 'default', $this->object->get( 'key', 'default' ) );
	}


	public function testGetWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'get' )->with( $this->equalTo( '1-key' ) );
		$object->get( 'key', 'default' );
	}


	public function testGetMultiple()
	{
		$this->mock->expects( $this->exactly( 2 ) )->method( 'get' )
			->will( $this->returnValue( 'value' ) );

		$expected = array( 'key1' => 'value', 'key2' => 'value' );
		$this->assertEquals( $expected, $this->object->getMultiple( array( 'key1', 'key2' ) ) );
	}


	public function testGetMultipleWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'get' )->with( $this->equalTo( '1-key' ) );
		$object->getMultiple( array( 'key' ) );
	}


	public function testHas()
	{
		$this->mock->expects( $this->once() )->method( 'has' )->will( $this->returnValue( true ) );
		$this->assertTrue( $this->object->has( 'key' ) );
	}


	public function testSet()
	{
		$this->mock->expects( $this->once() )->method( 'set' )
			->with(
				$this->equalTo( 'key' ), $this->equalTo( 'value' ),
				$this->equalTo( array( 'tag' ) ), $this->greaterThan( 0 )
			);

		$this->assertTrue( $this->object->set( 'key', 'value', '2100-01-01 00:00:00', array( 'tag' ) ) );
	}


	public function testSetWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'set' )
			->with(
				$this->equalTo( '1-key' ), $this->equalTo( 'value' ),
				$this->equalTo( array( '1-siteid', '1-tag' ) ), $this->equalTo( null )
			);

		$this->assertTrue( $object->set( 'key', 'value', null, array( 'tag' ) ) );
	}


	public function testSetMultiple()
	{
		$this->mock->expects( $this->once() )->method( 'set' )
			->with(
				$this->equalTo( 'key' ), $this->equalTo( 'value' ),
				$this->equalTo( array( 'tag' ) ), $this->greaterThan( 0 )
			);

		$this->assertTrue( $this->object->setMultiple( ['key' => 'value'], '2100-01-01 00:00:00', ['tag'] ) );
	}


	public function testSetMultipleWithSiteId()
	{
		$object = new \Aimeos\MW\Cache\Flow( array( 'siteid' => 1 ), $this->mock );

		$this->mock->expects( $this->once() )->method( 'set' )
			->with(
				$this->equalTo( '1-key' ), $this->equalTo( 'value' ),
				$this->equalTo( array( '1-siteid', '1-tag' ) ), $this->equalTo( null )
			);

		$this->assertTrue( $object->setMultiple( ['key' => 'value'], null, ['tag'] ) );
	}

}
