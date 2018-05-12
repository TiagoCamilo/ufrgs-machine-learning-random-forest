<?php

class FileManager {
	private $fileName;
	private $fileData;
	private $hasHeader;
	private $delimiters;
	private $labelColumn;

	public function __construct($fileName, $hasHeader = false, $delimiters = ';', $labelColumn = null) {
		$this->fileName = $fileName;
		$this->hasHeader = $hasHeader;
		$this->delimiters = $delimiters;
		$this->labelColumn = $labelColumn;
		$this->loadData();
	}

	public function loadData() {
		if (file_exists($this->fileName)) {
			$this->fileData = file_get_contents($this->fileName);
			return true;
		}
		die("\n\nArquivo nao encontrado.\n\n");
	}

	public function getDataAsArray() {
		$fileAsArray = explode("\n", $this->fileData);

		$skipedHeader = false;
		$array = array();
		foreach ($fileAsArray as $line) {

			// Remove cursor return para nao quebrar string
			$line = str_replace("\r", "", $line);

			//Se linha é invalida (exemplo ultima linha dos arquivos)
			if (strlen($line) == 0) {
				continue;
			}

			// Se deve existir cabecalho e ainda nao foi ignorado
			if ($this->hasHeader == true && $skipedHeader == false) {
				// Assume que primeira linha é o header e pula a mesma
				$skipedHeader = true;
				continue;
			}

			// Cria vetor atributos da linha
			$lineAttrs = explode($this->delimiters, $line);

			// Se a coluna label foi explicitamente definida
			// Garante que o valor esteja na ultima coluna atraves de swap
			if ($this->labelColumn !== null) {
				$aux = $lineAttrs[count($lineAttrs) - 1];
				$lineAttrs[count($lineAttrs) - 1] = $lineAttrs[$this->labelColumn];
				$lineAttrs[$this->labelColumn] = $aux;
			}

			// Adiciona ao array naturalmente indexado
			$array[] = $lineAttrs;
		}

		return $array;
	}

	public function __toString() {
		return $fileData;
	}

}