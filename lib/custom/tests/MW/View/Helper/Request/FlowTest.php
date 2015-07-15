<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


class MW_View_Helper_Request_FlowTest extends MW_Unittest_Testcase
{
	private $_object;
	private $_mock;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		if( !class_exists( '\TYPO3\Flow\Http\Request' ) ) {
			$this->markTestSkipped( '\TYPO3\Flow\Http\Request is not available' );
		}

		$this->_mock = $this->getMockBuilder( '\TYPO3\Flow\Http\Request' )
			->setConstructorArgs( array( array(), array(), array(), array() ) )->getMock();

		$view = new \MW_View_Default();
		$this->_object = new MW_View_Helper_Request_Flow( $view, $this->_mock, array() );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->_object, $this->mock );
	}


	public function testTransform()
	{
		$this->assertInstanceOf( 'MW_View_Helper_Request_Flow', $this->_object->transform() );
	}


	public function testGetBody()
	{
		$this->_mock->expects( $this->once() )->method( 'getContent' )
			->will( $this->returnValue( 'body' ) );

		$this->assertEquals( 'body', $this->_object->transform()->getBody() );
	}


	public function testGetClientAddress()
	{
		$this->_mock->expects( $this->once() )->method( 'getClientIpAddress' )
			->will( $this->returnValue( '127.0.0.1' ) );

		$this->assertEquals( '127.0.0.1', $this->_object->transform()->getClientAddress() );
	}
}
