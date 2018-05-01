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
		return false;
	}

	public function getDataAsArray() {
		$fileAsArray = explode("\n", $this->fileData);

		$i = 0;
		$headers = array();
		foreach ($fileAsArray as $line) {
			//Se linha é invalida (exemplo ultima linha dos arquivos)
			if (strlen($line) == 0) {
				continue;
			}

			// Se deve existir cabecalho e ainda nao esta definido
			if ($this->hasHeader == true && count($headers) == 0) {
				// Assume que primeira linha é o header
				$headers = explode($this->delimiters, $line);
				continue;
			}

			// Se nao tem header, basta retornar array naturalmente indexado
			//if ($this->hasHeader == false) {
			$array[$i++] = explode($this->delimiters, $line);
			continue;
			//}

			// Se tem header e ele ja esta definido, retorna array associativo
			$lineAsArray = explode($this->delimiters, $line);
			foreach ($headers as $attr) {
				// Adiciona todos atributos na mesma linha
				$array[$i][$attr] = array_shift($lineAsArray);
			}

			// Somente apos todos attr processado, incrementa linha
			$i++;

		}

		return $array;
	}

	public function __toString() {
		return $fileData;
	}

}