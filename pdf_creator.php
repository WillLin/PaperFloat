<?php

require('fpdf17/fpdf.php');

require_once 'paper.php';
require_once 'paper_word.php';

session_start();
class PDF extends FPDF
{
// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    //var_dump($data);
    return $data;
}

function SetRow($row, $key)
	{	
		if ($key)
		{	
			$y = 24*$row - 8;
		}

		else
		{
			$y = 20*$row + 8;
		}
		
		$this->SetY($y);

	}

// Simple table
function BasicTable($header, $data)
{
    // Header
    for ($i = 0; $i < count($header); $i++)
    {
        $this->Cell(65,7,$header[$i],1);
    }
    
    // Data
    $count = 0;
 	
    $col = 0;
    for ($i = 0; $i < count($data); $i++)
    {	
    	if ($count % 3 == 0)
    	{
    		$this->Ln();
    		$col = 0;
    			
    	}	


    	if (strlen($data[$i]) <= 46)
    	{
    		$this->SetCol($col);
    		$this->Cell(65,24, $data[$i],1, 'LR');
    		$this->SetCol($col + 1);
    		
    	}
	
    	else
    	{	

    		$num_lines = floor(strlen($data[$i]) / 46);
    		$current = substr($data[$i], 0, 46);
    		$this->SetCol($col);
    		$this->Cell(65,24, $current,1, 'LR');
    		
    		for ($j = 1; $j <= $num_lines; $j++)
    		{
    			if ($j > 3)
    			{
    				break;
    			}
    			$next = substr($data[$i], 46 * $j, 46);
    			$row = $this->GetY();
    			if ($j == 1)
    				$this->SetY($row + 8);
    			if ($j == 2)
    				$this->SetY($row + 11);
    			if ($j == 3)
    			{
    				$this->SetY($row + 14);
    			}
    			$this->SetCol($col);
    			$this->Cell(65, 14, $next, 0, 0);
    			$this->SetCol($col + 1);
    			$this->SetY($row);


    		}
    		
    	

    }
    $col++;
    $count++;

	}
}

function SetCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 10+$col*65;
    //$this->SetLeftMargin($x);
    $this->SetX($x);
}

}

$paperWithKeyword = $_SESSION['papersWithKeyword'];
$pdfArray = array();
for ($i = 0; $i < count($paperWithKeyword); $i++)
{
	$pdfArray[] = $paperWithKeyword[$i]->getTitle();
	$authors = "";
	foreach ($paperWithKeyword[$i]->getAuthors() as $key => $value)
	{
		$authors .= $value . " ";
	}
	$pdfArray[] = $authors;
	$pdfArray[] = $paperWithKeyword[$i]->getConference();
}

$pdf = new PDF();

$header = array('Title', 'Author', 'Conference');
$pdf->SetFont('Arial', '', 8);
$pdf->AddPage();
$pdf->BasicTable($header, $pdfArray);
$pdf->Output();



?>