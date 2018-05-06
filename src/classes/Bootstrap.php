<?php

class Bootstrap {
	private $trainingData;
	private $testData;

	public function __construct($data) {
		foreach ($data as $line) {
			$dataHash[] = implode(";", $line);
		}
		$this->processTraining($dataHash);
		$this->processTest($dataHash);
	}

	public function getTrainingData() {
		$multiDimTraining = [];
		foreach ($this->trainingData as $line) {
			$multiDimTraining[] = explode(";", $line);
		}
		return $multiDimTraining;
	}

	public function getTestData() {
		$multiDimTest = [];
		foreach ($this->testData as $line) {
			$multiDimTest[] = explode(";", $line);
		}
		return $multiDimTest;
	}

	private function processTraining($data) {
		$size = count($data);
		for ($i = 0; $i < $size; $i++) {
			$workData = $data;
			shuffle($workData);
			$this->trainingData[] = $workData[0];
		}
	}

	private function processTest($data) {
		$this->testData = array_diff($data, $this->trainingData);
	}

	public function debug() {
		echo "\nTraining Data Set:\n";
		foreach ($this->getTrainingData() as $example) {
			$example = implode(";", $example);
			echo $example;
			@$trainigHashs[$example]++;
		}
		var_dump($trainigHashs);
		echo "\nTest Data Set:\n";
		foreach ($this->getTestData() as $example) {
			$example = implode(";", $example);
			echo $example;
			@$testHashs[$example]++;
		}
		var_dump($testHashs);
	}
}