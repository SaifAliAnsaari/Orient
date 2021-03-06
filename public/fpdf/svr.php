<?php
require('fpdf.php');
// echo '<pre>'; print_r($_GET['id']); 
// die;
class PDF extends FPDF
{

    function header(){
        $this->Image('orient.png', 90, 5, 16);
    }

    function Footer()
    {
        //
    }

}


$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, 'orient');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT `id`, `report_created_at`, `report_created_by`, `date_of_visit`, `customer_visited`, `location`, `time_spent`, `purpose_of_visit`, `relationship`, `description`, (Select `name` from users where id = cc.report_created_by) as created_by, (Select `company_name` from customers where id = cc.customer_visited) as customer_name from svr_core as cc where id = ".$_GET['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($id, $report_created_at, $report_created_by, $date_of_visit, $customer_visited, $location, $time_spent, $purpose_of_visit, $relationship, $description, $created_by, $customer_name);
$stmt->fetch();
$core = array('report_created_at' => $report_created_at, 'report_created_by' => $report_created_by, 'date_of_visit' => $date_of_visit, 'customer_visited' => $customer_visited, 'location' => $location, 'time_spent' => $time_spent, 'purpose_of_visit' => $purpose_of_visit, 'relationship' => $relationship, 'description' => $description, 'created_by' => $created_by, 'customer_name' => $customer_name);
$stmt->close();


$sql = "SELECT `id`, `category_id`, (Select `name` from sub_categories where id = cp.category_id) as cat_name from svr_products as cp where svr_id = ".$_GET['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($pro_id, $pro_cat_id, $pro_cat_name);
$pro_count = 0;
$products = [];
while($stmt->fetch()){
    $products[$pro_count] = array('pro_id' => $pro_id, 'pro_category_id' => $pro_cat_id, 'pro_cat_name' => $pro_cat_name);
    $pro_count ++;
}
$stmt->close();




$sql = "SELECT `id`, `poc_id`, (Select `poc_name` from poc where id = c_p.poc_id) as poc_name from svr_poc as c_p where svr_id = ".$_GET['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($poc_id, $poc_cvr_poc, $poc_name);
$counter = 0;
$poc = [];
while($stmt->fetch()){
    $poc[$counter] = array('poc_id' => $poc_id, 'poc_poc_id' => $poc_cvr_poc, 'poc_name' => $poc_name);
    $counter ++;
}
$stmt->close();




$sql = "SELECT `name`, `strength` from cvr_competition where svr_id = ".$_GET['id'];
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($com_name, $com_strength);
$competition = [];
while($stmt->fetch()){
    $competition[] = array('com_name' => $com_name, 'com_strength' => $com_strength);
}
$stmt->close();




//  echo "<pre>"; print_r($poc); die;
//  die;

$pdf = new PDF();
$pdf->AddPage("P", "A4");

$pdf->SetAutoPageBreak(false);

/* Starting Billed To Section */
$pdf->SetXY(10,12);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(100, 40, 'Date of Report:', 0, 1);


$pdf->SetXY(46,32);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,($core['report_created_at'] != null ? $core['report_created_at'] : "--"),0,0);

$pdf->SetXY(120,32);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Report Prepared By:',0,0);

$pdf->SetXY(164,32);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,($core['created_by'] != null ? $core['created_by'] : "--"),0,0);



$pdf->SetY(37);
$pdf->setFillColor(237,238,239); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetDrawColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,10,' Date of Visit:  ',1,0,'L',1);
$pdf->Cell(95,10,' Customer Visited:  ',1,0,'L',1);

$pdf->SetY(47);
$pdf->setFillColor(237,238,239); 
$pdf->SetTextColor(0,0,0); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,10,' Location:  ',1,0,'L',1);
$pdf->Cell(95,10,' Time Spent:  ',1,0,'L',1);


$pdf->SetXY(35,42);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($core['date_of_visit'] != null ? $core['date_of_visit'] : "--"),0,0);
$pdf->SetXY(140,42);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($core['customer_name'] != null ? $core['customer_name'] : "--"),0,0);

$pdf->SetXY(30,52);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($core['location'] != null ? $core['location'] : "--"),0,0);
$pdf->SetXY(130,52);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,($core['time_spent'] != null ? $core['time_spent'] : "--"),0,0);




$pdf->SetY(65);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'POC Name  ',0,0);

$pdf->SetY(69);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);



$poc_counter = 0;
$height_poc = 70;
$pdf->SetY($height_poc);
$pdf->SetFont('Arial','',10);

if($poc){
    for($i = 0; $i < sizeof($poc); $i++){
        if($poc_counter == 4){
            $pdf->Cell(42,10,$poc[$i]['poc_name'],0,1);
            $height_poc += 15;
            $poc_counter = 0;
        }else{
            $pdf->Cell(42,10,$poc[$i]['poc_name'],0,0);
            $poc_counter++;
        }
    }
 }




$height_purpose = $height_poc += 15;

$purposes = explode (",", $core['purpose_of_visit']);

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
            $pdf->Cell(42,10,"- ".$purposes[$i],0,1);
            $height_purpose += 20;
            $counter = 0;
        }else{
            $pdf->Cell(42,10,"- ".$purposes[$i],0,0);
            $counter++;
        }
    }
}


if(sizeof($purposes) > 5){
    $height_products = $height_purpose ;
}else{
    $height_products = $height_purpose += 10;
}



$pdf->SetY($height_products);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Products  ',0,0);

$pdf->SetY($height_products + 5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);



$pdf->SetY($height_products += 5);
$pdf->SetFont('Arial','',10);
$counter_pro = 0;
$height_products += 5;
$test = $products;
if($products){
    for($i = 0; $i < sizeof($test); $i++){
        if($counter_pro == 2){
            $pdf->Cell(70,10,"- ".$test[$i]['pro_cat_name'],0,1);
            $height_products += 20;
            $counter_pro = 0;
        }else{
            $pdf->Cell(70,10,"- ".$test[$i]['pro_cat_name'],0,0);
            $counter_pro++;
        }
    }
}



$height = $height_products += 10;

$pdf->SetY($height);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,0,'Competition  ',0,0);

$pdf->SetY($height += 5);
$pdf->SetFont('Arial','B',0);
$pdf->Cell(0,0.5,'',0,0,'L',1);

unset($stmt);

$height = $height += 1;
$counter = 0;
if($competition){
    foreach($competition as $competitions){

        if($height > 280){
            $pdf->AddPage("P", "A4");
            $height = 30;
        }
        $pdf->SetY($height);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(95,10,' Competition Name: ',0,0);
        $pdf->Cell(95,10," Competitor's Strength: ",0,0);
        
        $pdf->SetXY(45,$height);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(90,10,$competitions['com_name'],0,0);
        
        $pdf->SetXY(146,$height);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(90,10,$competitions['com_strength'],0,0);
        
        if($counter < sizeof($competition)-1){
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
$pdf->MultiCell(180,5,($core['description'] != null ? $core['description'] : "--"),1);


$pdf->Output();
?>
