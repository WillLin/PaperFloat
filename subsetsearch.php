<?php

	require_once 'paper.php';
	require_once 'paper_word.php';

	function subsetSearch() {
		$paperIDArray = $_GET['paper'];

		$allPaperArray = $_SESSION['AllPaperArray'];
		$papersWithKeyword = $_SESSION['papersWithKeyword'];
		$subsetPapers = array();

		foreach ($paperIDArray as $key => $value) {
			$currentTitle = $papersWithKeyword[$value]->getTitle();
			foreach ($allPaperArray as $key => $paper) {
				if (strcmp($paper->getTitle(), $currentTitle) == 0) {
					$subsetPapers[] = $paper;
				}
			}
		}

		$_SESSION['subsetPapersArray'] = $subsetPapers;
	}

?>