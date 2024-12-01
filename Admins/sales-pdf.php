<?php 
require('../fpdf/fpdf.php');
include('../config/dbcon.php');

// Check if report_type is set
if (isset($_GET['report_type'])) {
    $reportType = $_GET['report_type'];

    // Get current year, month, and date
    $currentYear = date('Y');
    $currentMonth = date('Y-m');
    $currentDate = date('Y-m-d');

    // Initialize query variables
    $salesQuery = "";
    $title = "";

    // Determine the query based on the report type
    if ($reportType == 'daily') {
        $title = "Daily Sales Report";
        $salesQuery = "
            SELECT orders.total_price, payments.payment_method
            FROM orders 
            JOIN payments ON orders.order_id = payments.order_id
            WHERE DATE(orders.order_date) = '$currentDate' AND orders.is_completed = 1";
    } elseif ($reportType == 'monthly') {
        $title = "Monthly Sales Report";
        $salesQuery = "
            SELECT orders.total_price, payments.payment_method
            FROM orders 
            JOIN payments ON orders.order_id = payments.order_id
            WHERE YEAR(orders.order_date) = YEAR('$currentMonth-01') 
            AND MONTH(orders.order_date) = MONTH('$currentMonth-01') 
            AND orders.is_completed = 1";
    } else {
        $title = "Yearly Sales Report";
        $salesQuery = "
            SELECT orders.total_price, payments.payment_method
            FROM orders 
            JOIN payments ON orders.order_id = payments.order_id
            WHERE YEAR(orders.order_date) = '$currentYear' AND orders.is_completed = 1";
    }

    // Execute the sales query
    $result = mysqli_query($conn, $salesQuery);

    // Create PDF using FPDF
    class PDF extends FPDF
    {
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 0, 'L');
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
        }
    }

    // Create PDF
    $pdf = new PDF();
    $pdf->AddPage();

    // Set company info
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Urban Daybreak', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, '34, Lebuh Bishop, George Town,', 0, 1, 'C');
    $pdf->Cell(0, 10, '10200 George Town, Pulau Pinang', 0, 1, 'C');
    $pdf->Ln(5);  // Gap after company info

    // Add the Sales Report title (with the report type)
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->Ln(10); // Gap after title

    // Table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Payment Method', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Total Sales (RM)', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Total Orders', 1, 1, 'C');

    // Set table content
    $pdf->SetFont('Arial', '', 12);

    // Prepare data aggregation
    $paymentMethods = ['Card' => 0, 'eWallet' => 0, 'Cash' => 0];
    $paymentOrders = ['Card' => 0, 'eWallet' => 0, 'Cash' => 0];
    $grandTotalSales = 0;
    $grandTotalOrders = 0;

    // Loop through results to aggregate sales by payment method
    while ($row = mysqli_fetch_assoc($result)) {
        $paymentMethod = $row['payment_method'];
        if (in_array($paymentMethod, array_keys($paymentMethods))) {
            $paymentMethods[$paymentMethod] += $row['total_price'];
            $paymentOrders[$paymentMethod]++;
        }
    }

    // Output data for each payment method
    foreach ($paymentMethods as $method => $totalSales) {
        if ($totalSales > 0) {  // Only show methods with sales
            $pdf->Cell(60, 10, ucfirst($method), 1, 0, 'C');
            $pdf->Cell(60, 10, number_format($totalSales, 2), 1, 0, 'C');
            $pdf->Cell(60, 10, $paymentOrders[$method], 1, 1, 'C');
            // Add to grand totals
            $grandTotalSales += $totalSales;
            $grandTotalOrders += $paymentOrders[$method];
        }
    }

    // Grand Total row
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Grand Total', 1, 0, 'C');
    $pdf->Cell(60, 10, number_format($grandTotalSales, 2), 1, 0, 'C');
    $pdf->Cell(60, 10, $grandTotalOrders, 1, 1, 'C');  // Display the total orders in Grand Total row

    // Output PDF to the browser
    header('Content-Type: application/pdf');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Display the PDF in browser
    $pdf->Output('I', $title . '.pdf');  // 'I' will display the PDF inline in the browser

} else {
    echo "Error: Report type is missing.";
}
?>
