<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Admin
                <a href="admins.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

                <?php  
                    if(isset($_GET['id']))
                    {
                        if($_GET['id'] != ''){

                            $adminId = $_GET['id'];
                        } 
                        else 
                        {
                            echo '<h5>No Id Found</h5>';
                            return false;
                        }
                    }
                    else
                    {
                        echo '<h5>No Id give in url</h5>';
                        return false;
                    }

                    $adminData = getById('admins', $adminId);
                    if($adminData)
                    {
                        if($adminData['status'] == 200)
                        {
                            ?>
                            <input type="hidden" name="adminId" value="<?= $adminData['data']['id']; ?>">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Name *</label>
                                    <input type="text" name="name" required value="<?= $adminData['data']['a_name']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email *</label>
                                    <input type="email" name="email" required value="<?= $adminData['data']['a_email']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="password">Password *</label>
                                    <input type="password" name="password" minlength="8" class="form-control" />
                                    <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Position *</label>
                                    <input type="text" name="position" required value="<?= $adminData['data']['a_position']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="">Is Ban</label>
                                    <br/>
                                    <input type="checkbox" name="is_ban" value="true" <?php echo $adminData['data']['is_ban'] == 1 ? 'checked' : ''; ?> style="width:30px;height:30px;" />
                                </div>
                                <div class="col-md-12 mb-3 text-end">
                                    <button type="submit" name="updateAdmin" class="btn btn-primary">Update</button>
                                </div>

                            </div>
                            <?php
                        }
                        else
                        {
                            echo '<h5>'.$adminData['message'].'</h5>';
                        }
                    }
                    else
                    {
                        echo 'Something Went Wrong!';
                        return false;
                    }
                ?>

                

            </form>
        </div>
    </div>

</div>  

<?php include('includes/footer.php'); ?>