<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

// Check if form is submitted
if (isset($_POST['send_receipt'])) {
    $recipient_email = $_POST['recipient_email'];  // Get the email address from the form

    // Validate email format
    if (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Fetch order details from database (Ensure order_id is provided)
    if (!isset($_GET['order_id'])) {
        echo "Error: Order ID is missing.";
        exit();
    }

    $order_id = intval($_GET['order_id']);

    // Database connection
    include('../config/dbcon.php');
    
    // Retrieve Order Details including invoice_no
    $orderQuery = "
        SELECT 
            orders.table_no, 
            orders.total_price, 
            orders.invoice_no, 
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

    // Retrieve Ordered Items
    $orderItemsQuery = "
        SELECT item_name, quantity, price 
        FROM order_items 
        WHERE order_id = $order_id
    ";
    $orderItemsResult = mysqli_query($conn, $orderItemsQuery);

    // Prepare the order summary in HTML
    $orderSummary = "
        <h2>Order Summary</h2>
        <p><strong>Invoice No:</strong> {$orderDetails['invoice_no']}</p>
        <p><strong>Table No:</strong> {$orderDetails['table_no']}</p>
        <p><strong>Total Price:</strong> RM " . number_format($orderDetails['total_price'], 2) . "</p>
        <p><strong>Payment Mode:</strong> {$orderDetails['payment_method']}</p>
        <table border='1'>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Total Price (RM)</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($item = mysqli_fetch_assoc($orderItemsResult)) {
        $itemTotal = $item['price'] * $item['quantity'];
        $orderSummary .= "
            <tr>
                <td>{$item['item_name']}</td>
                <td>" . number_format($item['price'], 2) . "</td>
                <td>{$item['quantity']}</td>
                <td>" . number_format($itemTotal, 2) . "</td>
            </tr>";
    }

    $orderSummary .= "
            </tbody>
        </table>";

    // Send the email using PHPMailer
    try {
        $mail = new PHPMailer(true);
        
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'tengterrence@gmail.com'; // Your email address
        $mail->Password = 'akzp ckle owaf zwtn'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipient's email
        $mail->setFrom('tengterrence@gmail.com', 'Urban Daybreak');
        $mail->addAddress($recipient_email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Order Receipt';
        $mail->Body    = $orderSummary;
        $mail->AltBody = strip_tags($orderSummary); // Fallback plain text version

        // Send the email
        $mail->send();
        echo 'Receipt has been sent to ' . $recipient_email;
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>
