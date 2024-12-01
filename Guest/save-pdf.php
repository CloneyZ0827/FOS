<?php
require('../fpdf/fpdf.php');
include('../config/dbcon.php');

// Check if order_id is set
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);  // Ensure the order_id is an integer

    // Retrieve order details including invoice_no and order_date
    $orderQuery = "
        SELECT 
            orders.table_no, 
            orders.total_price, 
            orders.invoice_no, 
            orders.order_date, 
            payments.payment_method
        FROM orders
        JOIN payments ON orders.order_id = payments.order_id
        WHERE orders.order_id = $order_id
    ";
    $orderResult = mysqli_query($conn, $orderQuery);
    $orderDetails = mysqli_fetch_assoc($orderResult);

    if (!$orderDetails) {
        echo "Error: Order not found.";
        exit();
    }

    // Retrieve ordered items
    $orderItemsQuery = "
        SELECT item_name, quantity, price 
        FROM order_items 
        WHERE order_id = $order_id
    ";
    $orderItemsResult = mysqli_query($conn, $orderItemsQuery);

    // Create PDF using the FPDF library
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

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Urban Daybreak', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, '34, Lebuh Bishop, George Town,', 0, 1, 'C');
    $pdf->Cell(0, 10, '10200 George Town, Pulau Pinang', 0, 1, 'C');
    $pdf->Ln(5);  // Reduced gap after company details

    // Order Details
    $pdf->Cell(0, 8, 'Invoice No: ' . $orderDetails['invoice_no'], 0, 1);
    $pdf->Cell(0, 8, 'Table No: ' . $orderDetails['table_no'], 0, 1);
    $pdf->Cell(0, 8, 'Payment Mode: ' . $orderDetails['payment_method'], 0, 1);
    // Display Order Date
    $pdf->Cell(0, 8, 'Order Date: ' . date('d M Y', strtotime($orderDetails['order_date'])), 0, 1); // Format the date
    $pdf->Ln(5);  // Reduced gap after order details

    // Table for order items
    $col_widths = [70, 40, 40, 40];
    $pdf->SetX(10);
    $pdf->Cell($col_widths[0], 8, 'Product Name', 1, 0, 'C');
    $pdf->Cell($col_widths[1], 8, 'Price (RM)', 1, 0, 'C');
    $pdf->Cell($col_widths[2], 8, 'Quantity', 1, 0, 'C');
    $pdf->Cell($col_widths[3], 8, 'Total (RM)', 1, 1, 'C');

    // Order items
    $grandTotal = 0;
    while ($item = mysqli_fetch_assoc($orderItemsResult)) {
        $itemTotal = $item['price'] * $item['quantity'];
        $grandTotal += $itemTotal;

        $pdf->SetX(10);
        $pdf->Cell($col_widths[0], 8, $item['item_name'], 1, 0, 'L');
        $pdf->Cell($col_widths[1], 8, number_format($item['price'], 2), 1, 0, 'C');
        $pdf->Cell($col_widths[2], 8, $item['quantity'], 1, 0, 'C');
        $pdf->Cell($col_widths[3], 8, number_format($itemTotal, 2), 1, 1, 'C');
    }

    // Grand Total
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Grand Total: RM ' . number_format($grandTotal, 2), 0, 1, 'C');

    // Set headers to force PDF download with custom filename
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="invoice-' . $orderDetails['invoice_no'] . '.pdf"'); // Customize file name here
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output PDF to the browser, forcing download
    $pdf->Output('D', 'invoice-' . $orderDetails['invoice_no'] . '.pdf');  // 'D' forces the download
} else {
    echo "Error: Order ID is missing.";
}
?>
