<?php
require('fpdf.php');
// echo '<pre>'; print_r($_GET['core']); 
// die;
class PDF extends FPDF
{

    function header(){
        $this->Image('orient.png', 90, 15, 30);
    }

    function Footer()
    {
        // // Position at 1.5 cm from bottom
        // $this->SetY(-33);
        // // Arial italic 8
        // $this->SetFont('Arial','B',12);
        // // Text color in gray
        // $this->SetTextColor(128);
        // // Page number
        // // $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        // $this->Cell(0, 10, "H-Shippers",0,1,"C");
        // $this->SetFont('Arial','B',10);
        // $this->Cell(0, 5, "CL-1/1 SaifeeHouse DrZia UdDin Ahmed Road, Opposite ShaheenComplex, Karachi ",0,1,"C");
        // $this->Cell(0, 5, "| 021-32212217| 0300-2070848 | ",0,1,"C");
        // $this->Cell(0, 5, "| NTN# 8924782-4 | GST# 1200980575537 |",0,1,"C");
        // $this->Cell(0, 5, "w  w  w  .  h  s  h  i  p  p  e  r  s  .  c  o  m",0,0,"C", false, 'http://hshippers.com');
    }

}

$pdf = new PDF();
$pdf->AddPage("P", "A4");

/* Starting Billed To Section */
$pdf->SetXY(10,40);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(100, 40, 'Date of Report:', 0, 1);


$pdf->SetXY(46,60);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,($_GET['core']['report_created_at'] != null ? $_GET['core']['report_created_at'] : "--"),0,0);



$pdf->SetXY(120,60);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Report Prepared By:',0,0);

$pdf->SetXY(164,60);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,($_GET['core']['created_by'] != null ? $_GET['core']['created_by'] : "--"),0,0);



$pdf->SetY(65);
$pdf->setFillColor(237,238,239); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetDrawColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,10,' Date of Visit:  ',1,0,'L',1);
$pdf->Cell(95,10,' Customer Visited:  ',1,0,'L',1);

$pdf->SetY(75);
$pdf->setFillColor(237,238,239); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,10,' Location:  ',1,0,'L',1);
$pdf->Cell(95,10,' Time Spent:  ',1,0,'L',1);


$pdf->SetXY(35,70);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($_GET['core']['date_of_visit'] != null ? $_GET['core']['date_of_visit'] : "--"),0,0);
$pdf->SetXY(140,70);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($_GET['core']['customer_name'] != null ? $_GET['core']['customer_name'] : "--"),0,0);

$pdf->SetXY(30,80);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($_GET['core']['location'] != null ? $_GET['core']['location'] : "--"),0,0);
$pdf->SetXY(130,80);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($_GET['core']['time_spent'] != null ? $_GET['core']['time_spent'] : "--"),0,0);




$pdf->SetY(95);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'POC Name  ',0,0);

$pdf->SetY(99);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

$height_poc = 100;
$poc_counter = 0;
if($_GET['pocs']){
    foreach($_GET['pocs'] as $poc){
        $pdf->SetY($height_poc);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(95,10,' POC Name  ',0,0);
        $pdf->Cell(95,10,$poc['poc_name'],0,0);
        if($poc_counter < sizeof($_GET["pocs"])-1){
            $height_poc += 8;
        }
        
        $poc_counter++;
    }
}



$height_purpose = $height_poc + 15;

$purposes = explode (",", $_GET['core']['purpose_of_visit']);

$pdf->SetY($height_purpose);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Purpose of Visit  ',0,0);

$pdf->SetY($height_purpose += 4);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

$pdf->SetY($height_purpose += 1);
$pdf->SetFont('Arial','',10);
$counter = 0;
$height_purpose += 5;
if($purposes){
    for($i = 0; $i < sizeof($purposes); $i++){
        if($counter == 4){
            $pdf->Cell(42,10,$purposes[$i],0,1);
            $height_purpose += 20;
            $counter = 0;
        }else{
            $pdf->Cell(42,10,$purposes[$i],0,0);
            $counter++;
        }
    }
}


