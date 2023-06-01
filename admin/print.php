<?php
session_start();
include('includes/config.php');

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
        protected $col = 0; // Current column
        protected $y0; 
    
        function Header() {
            // Logo
            $this->Image('../assets/images/logg.png',10,6,50);
            // Line break
            $this->SetFont('Arial','B',15);
            $this->Cell(15);
            $this->Cell(0,10,'BOOKINGS',0,0,'C');
            $this->Ln(-3);
            
            $date = isset($_SESSION['date']) ? date($_SESSION['date']) : 'No Selected Date';

            $this->SetCol(2);
            $this->SetFont('Courier','',8);
            $this->Cell(2,4,"                  DATE: " . date("d/m/Y", strtotime($date)),10,0,'');
            $this->Ln(25);
        }

        function Footer() {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            $this->SetTextColor(0);
            // Page number
            $this->Cell(0,10,$this->PageNo(),0,0,'C');
        }
    
        function SetCol($col) {
            // Set position at a given column
            $this->col = $col;
            $x = 10+$col*65;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }

        function AcceptPageBreak() {
            // Method accepting or not automatic page break
            if($this->col<2) {
                // Go to next column
                $this->SetCol($this->col+1);
                // Set ordinate to top
                $this->SetY($this->y0);
                // Keep on page
                return false;
            }
            else
            {
                // Go back to first column
                $this->SetCol(0);
                // Page break
                return true;
            }
        }

        function FancyTable($header, $rowData, $tableHeight) {
            $this->SetCol(0);
            // Colors, line width and bold font
            $this->SetFillColor(128, 128, 128);
            $this->SetTextColor(255);
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(.3);
            $this->SetFont('', 'B');
            // Header
            $w = array(27, 30, 26, 26, 27, 26, 27);
            for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true );
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
    
            global $dbh;
    
            $fill = false;
    
            foreach($rowData as $id) {
                // Check if there is enough space for the next row
                if ($this->GetY() + 6 > $tableHeight) { // Adjust the value 6 as needed
                    $this->AddPage();
                    $this->SetCol(0);
                    $this->SetFont('', 'B');
                    for ($i = 0; $i < count($header); $i++) {
                        $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
                    }
                    $this->Ln();
                    $this->SetFillColor(224, 235, 255);
                    $this->SetTextColor(0);
                    $this->SetFont('');
                    $fill = false;
                }

                $sql = $dbh->query("SELECT tblvehicles.PricePerDay, tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id WHERE tblbooking.id = '{$id}' ORDER BY tblbooking.Status");
                
                foreach($sql as $row) {
                    $startDate = strtotime($row['FromDate']);
                    $endDate = strtotime($row['ToDate']);
                    
                    $days = floor(($endDate - $startDate) / (60 * 60 * 24))  + 1;

                    $this->Cell($w[0], 8,$row['FullName'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[1], 8, $row['VehiclesTitle'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 8, $row['FromDate'], 'LR', 0, 'R', $fill);
                    $this->Cell($w[3], 8, $row['ToDate'], 'LR', 0, 'R', $fill);
                    $this->Cell($w[4], 8, $row['message'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[5], 8, number_format($row['PricePerDay'],2), 'LR', 0, 'R', $fill);
                    $this->Cell($w[4], 8, number_format($days * $row['PricePerDay'],2), 'LR', 0, 'R', $fill);
                    $this->Ln();


                }
        
                $fill = !$fill;
            }
    
    
            // Closing line
            $this->Cell(array_sum($w), 0, '', 'T');
        }

        public function getSale($rowData) {
            global $dbh;
            $total = 0;
            $totalday = 0;
            foreach ($rowData as $id) {
                $sql = $dbh->query("SELECT tblvehicles.PricePerDay, tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id WHERE tblbooking.id = '{$id}' ORDER BY tblbooking.Status");
                
                foreach ($sql as $row) {
                    $startDate = strtotime($row['FromDate']);
                    $endDate = strtotime($row['ToDate']);
                    $days = floor(($endDate - $startDate) / (60 * 60 * 24)) + 1;
                    $totalday += $days * $row['PricePerDay'];
                }
            }
            return $totalday;
        }
    }
    
    $pdf = new PDF();
    $pdf->AddPage();
    
    $header = array('Name', 'Vehicle', 'From date', 'To Date', 'Message', 'Price/Day', 'Total');
    // Data loading
    $pdf->SetFont('Courier', '', 10);
    
    $tableHeight = $pdf->GetPageHeight() - $pdf->GetY() - 1;
    $pdf->FancyTable($header, $_SESSION['ids'], $tableHeight);

    $pdf->SetCol(0);
    $pdf->SetFillColor(128, 128, 128);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('','B');
    $pdf->Cell(162,7,'TOTAL SALE',1,0,'C',true);
    $pdf->Cell(27,7,number_format($pdf->getSale($_SESSION['ids']), 2),1,0,'C',true);

    // Check if there is enough space for the next content
    if ($pdf->GetY() + 20 > $pdf->GetPageHeight()) { // Adjust the value 20 as needed
        $pdf->AddPage();
    }

    // $filename = 'report_' . date('m-d-Y') . '.pdf';
    // $pdf->Output($filename, 'D');
    $pdf->Output();
    exit;
}

