<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class ProjectServiceContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    public function __construct()
    {
        $this->services = array();
        $this->normalizedIds = array(
            'a' => 'A',
            'b' => 'B',
            'c' => 'C',
            'd' => 'D',
            'e' => 'E',
            'f' => 'F',
            'g' => 'G',
            'h' => 'H',
            'i' => 'I',
            'j' => 'J',
        );
        $this->methodMap = array(
            'A' => 'getAService',
            'B' => 'getBService',
            'C' => 'getCService',
            'D' => 'getDService',
            'E' => 'getEService',
            'F' => 'getFService',
            'G' => 'getGService',
            'H' => 'getHService',
            'I' => 'getIService',
            'J' => 'getJService',
        );

        $this->aliases = array();
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    /**
     * {@inheritdoc}
     */
    public function isCompiled()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isFrozen()
    {
        @trigger_error(sprintf('The %s() method is deprecated since version 3.3 and will be removed in 4.0. Use the isCompiled() method instead.', __METHOD__), E_USER_DEPRECATED);

        return true;
    }

    /**
     * Gets the public 'A' service.
     *
     * @return \A
     */
    protected function getAService()
    {
        return new \A();
    }

    /**
     * Gets the public 'B' service.
     *
     * @return \B
     */
    protected function getBService()
    {
        return new \B(new \A());
    }

    /**
     * Gets the public 'C' service.
     *
     * @return \C
     */
    protected function getCService()
    {
        return new \C(new \B(new \A()));
    }

    /**
     * Gets the public 'D' service.
     *
     * @return \D
     */
    protected function getDService()
    {
        return new \D(new \C(new \B(new \A())));
    }

    /**
     * Gets the public 'E' service.
     *
     * @return \E
     */
    protected function getEService()
    {
        return new \E(new \D(new \C(new \B(new \A()))));
    }

    /**
     * Gets the public 'F' service.
     *
     * @return \F
     */
    protected function getFService()
    {
        return new \F(new \E(new \D(new \C(new \B(new \A())))));
    }

    /**
     * Gets the public 'G' service.
     *
     * @return \G
     */
    protected function getGService()
    {
        return new \G(new \F(new \E(new \D(new \C(new \B(new \A()))))));
    }

    /**
     * Gets the public 'H' service.
     *
     * @return \H
     */
    protected function getHService()
    {
        return new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A())))))));
    }

    /**
     * Gets the public 'I' service.
     *
     * @return \I
     */
    protected function getIService()
    {
        return new \I(new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A()))))))));
    }

    /**
     * Gets the public 'J' service.
     *
     * @return \J
     */
    protected function getJService()
    {
        return new \J(new \I(new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A())))))))));
    }
}
