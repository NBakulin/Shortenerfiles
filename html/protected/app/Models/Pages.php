<?php

class Pages {
	
	public function index() {
		return "Hello!";
	}

	public function hello($name) {
		return "{$name}";
	}

}
