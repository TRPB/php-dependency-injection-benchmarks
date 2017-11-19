<?php
class Container_dcca481015 extends Nette\DI\Container
{
	protected $meta = [
		'types' => [
			'A' => [1 => ['a']],
			'B' => [1 => ['b']],
			'C' => [1 => ['c']],
			'D' => [1 => ['d']],
			'E' => [1 => ['e']],
			'F' => [1 => ['f']],
			'G' => [1 => ['g']],
			'H' => [1 => ['h']],
			'I' => [1 => ['i']],
			'J' => [1 => ['j']],
			'Nette\DI\Container' => [1 => ['container']],
		],
		'services' => [
			'a' => 'A',
			'b' => 'B',
			'c' => 'C',
			'container' => 'Nette\DI\Container',
			'd' => 'D',
			'e' => 'E',
			'f' => 'F',
			'g' => 'G',
			'h' => 'H',
			'i' => 'I',
			'j' => 'J',
		],
		'aliases' => [],
	];


	public function __construct(array $params = [])
	{
		$this->parameters = $params;
		$this->parameters += [];
	}


	public function createServiceA(): A
	{
		$service = new A;
		return $service;
	}


	public function createServiceB(): B
	{
		$service = new B($this->createServiceA());
		return $service;
	}


	public function createServiceC(): C
	{
		$service = new C($this->createServiceB());
		return $service;
	}


	public function createServiceContainer(): Nette\DI\Container
	{
		return $this;
	}


	public function createServiceD(): D
	{
		$service = new D($this->createServiceC());
		return $service;
	}


	public function createServiceE(): E
	{
		$service = new E($this->createServiceD());
		return $service;
	}


	public function createServiceF(): F
	{
		$service = new F($this->createServiceE());
		return $service;
	}


	public function createServiceG(): G
	{
		$service = new G($this->createServiceF());
		return $service;
	}


	public function createServiceH(): H
	{
		$service = new H($this->createServiceG());
		return $service;
	}


	public function createServiceI(): I
	{
		$service = new I($this->createServiceH());
		return $service;
	}


	public function createServiceJ(): J
	{
		$service = new J($this->createServiceI());
		return $service;
	}


	public function initialize()
	{
	}
}
