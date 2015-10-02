<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage Cache
 */


/**
 * Flow caching implementation.
 *
 * @package MW
 * @subpackage Cache
 */
class MW_Cache_Flow
	extends MW_Cache_Abstract
	implements MW_Cache_Interface
{
	private $object;
	private $prefix;


	/**
	 * Initializes the object instance.
	 *
	 * @param array $config List of configuration values
	 * @param TYPO3\Flow\Cache\Frontend\FrontendInterface $cache TYPO3 cache object
	 */
	public function __construct( array $config, TYPO3\Flow\Cache\Frontend\FrontendInterface $cache )
	{
		$this->prefix = ( isset( $config['siteid'] ) ? $config['siteid'] . '-' : '' );
		$this->object = $cache;
	}


	/**
	 * Removes the cache entry identified by the given key.
	 *
	 * @inheritDoc
	 *
	 * @param string $key Key string that identifies the single cache entry
	 */
	public function delete( $key )
	{
		$this->object->remove( $this->prefix . $key );
	}


	/**
	 * Removes the cache entries identified by the given keys.
	 *
	 * @inheritDoc
	 *
	 * @param string[] $keys List of key strings that identify the cache entries
	 * 	that should be removed
	 */
	public function deleteList( array $keys )
	{
		foreach( $keys as $key ) {
			$this->object->remove( $this->prefix . $key );
		}
	}


	/**
	 * Removes the cache entries identified by the given tags.
	 *
	 * @inheritDoc
	 *
	 * @param string[] $tags List of tag strings that are associated to one or more
	 * 	cache entries that should be removed
	 */
	public function deleteByTags( array $tags )
	{
		foreach( $tags as $tag ) {
			$this->object->flushByTag( $this->prefix . $tag );
		}
	}


	/**
	 * Removes all entries for the current site from the cache.
	 *
	 * @inheritDoc
	 */
	public function flush()
	{
		if( $this->prefix ) {
			$this->object->flushByTag( $this->prefix . 'siteid' );
		} else {
			$this->object->flush();
		}
	}


	/**
	 * Returns the value of the requested cache key.
	 *
	 * @inheritDoc
	 *
	 * @param string $name Path to the requested value like tree/node/classname
	 * @param string $default Value returned if requested key isn't found
	 * @return mixed Value associated to the requested key
	 */
	public function get( $name, $default = null )
	{
		if( ( $entry = $this->object->get( $this->prefix . $name ) ) !== false ) {
			return $entry;
		}

		return $default;
	}


	/**
	 * Returns the cached values for the given cache keys.
	 *
	 * @inheritDoc
	 *
	 * @param string[] $keys List of key strings for the requested cache entries
	 * @return array Associative list of key/value pairs for the requested cache
	 * 	entries. If a cache entry doesn't exist, neither its key nor a value
	 * 	will be in the result list
	 */
	public function getList( array $keys )
	{
		$result = array();

		foreach( $keys as $key )
		{
			if( ( $entry = $this->object->get( $this->prefix . $key ) ) !== false ) {
				$result[$key] = $entry;
			}
		}

		return $result;
	}


	/**
	 * Returns the cached keys and values associated to the given tags.
	 *
	 * @inheritDoc
	 *
	 * @param string[] $tags List of tag strings associated to the requested cache entries
	 * @return array Associative list of key/value pairs for the requested cache
	 * 	entries. If a tag isn't associated to any cache entry, nothing is returned
	 * 	for that tag
	 */
	public function getListByTags( array $tags )
	{
		$result = array();
		$len = strlen( $this->prefix );

		foreach( $tags as $tag )
		{
			foreach( $this->object->getByTag( $this->prefix . $tag ) as $key => $value )
			{
				if( strncmp( $key, $this->prefix, $len ) === 0 ) {
					$result[ substr( $key, $len ) ] = $value;
				} else {
					$result[$key] = $value;
				}

			}
		}

		return $result;
	}


	/**
	 * Sets the value for the given key in the cache.
	 *
	 * @inheritDoc
	 *
	 * @param string $name Key string for the given value like product/id/123
	 * @param mixed $value Value string that should be stored for the given key
	 * @param array $tags List of tag strings that should be assoicated to the
	 * 	given value in the cache
	 * @param string|null $expires Date/time string in "YYYY-MM-DD HH:mm:ss"
	 * 	format when the cache entry expires
	 */
	public function set( $name, $value, array $tags = array(), $expires = null )
	{
		if( $expires !== null && ( $timestamp = strtotime( $expires ) ) !== false ) {
			$expires = $timestamp;
		} else {
			$expires = null;
		}

		$tagList = ( $this->prefix ? array( $this->prefix . 'siteid' ) : array() );

		foreach( $tags as $tag ) {
			$tagList[] = $this->prefix . $tag;
		}

		$this->object->set( $this->prefix . $name, $value, $tagList, $expires );
	}


	/**
	 * Adds or overwrites the given key/value pairs in the cache, which is much
	 * more efficient than setting them one by one using the set() method.
	 *
	 * @inheritDoc
	 *
	 * @param array $pairs Associative list of key/value pairs. Both must be
	 * 	a string
	 * @param array $tags Associative list of key/tag or key/tags pairs that should be
	 * 	associated to the values identified by their key. The value associated
	 * 	to the key can either be a tag string or an array of tag strings
	 * @param array $expires Associative list of key/datetime pairs.
	 */
	public function setList( array $pairs, array $tags = array(), array $expires = array() )
	{
		foreach( $pairs as $key => $value )
		{
			$tagList = ( isset( $tags[$key] ) ? (array) $tags[$key] : array() );
			$keyExpire = ( isset( $expires[$key] ) ? $expires[$key] : null );

			$this->set( $key, $value, $tagList, $keyExpire );
		}
	}
}
