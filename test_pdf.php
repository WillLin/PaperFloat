<?php
require('fpdf17/fpdf.php');
class PDF extends FPDF
{
	function SetCol($col)
	{
	    // Set position at a given column
	    
	    $x = 10+$col*65;
	    //$this->SetLeftMargin($x);
	    $this->SetX($x);
	}

	function SetRow($row)
	{
		$y = 14+$row;
		$this->SetY($y);

	}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 14);
$pdf->setCol(1);
$pdf->Cell(65, 7, "Pavel");
//$pdf->Ln();
$pdf->SetRow(1);
$pdf->setCol(1);
$pdf->Cell(65, 7, "Jerry");
$pdf->Output();
?>