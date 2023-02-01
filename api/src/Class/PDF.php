<?php
include_once('./library/pdf_generator/fpdf.php');

class PDF extends FPDF {
    // Funcion encargado de realizar el encabezado
    function Header($title='',$color=0){
        // Logo

        // $this->Image('logo.jpg',10,-1,70);
        $this->SetFont('Arial','B',13);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(95,10,$title,1,0,'C',$color);
        // Line break
        $this->Ln(20);
    }
    // Funcion pie de pagina
    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

?>