if(sizeof($purposes) > 5){
    $height_products = $height_purpose ;
}else{
    $height_products = $height_purpose += 10;
}

//$height_products = $height_purpose ;


$pdf->SetY($height_products);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Products  ',0,0);

$pdf->SetY($height_products + 5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

// $pdf->SetY($height_products + 6);
// $pdf->SetFont('Arial','',10);
// $pdf->Cell(95,10,' Sub Cat 1  ',0,0);
// $pdf->Cell(95,10,' Sub Cat 2  ',0,0);

$pdf->SetY($height_products += 5);
$pdf->SetFont('Arial','',10);
$counter_pro = 0;
$height_products += 5;
$test = $_GET['products'];
if($_GET['products']){
    for($i = 0; $i < sizeof($test); $i++){
        if($counter_pro == 2){
            $pdf->Cell(70,10,$test[$i]['cat_name'],0,1);
            $height_products += 20;
            $counter_pro = 0;
        }else{
            $pdf->Cell(70,10,$test[$i]['cat_name'],0,0);
            $counter_pro++;
        }
    }
}




if(sizeof($test) > 3){
    $height_opportunity = $height_products;
}else{
    $height_opportunity = $height_products += 15;
}



$pdf->SetY($height_opportunity);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Opportunity  ',0,0);
$pdf->SetXY(70,$height_opportunity);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Annual Business Value  ',0,0);
$pdf->SetXY(140,$height_opportunity);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Relationship With Customer ',0,0);

$pdf->SetY($height_opportunity + 5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

$pdf->SetY($height_opportunity + 6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(70,10,($_GET['core']['opportunity'] != null ? $_GET['core']['opportunity'] : "--"),0,0);
$pdf->Cell(70,10,($_GET['core']['bussiness_value'] != null ? $_GET['core']['bussiness_value'] : "--"),0,0);
$pdf->Cell(70,10,($_GET['core']['relationship'] != null ? $_GET['core']['relationship'] : "--"),0,0);



$height = $height_opportunity += 20;

$pdf->SetY($height);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Competition  ',0,0);

$pdf->SetY($height += 5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

$height = $height += 1;
$counter = 0;
if($_GET['competitions']){
    foreach($_GET['competitions'] as $competition){
        $pdf->SetY($height);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(95,10,' Competition Name: ',0,0);
        $pdf->Cell(95,10," Competitor's Strength: ",0,0);
        
        $pdf->SetXY(45,$height);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(90,10,$competition['name'],0,0);
        
        $pdf->SetXY(146,$height);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(90,10,$competition['strength'],0,0);
       
        if($counter < sizeof($_GET["competitions"])-1){
            $height += 8;
        }
        
        $counter++;
        
    }
}


$height_summary = $height += 15;

$pdf->SetY($height_summary);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Visit Summary  ',0,0);

$pdf->SetY($height_summary+=5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

$pdf->SetY($height_summary+=1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,10,($_GET['core']['description'] != null ? $_GET['core']['description'] : "--"),0,0);





// $totalFcharges = 0;
// $yPos = 110;
// $grandTotalPrice = 0;
// $sNo = 1;

// $pdf->SetY($yPos);
// $pdf->setFillColor(255,255,255); 
// $pdf->SetTextColor(0,0,0); 
// $pdf->SetFont('Arial','',10);
// $pdf->Cell(12,10,$sNo++,1,0,'L',1);
// $pdf->Cell(60,10,'Over Night Delivery ',1,0,'L',1);
// $pdf->Cell(35,10,'100',1,0,'L',1);
// $pdf->Cell(35,10,'252222',1,0,'L',1);
// $pdf->Cell(48,10,'Rs.',1,1,'L',1);
// $yPos += 10;






/* Ending Total Section */
// $height = $pdf->h;
// $pdf->SetY($yPos+30);
// $image1 = "hships.PNG";
// $pdf->Cell( 40, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 120), 0, 0, 'L', false );

$pdf->Output();
?>
