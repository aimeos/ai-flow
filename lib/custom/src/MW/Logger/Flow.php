<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage Logger
 */


namespace Aimeos\MW\Logger;


/**
 * Log messages using the Flow logger.
 *
 * @package MW
 * @subpackage Logger
 */
class Flow
	extends \Aimeos\MW\Logger\Base
	implements \Aimeos\MW\Logger\Iface
{
	private $logger = null;


	/**
	 * Initializes the logger object.
	 *
	 * @param \TYPO3\Flow\Log\SystemLoggerInterface $logger Flow logger object
	 */
	public function __construct( \TYPO3\Flow\Log\SystemLoggerInterface $logger )
	{
		$this->logger = $logger;
	}


	/**
	 * Writes a message to the configured log facility.
	 *
	 * @param string $message Message text that should be written to the log facility
	 * @param integer $priority Priority of the message for filtering
	 * @param string $facility Facility for logging different types of messages (e.g. message, auth, user, changelog)
	 * @throws \Aimeos\MW\Logger\Exception If an error occurs in Zend_Log
	 * @see \Aimeos\MW\Logger\Base for available log level constants
	 */
	public function log( $message, $priority = \Aimeos\MW\Logger\Base::ERR, $facility = 'message' )
	{
		try
		{
			if( !is_scalar( $message ) ) {
				$message = json_encode( $message );
			}

			$this->logger->log( $message, $this->translatePriority( $priority ) );
		}
		catch( \Exception $e )	{
			throw new \Aimeos\MW\Logger\Exception( $e->getMessage(), $e->getCode(), $e );
		}
	}


	/**
	 * Translates the log priority to the log levels of Monolog.
	 *
	 * @param integer $priority Log level from \Aimeos\MW\Logger\Base
	 * @return integer Log level from Monolog\Logger
	 * @throws \Aimeos\MW\Logger\Exception If log level is unknown
	 */
	protected function translatePriority( $priority )
	{
		switch( $priority )
		{
			case \Aimeos\MW\Logger\Base::EMERG:
				return LOG_EMERG;
			case \Aimeos\MW\Logger\Base::ALERT:
				return LOG_ALERT;
			case \Aimeos\MW\Logger\Base::CRIT:
				return LOG_CRIT;
			case \Aimeos\MW\Logger\Base::ERR:
				return LOG_ERR;
			case \Aimeos\MW\Logger\Base::WARN:
				return LOG_WARNING;
			case \Aimeos\MW\Logger\Base::NOTICE:
				return LOG_NOTICE;
			case \Aimeos\MW\Logger\Base::INFO:
				return LOG_INFO;
			case \Aimeos\MW\Logger\Base::DEBUG:
				return LOG_DEBUG;
			default:
				throw new \Aimeos\MW\Logger\Exception( 'Invalid log level' );
		}
	}
}