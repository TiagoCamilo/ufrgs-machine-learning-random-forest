<?php

class Classifier {
	private $modelo;
	private $instancia;

	public function __construct($modelo, $instancia) {
		$this->modelo = $modelo;
		$this->instancia = $instancia;
	}

	public function execute() {
		foreach ($this->modelo->getNodes() as $node) {
			$result = $this->analizy($node);
			if ($result !== false) {
				return $result;
			}
		}

		return $this->modelo->getLabelMostCommon() . "x";
		//die("\n\nERROR: Valor NULL na predicao instancia [ " . implode(" ; ", $this->instancia) . " ]");
	}

	private function analizy($node) {

		switch ($node->operator) {
		case "==":
			$result = $this->instancia[$node->attr] == $node->value;
			break;
		case ">":
			$result = $this->instancia[$node->attr] > $node->value;
			break;
		case "<=":
			$result = $this->instancia[$node->attr] <= $node->value;
			break;
		default:
			$result = false;
		}

		if ($result === false) {
			return false;

		}

		if (is_array($node->nodes)) {
			foreach ($node->nodes as $childNode) {
				$result = $this->analizy($childNode);
				if ($result !== false) {
					return $result;
				}
			}
		} else {
			$result = $node->nodes; //Rotulo encontrado
		}

		return $result;
	}

	public function debugNode($node, $nivel = 0) {
		for ($i = 0; $i <= $nivel; $i++) {
			echo "\t";
		}

		echo $node->attr . " " . $node->operator . " " . $node->value;
		$nivel++;

		if (is_array($node->nodes)) {
			foreach ($node->nodes as $node) {
				echo "\n";
				$this->debugNode($node, $nivel);
			}
		} else {
			echo "\t\t => ";
			echo $node->nodes;
		}

	}

}