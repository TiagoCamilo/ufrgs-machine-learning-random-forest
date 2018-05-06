<?php

class Node {
	public $attr;
	public $value;
	public $operator;
	public $nodes;

	public function __construct($attr, $value, $operator) {
		$this->attr = $attr;
		$this->value = $value;
		$this->operator = $operator;
	}

	public static function createNode($attr, $value, $operator = "==") {
		return new Node($attr, $value, $operator);
	}

}