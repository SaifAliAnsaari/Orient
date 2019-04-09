<?php
require('fpdf.php');
class PDF extends FPDF
{

    function header(){
        $this->Image('logo.png', 10, 20, 60);
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-33);
        // Arial italic 8
        $this->SetFont('Arial','B',12);
        // Text color in gray
        $this->SetTextColor(128);
        // Page number
        // $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        $this->Cell(0, 10, "H-Shippers",0,1,"C");
        $this->SetFont('Arial','B',10);
        $this->Cell(0, 5, "CL-1/1 SaifeeHouse DrZia UdDin Ahmed Road, Opposite ShaheenComplex, Karachi ",0,1,"C");
        $this->Cell(0, 5, "| 021-32212217| 0300-2070848 | ",0,1,"C");
        $this->Cell(0, 5, "| NTN# 8924782-4 | GST# 1200980575537 |",0,1,"C");
        $this->Cell(0, 5, "w  w  w  .  h  s  h  i  p  p  e  r  s  .  c  o  m",0,0,"C", false, 'http://hshippers.com');
    }

}

$pdf = new PDF();
$pdf->AddPage("P", "A4");

/* Starting Billed To Section */
$pdf->SetY(40);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0, 20, 'Billed To:', 0, 1);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,'Indus Motors Corporation',0,1);
$pdf->SetY(70);
$pdf->SetFont('Arial','',11);
$address = "Plot No N,W-Z/1,P-1 North Western Industrial Area Zone";
$pdf->Cell(0,0,$address,0,1);
$pdf->SetY(80);
$pdf->Cell(0,0,'NTN# 0676546-7',0,1);
$pdf->SetY(85);
$pdf->Cell(0,0,'STRN# 02-04-8703001-55',0,1);
/* Ending Billed To Section */

/* Starting Invoice Section */
$pdf->SetXY(125, 25);
$pdf->setFillColor(53,72,122); 
$pdf->SetTextColor(255,255,255); 
$pdf->SetFont('Arial','',15);
$pdf->Cell(200,13,'   e-Invoice',0,1,'L',1);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(125,45);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Account #',0,0);
$pdf->SetXY(160, 45);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,'K12842',0,0);

$pdf->SetXY(125, 53);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Invoice #',0,0);
$pdf->SetXY(160,53);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,'0001',0,0);

$pdf->SetXY(125,61);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Invoice Date',0,0);
$pdf->SetXY(160, 61);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,'31/11/2018',0,0);

$pdf->SetXY(125, 69);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Time Period',0,0);
$pdf->SetXY(160, 69);
$pdf->SetFont('Arial','',12);
setlocale(LC_CTYPE, 'en_US');
$pdf->Cell(0,0,iconv('UTF-8', 'ASCII//TRANSLIT', "01/11/18 - 30/11/18"),0,0);
// /* Ending Invoice Section */

// /* Starting Invoice Table Section */
$pdf->SetY(100);
$pdf->setFillColor(53,72,122); 
$pdf->SetTextColor(255,255,255); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(12,10,' SN ',1,0,'L',1);
$pdf->Cell(60,10,' SERVICE ',1,0,'L',1);
$pdf->Cell(30,10,' QUANTITY ',1,0,'L',1);
$pdf->Cell(25,10,' WEIGHT ',1,0,'L',1);
$pdf->Cell(25,10,' RATE ',1,0,'L',1);
$pdf->Cell(38,10,' TOTAL ',1,0,'L',1);

$yPos = 110;
$totalRecs = 1;
$contOnEachPage = 12;
$totalPages = ceil($totalRecs/$contOnEachPage);
$contentCounter = 0;
$resumeFromCounter = 0;
for ($i=0; $i < $totalPages; $i++) { 
    if($i > 0){
        if($contentCounter <= $totalRecs){
            $pdf->AddPage('P', 'A4');
            $yPos = 40;
            $contOnEachPage = 22;
        }
    }
    for ($j=0; $j < $contOnEachPage; $j++) { 
        if($contentCounter <= $totalRecs){
            $pdf->SetY($yPos);
            $pdf->setFillColor(255,255,255); 
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetFont('Arial','',10);
            if(($i == 0 && ($contentCounter == ($contOnEachPage-1))) || ($contentCounter == $totalRecs)){
                $pdf->Cell(12,10,$contentCounter,'L,R,B',0,'L',1);
                $pdf->Cell(60,10,'Over Night Delivery ','L,R,B',0,'L',1);
                $pdf->Cell(30,10,' QUANTITY ','L,R,B',0,'L',1);
                $pdf->Cell(25,10,' WEIGHT ','L,R,B',0,'L',1);
                $pdf->Cell(25,10,' RATE ','L,R,B',0,'L',1);
                $pdf->Cell(38,10,' TOTAL ','L,R,B',1,'L',1);
            }else{
                $pdf->Cell(12,10,$contentCounter,'L,R',0,'L',1);
                $pdf->Cell(60,10,'Over Night Delivery ','L,R',0,'L',1);
                $pdf->Cell(30,10,' QUANTITY ','L,R',0,'L',1);
                $pdf->Cell(25,10,' WEIGHT ','L,R',0,'L',1);
                $pdf->Cell(25,10,' RATE ','L,R',0,'L',1);
                $pdf->Cell(38,10,' TOTAL ','L,R',1,'L',1);
            }
            $yPos += 10;
            $contentCounter++;
            $resumeFromCounter++;
        }
    }
}

/* Starting Total Section */
if($yPos > 230){
    $pdf->AddPage("P", "A4");
    $yPos = 50;
}
$pdf->SetY($yPos);
$pdf->setFillColor(255,255,255); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(12,10,'',0,0,'L',1);
$pdf->Cell(60,10,'',0,0,'L',1);
$pdf->Cell(30,10,'  ',0,0,'L',1);
$pdf->Cell(50,10,' Fuel Charges ',1,0,'L',1);
$pdf->Cell(38,10,' RS.14497 ',1,1,'L',1);
$yPos += 10;

$pdf->SetY($yPos);
$pdf->setFillColor(255,255,255); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(12,10,'',0,0,'L',1);
$pdf->Cell(60,10,'',0,0,'L',1);
$pdf->Cell(30,10,'  ',0,0,'L',1);
$pdf->Cell(50,10,' GST (13%) ',1,0,'L',1);
$pdf->Cell(38,10,' Rs.20731.42 ',1,1,'L',1);
$yPos += 10;

$pdf->SetY($yPos);
$pdf->SetFont('Arial','',10);
$pdf->Cell(12,10,'',0,0,'L',1);
$pdf->Cell(60,10,'',0,0,'L',1);
$pdf->Cell(30,10,'  ',0,0,'L',1);
$pdf->SetFont('Arial','B',10);
$pdf->setFillColor(53,72,122); 
$pdf->SetTextColor(255,255,255);
$pdf->Cell(50,10,' GRAND TOTAL ',1,0,'L',1);
$pdf->Cell(38,10,' RS. 180,203.93 ',1,1,'L',1);
$yPos += 10;

/* Ending Total Section */
$height = $pdf->h;
$pdf->SetY($yPos+10);
$image1 = "hships.PNG";
$pdf->Cell( 40, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 120), 0, 0, 'L', false );

$pdf->Output();
?>