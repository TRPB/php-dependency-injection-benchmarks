<?php 

class A {
	
}

class B {
	public $a;
	
	public function __construct(A $a) {
		$this->a = $a;
	}
}

class C {
	public $b;
	
	public function __construct(B $b) {
		$this->b = $b;
	}
}


class D {
	public $c;
	
	public function __construct(C $c) {
		$this->c = $c;
	}
}


class E {
	public $d;
	
	public function __construct(D $d) {
		$this->d = $d;
	}
}


class F {
	public $e;
	
	public function __construct(E $e) {
		$this->e = $e;
	}
}

class G {
	public $f;

	public function __construct(F $f) {
		$this->f = $f;
	}
}


class H {
	public $g;

	public function __construct(G $g) {
		$this->g = $g;
	}
}


class I {
	public $h;

	public function __construct(H $h) {
		$this->h = $h;
	}
}


class J {
	public $i;

	public function __construct(I $i) {
		$this->i = $i;
	}
}

class K {
	public $j;
	
	public function __construct(K $k) {
		$this->k = $k;
	}
}