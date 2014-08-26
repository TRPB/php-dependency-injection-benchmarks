<?php

namespace Njasm\Container\Definition\Finder;

use Njasm\Container\Definition\Service\Request;

abstract class AbstractFinder
{
    protected $successor;
    protected $found;
    
    final public function append(AbstractFinder $successor)
    {
        if (isset($this->successor)) {
            $this->successor->append($successor);
        }
        
        $this->successor = $successor;
    }
    
    final public function has(Request $request)
    {
        $this->found = $this->handle($request);
        
        if (!$this->found) {
            if (!is_null($this->successor)) {
                $this->found = $this->successor->has($request);
            }
        }

        return $this->found;
    }
    
    abstract protected function handle(Request $request);
}
