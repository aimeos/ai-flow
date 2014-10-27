<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage Logger
 */


/**
 * Log messages using the Flow logger.
 *
 * @package MW
 * @subpackage Logger
 */
class MW_Logger_Flow
	extends MW_Logger_Abstract
	implements MW_Logger_Interface
{
	private $_logger = null;


	/**
	 * Initializes the logger object.
	 *
	 * @param \TYPO3\Flow\Log\SystemLoggerInterface $logger Flow logger object
	 */
	public function __construct( \TYPO3\Flow\Log\SystemLoggerInterface $logger )
	{
		$this->_logger = $logger;
	}


	/**
	 * Writes a message to the configured log facility.
	 *
	 * @param string $message Message text that should be written to the log facility
	 * @param integer $priority Priority of the message for filtering
	 * @param string $facility Facility for logging different types of messages (e.g. message, auth, user, changelog)
	 * @throws MW_Logger_Exception If an error occurs in Zend_Log
	 * @see MW_Logger_Abstract for available log level constants
	 */
	public function log( $message, $priority = MW_Logger_Abstract::ERR, $facility = 'message' )
	{
		try
		{
			if( !is_scalar( $message ) ) {
				$message = json_encode( $message );
			}

			$this->_logger->log( $message, $this->_translatePriority( $priority ) );
		}
		catch( Exception $e )	{
			throw new MW_Logger_Exception( $e->getMessage(), $e->getCode(), $e );
		}
	}


	/**
	 * Translates the log priority to the log levels of Monolog.
	 *
	 * @param integer $priority Log level from MW_Logger_Abstract
	 * @return integer Log level from Monolog\Logger
	 * @throws MW_Logger_Exception If log level is unknown
	 */
	protected function _translatePriority( $priority )
	{
		switch( $priority )
		{
			case MW_Logger_Abstract::EMERG:
				return LOG_EMERG;
			case MW_Logger_Abstract::ALERT:
				return LOG_ALERT;
			case MW_Logger_Abstract::CRIT:
				return LOG_CRIT;
			case MW_Logger_Abstract::ERR:
				return LOG_ERR;
			case MW_Logger_Abstract::WARN:
				return LOG_WARNING;
			case MW_Logger_Abstract::NOTICE:
				return LOG_NOTICE;
			case MW_Logger_Abstract::INFO:
				return LOG_INFO;
			case MW_Logger_Abstract::DEBUG:
				return LOG_DEBUG;
			default:
				throw new MW_Logger_Exception( 'Invalid log level' );
		}
	}
}