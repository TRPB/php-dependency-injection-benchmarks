<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * ProjectServiceContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class ProjectServiceContainer extends Container
{
    private $parameters;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array();
        $this->scopeChildren = array();
        $this->methodMap = array(
            'a' => 'getAService',
            'b' => 'getBService',
            'c' => 'getCService',
            'd' => 'getDService',
            'e' => 'getEService',
            'f' => 'getFService',
            'g' => 'getGService',
            'h' => 'getHService',
            'i' => 'getIService',
            'j' => 'getJService',
        );

        $this->aliases = array();
    }

    /**
     * Gets the 'a' service.
     *
     * @return A A A instance.
     */
    protected function getAService()
    {
        return new \A();
    }

    /**
     * Gets the 'b' service.
     *
     * @return B A B instance.
     */
    protected function getBService()
    {
        return new \B(new \A());
    }

    /**
     * Gets the 'c' service.
     *
     * @return C A C instance.
     */
    protected function getCService()
    {
        return new \C(new \B(new \A()));
    }

    /**
     * Gets the 'd' service.
     *
     * @return D A D instance.
     */
    protected function getDService()
    {
        return new \D(new \C(new \B(new \A())));
    }

    /**
     * Gets the 'e' service.
     *
     * @return E A E instance.
     */
    protected function getEService()
    {
        return new \E(new \D(new \C(new \B(new \A()))));
    }

    /**
     * Gets the 'f' service.
     *
     * @return F A F instance.
     */
    protected function getFService()
    {
        return new \F(new \E(new \D(new \C(new \B(new \A())))));
    }

    /**
     * Gets the 'g' service.
     *
     * @return G A G instance.
     */
    protected function getGService()
    {
        return new \G(new \F(new \E(new \D(new \C(new \B(new \A()))))));
    }

    /**
     * Gets the 'h' service.
     *
     * @return H A H instance.
     */
    protected function getHService()
    {
        return new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A())))))));
    }

    /**
     * Gets the 'i' service.
     *
     * @return I A I instance.
     */
    protected function getIService()
    {
        return new \I(new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A()))))))));
    }

    /**
     * Gets the 'j' service.
     *
     * @return J A J instance.
     */
    protected function getJService()
    {
        return new \J(new \I(new \H(new \G(new \F(new \E(new \D(new \C(new \B(new \A())))))))));
    }
}
