<?php 
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white bg-[url('../assets/images/logg.png')] bg-no-repeat">
    <div class="container mx-auto px-4 pt-4">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Car Rental Invoice</h1>
            <p class="text-gray-600">Invoice #INV00<?= $_GET['pid'] ?> | Date: <?= date('F d, Y') ?> </p>
        </div>

        <?php 
        $qry = $dbh->query("
                SELECT bk.*, bd.*, vh.*, us.*
                from tblbooking as bk 
                    join tblvehicles as vh 
                            on vh.id = bk.VehicleId 
                    join tblusers as us 
                            on us.EmailId = bk.userEmail 
                    join tblbrands as bd 
                            on vh.VehiclesBrand = bd.id
                WHERE bk.id = '{$_GET['pid']}'
                ORDER BY bk.Status
            ");

        foreach ($qry as $row) {
            $startDate = strtotime($row['FromDate']);
            $endDate = strtotime($row['ToDate']);
            $days = floor(($endDate - $startDate) / (60 * 60 * 24)) + 1;
            $totalday = $days * $row['PricePerDay'];
        ?>
        <div class="mt-8">
            <div class="flex justify-between mb-2 pb-2 border-b-2 border-gray-700">
                <div>
                    <h2 class="text-xl font-bold">Billing Details</h2>
                    <p class="text-gray-600"><?= $row['FullName'] ?></p>
                    <p class="text-gray-600"><?= $row['Address'] ?></p>
                    <p class="text-gray-600">Email: <?= $row['EmailId'] ?></p>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Rental Details</h2>
                    <p class="text-gray-600">Rental Period: <?= date('F d, Y', strtotime($row['FromDate'])) . ' - ' . date('F d, Y', strtotime($row['ToDate']))?></p>
                    <p class="text-gray-600">Vehicle: <?= $row['BrandName'] ?></p>
                    <p class="text-gray-600">Rate: ₱<?= number_format($row['PricePerDay'],2) ?> per day</p>
                </div>
            </div>

            <table class="w-full mb-4 pb-4 border-b-2 border-gray-700">
                <thead>
                    <tr>
                        <th class="text-left">Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="border-b-2 border-gray-700">
                    <tr>
                        <td class="py-2">Rental Charges</td>
                        <td class="py-2 text-right">₱<?= number_format($totalday,2) ?></td>
                    </tr>
                    <tr>
                        <td class="py-2">Fuel Fee</td>
                        <td class="py-2 text-right">₱1,000.00</td>
                    </tr>
                    <tr>
                        <td class="py-2">Discount</td>
                        <td class="py-2 text-right">-₱0.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="py-2 text-right">Total Amount Due</td>
                        <td class="py-2 text-right">₱<?= number_format($totalday + 1000.00, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-right">
                <h2 class="text-lg font-bold">Payment Instructions</h2>
                <p class="text-gray-600">Please make the payment by <?= date('F d, Y', strtotime($row['ToDate'])) ?>.</p>
                <p class="text-gray-600">Accepted payment methods: Cash, Credit Card, Bank Transfer.</p>
                <p class="text-gray-600">Bank Account Details: ABC Bank, Account Number: 123456789</p>
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600">Thank you for choosing our car rental service!</p>
                <p class="text-gray-600">For any inquiries, please contact us at abegurl@gmail.com.</p>
            </div>
        </div>
        <?php } ?>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        window.onbeforeprint = function() {
            console.log("Print button clicked");
        };

        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
