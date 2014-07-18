<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache;

use Orno\Cache\Adapter\AdapterInterface;

/**
 * Cache
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Cache implements Adapter\AdapterInterface
{
    /**
     * @var \Orno\Cache\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param \Orno\Cache\Adapter\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->getAdapter()->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function set($key, $data, $expiry = null)
    {
        $this->getAdapter()->set($key, $data, $expiry);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function delete($key)
    {
        $this->getAdapter()->delete($key);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function persist($key, $value)
    {
        $this->getAdapter()->persist($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function increment($key, $offset = 1)
    {
        $this->getAdapter()->increment($key, $offset);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function decrement($key, $offset = 1)
    {
        $this->getAdapter()->decrement($key, $offset);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function flush()
    {
        $this->getAdapter()->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function setConfig(array $config)
    {
        $this->getAdapter()->setConfig($config);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Cache
     */
    public function setDefaultExpiry($expiry)
    {
        $this->getAdapter()->setDefaultExpiry($expiry);

        return $this;
    }

    /**
     * Gets the adapter
     *
     * @return \Orno\Cache\Adapter\CacheAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the adapter
     *
     * @param  \Orno\Cache\Adapter\AdapterInterface $adapter
     * @return \Orno\Cache\Cache
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }
}
