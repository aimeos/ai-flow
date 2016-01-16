<?php

/**
 * @license LGPLv3, http://www.gnu.org/copyleft/lgpl.html
 * @copyright Aimeos (aimeos.org), 2015
 * @package MAdmin
 * @subpackage Cache
 */


namespace Aimeos\MAdmin\Cache\Proxy;


/**
 * Cache proxy for creating the Flow cache object on demand.
 *
 * @package MAdmin
 * @subpackage Cache
 */
class Flow
	extends \Aimeos\MAdmin\Cache\Proxy\Standard
	implements \Aimeos\MW\Cache\Iface
{
	private $object;
	private $context;
	private $cache;


	/**
	 * Initializes the cache controller.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context MShop context object
	 * @param \TYPO3\Flow\Cache\Frontend\StringFrontend $cache Flow cache object
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\Flow\Cache\Frontend\StringFrontend $cache )
	{
		$this->context = $context;
		$this->cache = $cache;
	}


	/**
	 * Returns the cache object or creates a new one if it doesn't exist yet.
	 *
	 * @return \Aimeos\MW\Cache\Iface Cache object
	 */
	protected function getObject()
	{
		if( !isset( $this->object ) )
		{
			$siteid = $this->context->getLocale()->getSiteId();
			$conf = array( 'siteid' => $this->context->getConfig()->get( 'madmin/cache/prefix' ) . $siteid );
			$this->object = \Aimeos\MW\Cache\Factory::createManager( 'Flow', $conf, $this->cache );
		}

		return $this->object;
	}
}
