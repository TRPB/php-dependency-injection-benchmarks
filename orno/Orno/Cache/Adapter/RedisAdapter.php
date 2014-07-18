<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Cache\Adapter;

use Predis\Client;

/**
 * RedisAdapter
 */
class RedisAdapter extends AbstractAdapter
{
    /**
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * Constructor
     *
     * @param \Predis\Client $redis
     * @param array          $config
     */
    public function __construct(Client $redis, array $config = [])
    {
        $this->redis = $redis;
        $this->setConfig($config);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $return = $this->redis->get($key);

        return (! is_null($return)) ? $return : false;
    }

    /**
     * {@inheirtdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function set($key, $data, $expiry = null)
    {
        if (is_null($expiry)) {
            $expiry = $this->getExpiry();
        }

        if (is_string($expiry)) {
            $expiry = $this->convertExpiryString($expiry);
        }

        $this->redis->set($key, $data);
        $this->redis->expire($key, $expiry);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function delete($key)
    {
        $this->redis->del($key);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function persist($key, $value)
    {
        $this->redis->set($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function increment($key, $offset = 1)
    {
        $this->redis->incrby($key, $offset);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function decrement($key, $offset = 1)
    {
        $this->redis->decrby($key, $offset);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function flush()
    {
        $this->redis->flushall();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    public function setConfig(array $config)
    {
        if (array_key_exists('servers', $config)) {
            $this->addServers($config['servers']);
        }

        if (array_key_exists('expiry', $config)) {
            $this->setDefaultExpiry($config['expiry']);
        }

        return $this;
    }

    /**
     * Adds a server to the redis configuration
     *
     * @param  string $host
     * @param  int    $port
     * @param  int    $weight
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    protected function addServer($host, $port)
    {
        $this->redis = Client::create([
            'host' => $host,
            'port' => $port
        ]);

        return $this;
    }

    /**
     * Adds an array of servers to the redis configuration
     *
     * @param  array $servers
     * @return \Orno\Cache\Adapter\RedisAdapter
     */
    protected function addServers(array $servers)
    {
        $this->redis = new Client($servers);

        return $this;
    }
}
