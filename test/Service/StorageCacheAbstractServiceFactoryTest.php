<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Cache\Service;

use Zend\Cache;
use Zend\ServiceManager\ServiceManager;

/**
 * @group      Zend_Cache
 */
class StorageCacheAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $sm;

    public function setUp()
    {
        Cache\StorageFactory::resetAdapterPluginManager();
        Cache\StorageFactory::resetPluginManager();
        $this->sm = new ServiceManager();
        $this->sm->setService('Config', ['caches' => [
            'Memory' => [
                'adapter' => 'Memory',
                'plugins' => ['Serializer', 'ClearExpiredByFactor'],
            ],
            'Foo' => [
                'adapter' => 'Memory',
                'plugins' => ['Serializer', 'ClearExpiredByFactor'],
            ],
        ]]);
        $this->sm->addAbstractFactory('Zend\Cache\Service\StorageCacheAbstractServiceFactory');
    }

    public function tearDown()
    {
        Cache\StorageFactory::resetAdapterPluginManager();
        Cache\StorageFactory::resetPluginManager();
    }

    public function testCanLookupCacheByName()
    {
        $this->assertTrue($this->sm->has('Memory'));
        $this->assertTrue($this->sm->has('Foo'));
    }

    public function testCanRetrieveCacheByName()
    {
        $cacheA = $this->sm->get('Memory');
        $this->assertInstanceOf('Zend\Cache\Storage\Adapter\Memory', $cacheA);

        $cacheB = $this->sm->get('Foo');
        $this->assertInstanceOf('Zend\Cache\Storage\Adapter\Memory', $cacheB);

        $this->assertNotSame($cacheA, $cacheB);
    }

    public function testInvalidCacheServiceNameWillBeIgnored()
    {
        $this->assertFalse($this->sm->has('invalid'));
    }
}
