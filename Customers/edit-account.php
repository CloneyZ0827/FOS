<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Account
                <a onclick="window.location.href='account.php?id=<?= $_SESSION['customerUser']['id']; ?>';" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

                <?php  
                if (isset($_GET['id']) && $_GET['id'] != '') {
                    $customerId = $_GET['id'];
                } else {
                    echo '<h5>No ID provided in the URL.</h5>';
                    return false;
                }

                // Fetch customer data from the session
                $customerData = getById('customer', $customerId); // Fetch customer data
                if ($customerData) {
                    if ($customerData['status'] == 200) {
                        ?>
                        <input type="hidden" name="customerId" value="<?= $customerData['data']['id']; ?>">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Name *</label>
                                <input type="text" name="name" required value="<?= isset($_SESSION['customerUser']['name']) ? htmlspecialchars($_SESSION['customerUser']['name']) : ''; ?>" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Email *</label>
                                <input type="email" name="email" required value="<?= isset($_SESSION['customerUser']['email']) ? htmlspecialchars($_SESSION['customerUser']['email']) : ''; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="password">Password *</label>
                                <input type="password" name="password" minlength="8" class="form-control" />
                                <small class="form-text text-muted">Leave blank to keep the existing password. Password must be at least 8 characters long.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Phone No. *</label>
                                <input type="phone" name="phone" required value="<?= isset($_SESSION['customerUser']['phone']) ? htmlspecialchars($_SESSION['customerUser']['phone']) : ''; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateCustomer" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<h5>' . $customerData['message'] . '</h5>';
                    }
                } else {
                    echo 'Something went wrong! Please try again.';
                    return false;
                }
                ?>

            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
