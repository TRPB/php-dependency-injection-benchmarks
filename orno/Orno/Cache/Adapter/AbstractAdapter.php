<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Cache\Adapter;

/**
 * AbstractAdapter
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Default expiry time for cahced keys in seconds
     *
     * @var integer
     */
    protected $expiry = 60;

    /**
     * {@inheritdoc}
     */
    abstract public function get($key);

    /**
     * {@inheritdoc}
     */
    abstract public function set($key, $data, $expiry = null);

    /**
     * {@inheritdoc}
     */
    abstract public function delete($key);

    /**
     * {@inheritdoc}
     */
    abstract public function persist($key, $value);

    /**
     * {@inheritdoc}
     */
    abstract public function increment($key, $offset = 1);

    /**
     * {@inheritdoc}
     */
    abstract public function decrement($key, $offset = 1);

    /**
     * {@inheritdoc}
     */
    abstract public function flush();

    /**
     * {@inheritdoc}
     */
    abstract public function setConfig(array $config);

    /**
     * Gets the expiry time
     *
     * @return int
     */
    protected function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setDefaultExpiry($expiry)
    {
        if (is_string($expiry)) {
            $expiry = $this->convertExpiryString($expiry);
        }

        $this->expiry = (int) $expiry;
    }



    /**
     * Convert an expiry string to seconds ('10m30s' => 660)
     *
     * @param  string $string
     * @return integer
     */
    protected function convertExpiryString($string)
    {
        $string = preg_replace("/[^0-9A-Za-z]/", null, $string);
        $string = strtolower($string);

        preg_match_all("/(\d)([w|d|h|m|s])/", $string, $match);

        $times = $match[1];
        $delimiters = $match[2];
        $expiry = 0;

        foreach ($delimiters as $key => $delimiter) {
            switch ($delimiter) {
                case 'w':
                    $multiplier = 60 * 60 * 24 * 7;
                    break;
                case 'd':
                    $multiplier = 60 * 60 * 24;
                    break;
                case 'h':
                    $multiplier = 60 * 60;
                    break;
                case 'm':
                    $multiplier = 60;
                    break;
                case 's':
                default:
                    $multiplier = 0;
                    break;
            }

            $expiry += (int) $times[$key] * $multiplier;
        }

        return $expiry;
    }
}
