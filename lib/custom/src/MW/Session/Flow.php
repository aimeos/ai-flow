<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage Session
 */


/**
 * Implementation using Flow sessions.
 *
 * @package MW
 * @subpackage Session
 */
class MW_Session_Flow
	implements MW_Session_Interface
{
	private $_object;


	/**
	 * Initializes the object.
	 *
	 * @param TYPO3\Flow\Session\SessionInterface $object Flow session object
	 */
	public function __construct( TYPO3\Flow\Session\SessionInterface $object )
	{
		$this->_object = $object;
	}


	/**
	 * Returns the value of the requested session key.
	 *
	 * If the returned value wasn't a string, it's decoded from its string representation.
	 *
	 * @param string $name Key of the requested value in the session
	 * @param mixed $default Value returned if requested key isn't found
	 * @return mixed Value associated to the requested key
	 */
	public function get( $name, $default = null )
	{
		if( $this->_object->hasKey( $name ) !== true ) {
			return $default;
		}

		return $this->_object->getData( $name );
	}


	/**
	 * Sets the value for the specified key.
	 *
	 * If the value isn't a string, it's serialized and decoded again when using the get() method.
	 *
	 * @param string $name Key to the value which should be stored in the session
	 * @param mixed $value Value that should be associated with the given key
	 * @return void
	 */
	public function set( $name, $value )
	{
		$this->_object->putData( $name, $value );
	}
}
