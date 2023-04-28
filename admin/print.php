<?php
session_start();

function set_session(){
    if (empty($_POST['rowData']) || empty($_POST['rowData'][0])) {
        echo '1';
        exit;
    }

    $_SESSION['ids'] = $_POST['rowData'];
    $_SESSION['date'] = $_POST['date'];
}

if (isset($_GET['a']) && $_GET['a'] == 1) {
    set_session();
    exit;
}

if (isset($_GET['a']) && $_GET['a'] == 2) {
    require("pdfreport/fpdf.php");

    class PDF extends FPDF {
        function FancyTable($header, $rowData) {
            // Colors, line width and bold font
            $this->SetFillColor(255, 0, 0);
            $this->SetTextColor(255);
            $this->SetDrawColor(128, 0, 0);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B');
            // Header
            $w = array(32, 32, 32, 32, 32, 32);
            for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true );
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
    
            include('includes/config.php');
    
            
    
            $fill = false;
    
            foreach($rowData as $id) {
                $sql = $dbh->query("SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id WHERE tblbooking.id = '{$id}' ORDER BY tblbooking.Status");
                
                foreach($sql as $row) {
                    $this->Cell($w[0], 6,$row['FullName'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[1], 6, $row['VehiclesTitle'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $row['FromDate'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[3], 6, $row['ToDate'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[4], 6, $row['message'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[5], 6, date('Y-m-d', strtotime($row['PostingDate'])), 'LR', 0, 'L', $fill);
                    $this->Ln();
                }
        
                $fill = !$fill;
            }
    
    
            // Closing line
            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }
    
    $pdf = new PDF();
    $pdf->AddPage();
    
    $pdf->SetX(7);
    $pdf->SetFont('Courier','',10);
    $date = isset($_SESSION['date']) ? date($_SESSION['date']) : '';
    $pdf->Cell(20,10,"Date : $date",0,1,'');
    
    $header = array('Name', 'Vehicle', 'From date', 'To Date', 'Message', 'Posting date');
    // Data loading
    $pdf->SetFont('Courier', '', 10);
    $pdf->FancyTable($header, $_SESSION['ids']);

    // $filename = 'report_' . date('m-d-Y') . '.pdf';
    // $pdf->Output($filename, 'D');
    $pdf->Output();
    exit;
}

