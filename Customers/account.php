<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Account</h1>
    <ol class="breadcrumb mb-4">
        <?php  
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $customerId = $_GET['id'];
            // Fetch customer data from the database
            $customerData = getById('customer', $customerId); // Ensure you have this function available
            if ($customerData && $customerData['status'] == 200) {
                // Customer name available, proceed to display
                ?>
                <li class="breadcrumb-item active"><?= htmlspecialchars($customerData['data']['cName']); ?></li>
                <?php
            } else {
                echo '<li class="breadcrumb-item active">Customer Not Found</li>';
            }
        } else {
            echo '<li class="breadcrumb-item active">No ID provided</li>';
        }
        ?>
    </ol>
    <div class="card mt-4 shadow-sm col-md-6">
        <div class="card-header">
            <h4 class="mb-0">View Account
                <a onclick="window.location.href='edit-account.php?id=<?= $_SESSION['customerUser']['id']; ?>';" class="btn btn-primary float-end">Edit</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <div class="row">
                <?php  
                if (isset($_GET['id']) && $_GET['id'] != '') {
                    $customerId = $_GET['id'];

                    // Fetch customer data from the database
                    $customerData = getById('customer', $customerId);
                    if ($customerData) {
                        if ($customerData['status'] == 200) {
                            // Display customer details without input fields
                            ?>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="label-bold">Name</label>
                                <p class="text-small"><?= htmlspecialchars($customerData['data']['cName']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="label-bold">Email</label>
                                <p class="text-small"><?= htmlspecialchars($customerData['data']['email']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="label-bold">Phone No.</label>
                                <p class="text-small"><?= htmlspecialchars($customerData['data']['phoneNo']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reg_date" class="label-bold">Registration Date</label>
                                <p class="text-small"><?= date('F j, Y', strtotime($customerData['data']['RegistrationDate'])); ?></p>
                            </div>
                            <?php
                        } else {
                            echo '<h5>' . $customerData['message'] . '</h5>';
                        }
                    } else {
                        echo 'Something went wrong! Please try again.';
                        return false;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Make labels bold and increase the font size */
.label-bold {
    font-weight: bold;
    font-size: 1.2rem;  /* Adjust size as needed */
}

/* Make the paragraph text smaller than the label */
.text-small {
    font-size: 1rem;  /* Adjust size as needed */
}
</style>
<?php include('includes/footer.php'); ?>
