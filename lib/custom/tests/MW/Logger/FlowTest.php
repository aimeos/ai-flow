<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 */


namespace Aimeos\MW\Logger;


/**
 * Test class for \Aimeos\MW\Logger\Flow.
 */
class FlowTest extends \PHPUnit_Framework_TestCase
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
		if( class_exists( '\\TYPO3\\Flow\\Log\\Logger' ) === false ) {
			$this->markTestSkipped( 'Class \\TYPO3\\Flow\\Log\\Logger not found' );
		}

		$be = new \TYPO3\Flow\Log\Backend\FileBackend();
		$be->setSeverityThreshold( LOG_ERR );
		$be->setLogFileURL( 'flow.log' );

		$log = new \TYPO3\Flow\Log\Logger();
		$log->addBackend( $be );

		$this->object = new \Aimeos\MW\Logger\Flow( $log );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		@unlink( 'flow.log' );
	}


	public function testLog()
	{
		$this->object->log( 'error' );
		$this->assertRegExp( '/^[^ ]+ [^ ]+ [0-9]+[ ]+ERROR[ ]+MW[ ]+error/', file_get_contents( 'flow.log' ) );
	}


	public function testNonScalarLog()
	{
		$this->object->log( array( 'error', 'error2', 2 ) );
		$this->assertRegExp( '/^[^ ]+ [^ ]+ [0-9]+[ ]+ERROR[ ]+MW[ ]+\["error","error2",2\]/', file_get_contents( 'flow.log' ) );
	}


	public function testLogDebug()
	{
		$this->object->log( 'debug', \Aimeos\MW\Logger\Base::DEBUG );
		$this->assertEquals( '', file_get_contents( 'flow.log' ) );
	}


	public function testBadPriority()
	{
		$this->setExpectedException( '\\Aimeos\\MW\\Logger\\Exception' );
		$this->object->log( 'error', -1 );
	}
}
