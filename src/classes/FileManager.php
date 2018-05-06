<?php

class FileManager {
	private $fileName;
	private $fileData;
	private $hasHeader;
	private $delimiters;

	public function __construct($fileName, $hasHeader = false, $delimiters = ';') {
		$this->fileName = $fileName;
		$this->hasHeader = $hasHeader;
		$this->loadData();
		$this->delimiters = $delimiters;
	}

	public function loadData() {
		if (file_exists($this->fileName)) {
			$this->fileData = file_get_contents($this->fileName);
			return true;
		}
		die("\n\nArquivo nao encontrado.\n\n");
		return false;
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

			// Adiciona ao array naturalmente indexado
			$array[] = explode($this->delimiters, $line);
		}

		return $array;
	}

	public function __toString() {
		return $fileData;
	}

}