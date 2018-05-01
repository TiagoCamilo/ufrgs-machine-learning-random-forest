<?php

class ArvoreDecisao {
	private $dados;
	private $indexLabel;
	private $listIndexAttr = array();
	private $subArvore;

	public function __construct($dados, $indexLabel) {
		$this->dados = $dados;
		$this->indexLabel = $indexLabel;
		$this->processAttr();
	}

	private function processAttr() {
		foreach ($this->dados as $line) {
			foreach ($line as $attr => $value) {
				if ($attr == $this->indexLabel) {
					continue;
				}

				$this->listIndexAttr[$attr] = $attr;
			}
		}
	}

	public function build($level = 0) {
		if (count($this->listIndexAttr) == 0) {
			echo "\t => \t" . $this->dados[0][$this->indexLabel];
			return $this->dados[0][$this->indexLabel];
		}

		$array = array();
		$attr = array_shift($this->listIndexAttr);

		foreach ($this->dados as $line) {
			$newLine = $line;
			unset($newLine[$attr]);

			$array[$line[$attr]][] = $newLine;
		}

		$level++;

		foreach ($array as $attr => $value) {
			//Debug
			echo "<br>";
			for ($i = 0; $i < $level; $i++) {
				echo "\t";
			}
			echo $attr;
			// Fim Debug

			$newArvore = new ArvoreDecisao($value, $this->indexLabel);
			$this->subArvore[] = $newArvore->build($level);
		}

		// Liberando espaco, visto que nao sera mais utilizada
		unset($this->dados);
		return $this;
	}

}