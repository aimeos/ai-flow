<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014-2018
 */


namespace Aimeos\MW\Session;


/**
 * Test class for \Aimeos\MW\Session\Flow.
 */
class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		if( class_exists( '\\Neos\\Flow\\Session\\Session' ) === false ) {
			$this->markTestSkipped( 'Class \\Neos\\Flow\\Session\\Session not found' );
		}

		$session = new \Neos\Flow\Session\TransientSession();
		$session->start();

		$this->object = new \Aimeos\MW\Session\Flow( $session );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetDefault()
	{
		$this->assertEquals( null, $this->object->get( 'notexist' ) );
	}


	public function testGetSet()
	{
		$this->assertInstanceof( '\Aimeos\MW\Session\Iface', $this->object->set( 'key', 'value' ) );
		$this->assertEquals( 'value', $this->object->get( 'key' ) );
	}


	public function testGetSetArray()
	{
		$this->assertInstanceof( '\Aimeos\MW\Session\Iface', $this->object->set( 'key', array( 'value' ) ) );
		$this->assertEquals( array( 'value' ), $this->object->get( 'key' ) );
	}
}
