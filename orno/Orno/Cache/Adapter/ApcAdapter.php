<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache\Adapter;

/**
 * ApcAdapter
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class ApcAdapter extends AbstractAdapter
{
    /**
     * If this is set to true it will use the APCu extension else it will use the APC (if loaded)
     *
     * @var bool
     */
    protected $apcu = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apcu = function_exists('apcu_fetch');
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function get($key)
    {
        if ($this->apcu) {
            return apcu_fetch($key);
        }

        return apc_fetch($key);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function set($key, $data, $expiry = null)
    {
        if (is_null($expiry)) {
            $expiry = $this->getExpiry();
        }

        if (is_string($expiry)) {
            $expiry = $this->convertExpiryString($expiry);
        }

        if ($this->apcu) {
            apcu_add($key, $data, $expiry);
        } else {
            apc_add($key, $data, $expiry);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function delete($key)
    {
        if ($this->apcu) {
            apcu_delete($key);
        } else {
            apc_delete($key);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function persist($key, $value)
    {
        $this->set($key, $value, 0);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function increment($key, $offset = 1)
    {
        if ($this->apcu) {
            apcu_inc($key, $offset);
        } else {
            apc_inc($key, $offset);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function decrement($key, $offset = 1)
    {
        if ($this->apcu) {
            apcu_dec($key, $offset);
        } else {
            apc_dec($key, $offset);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function flush()
    {
        if ($this->apcu) {
            apcu_clear_cache('user');
        } else {
            apc_clear_cache('user');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function setConfig(array $config)
    {
        return $this;
    }
}
