<?php 
include('includes/header.php');
include('../config/dbcon.php');

// Get the current year
$currentYear = date('Y');

// Fetch total sales for the current year
$queryYearlySales = "
    SELECT SUM(total_price) AS yearly_sales 
    FROM orders 
    WHERE YEAR(order_date) = '$currentYear' AND is_completed = 1";
$resultYearly = mysqli_query($conn, $queryYearlySales);
$rowYearly = mysqli_fetch_assoc($resultYearly);
$yearlySales = number_format($rowYearly['yearly_sales'], 2);

// Handle monthly and daily search
$searchMonth = isset($_GET['search_month']) ? $_GET['search_month'] : date('Y-m'); // Default to current month
$searchDate = isset($_GET['search_date']) ? $_GET['search_date'] : date('Y-m-d'); // Default to today's date

// Query to get daily sales
$queryDailySales = "
    SELECT SUM(total_price) AS daily_sales 
    FROM orders 
    WHERE DATE(order_date) = '$searchDate' AND is_completed = 1";
    
$resultDaily = mysqli_query($conn, $queryDailySales);
$rowDaily = mysqli_fetch_assoc($resultDaily);
$dailySales = number_format($rowDaily['daily_sales'], 2);

// Query to get daily total orders
$queryDailyOrders = "
    SELECT COUNT(order_id) AS daily_orders
    FROM orders 
    WHERE DATE(order_date) = '$searchDate' AND is_completed = 1";
    
$resultDailyOrders = mysqli_query($conn, $queryDailyOrders);
$rowDailyOrders = mysqli_fetch_assoc($resultDailyOrders);
$dailyOrders = $rowDailyOrders['daily_orders'];

// Query to get monthly sales
$queryMonthlySales = "
    SELECT SUM(total_price) AS monthly_sales 
    FROM orders 
    WHERE YEAR(order_date) = YEAR('$searchMonth-01') AND MONTH(order_date) = MONTH('$searchMonth-01') AND is_completed = 1";
$resultMonthly = mysqli_query($conn, $queryMonthlySales);
$rowMonthly = mysqli_fetch_assoc($resultMonthly);
$monthlySales = number_format($rowMonthly['monthly_sales'], 2);

// Query to get monthly total orders
$queryMonthlyOrders = "
    SELECT COUNT(order_id) AS monthly_orders
    FROM orders 
    WHERE YEAR(order_date) = YEAR('$searchMonth-01') AND MONTH(order_date) = MONTH('$searchMonth-01') AND is_completed = 1";
    
$resultMonthlyOrders = mysqli_query($conn, $queryMonthlyOrders);
$rowMonthlyOrders = mysqli_fetch_assoc($resultMonthlyOrders);
$monthlyOrders = $rowMonthlyOrders['monthly_orders'];

// Query to get yearly total orders
$queryYearlyOrders = "
    SELECT COUNT(order_id) AS yearly_orders
    FROM orders 
    WHERE YEAR(order_date) = '$currentYear' AND is_completed = 1";
    
$resultYearlyOrders = mysqli_query($conn, $queryYearlyOrders);
$rowYearlyOrders = mysqli_fetch_assoc($resultYearlyOrders);
$yearlyOrders = $rowYearlyOrders['yearly_orders'];
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <!-- Dynamic year display -->
        <li class="breadcrumb-item active">Sales Report <?= $currentYear; ?></li>
    </ol>

    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4" style="border-radius: 15px;">
                <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 style="font-size: 30px;">Daily Sales</h5>
                </div>
                <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                    <p style="font-size: 26px; padding: 5px; text-align: center;"><strong>RM <?= $dailySales; ?></strong></p>
                    <p style="font-size: 18px; background-color: #d8bc97; color: #3a3a3a; padding: 5px; border-radius: 15px; text-align: center;">Total Orders: <strong><?= $dailyOrders; ?></strong></p>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-radius: 0 0 15px 15px;">
                    <a href="sales-pdf.php?report_type=daily&date=<?= $searchDate ?>" class="btn" style="background-color: #d8bc97; color: #3a3a3a; border: none;">View PDF</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4" style="border-radius: 15px;">
                <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 style="font-size: 30px;">Monthly Sales</h5>
                </div>
                <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                    <p style="font-size: 26px; padding: 5px; text-align: center;"><strong>RM <?= $monthlySales; ?></strong></p>
                    <p style="font-size: 18px; background-color: #d8bc97; color: #3a3a3a; padding: 5px; border-radius: 15px; text-align: center;">Total Orders: <strong><?= $monthlyOrders; ?></strong></p>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-radius: 0 0 15px 15px;">
                    <a href="sales-pdf.php?report_type=monthly&month=<?= $searchMonth ?>" class="btn" style="background-color: #d8bc97; color: #3a3a3a; border: none;">View PDF</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4" style="border-radius: 15px;">
                <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 style="font-size: 30px;">Yearly Sales</h5>
                </div>
                <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                    <p style="font-size: 26px; padding: 5px; text-align: center;"><strong>RM <?= $yearlySales; ?></strong></p>
                    <p style="font-size: 18px; background-color: #d8bc97; color: #3a3a3a; padding: 5px; border-radius: 15px; text-align: center;">Total Orders: <strong><?= $yearlyOrders; ?></strong></p>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(58, 58, 58); color: #d8bc97; border-radius: 0 0 15px 15px;">
                    <a href="sales-pdf.php?report_type=yearly" class="btn" style="background-color: #d8bc97; color: #3a3a3a; border: none;">View PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>  

<?php include('includes/footer.php'); ?>
