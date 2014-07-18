<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache\Adapter;

/**
 * Cache Adapter Interface
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
interface AdapterInterface
{
    /**
     * Gets the value from the adapter
     *
     * @param string $key
     */
    public function get($key);

    /**
     * Set the value in the adapter
     *
     * @param string  $key
     * @param mixed   $data
     * @param integer $expiry
     */
    public function set($key, $data, $expiry = null);

    /**
     * Deletes the value in the adapter
     *
     * @param string $key
     */
    public function delete($key);

    /**
     * Add an item to the cache to be stored until the cache is flushed
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function persist($key, $value);

    /**
     * Increment a value in the cache
     *
     * @param  string  $key
     * @param  mixed   $offset
     */
    public function increment($key, $offset = 1);

    /**
     * Decrement a value in the cache
     *
     * @param  string  $key
     * @param  mixed   $offset
     */
    public function decrement($key, $offset = 1);

    /**
     * Flush all items from the cache
     */
    public function flush();

    /**
     * Sets the configuration for the adapter
     *
     * Example:
     *
     * [
     *      'servers' => [
     *          ['127.0.0.1', 11211, 1],
     *          ['192.168.0.1', 11211, 3],
     *      ],
     *      'expiry' => 120, // expiry in seconds
     *      'expiry' => '5w 9d 12h 24m 55s' // expiry in time string
     * ]
     *
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Set a default expiry time for cached keys
     *
     * Example:
     *
     * $expiry = 100 // for 100 seconds
     * or
     * $expiry = '10m 30s' // for 660 seconds
     *
     * @param integer|string $expiry
     */
    public function setDefaultExpiry($expiry);
}
