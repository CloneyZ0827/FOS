<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Menu
                <a href="products.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="code.php" method="POST" enctype="multipart/form-data">

                <?php
                    $paramValue = checkParamId('id');
                    if(!is_numeric($paramValue)){
                        echo '<h5>Id is not an integer</h5>';
                        return false;
                    }

                    $menu = getById('menu',$paramValue);
                    if($menu)
                    {
                        if($menu['status'] == 200)
                        {
                        ?>

                <input type="hidden" name="menu_id" value="<?= $menu['data']['id']; ?>">

                <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Select Menu</label>
                    <select name="category_id" class="form-select">
                        <option value="">Select Category</option>
                        <?php
                        $categories = getAll('categories');
                        if($categories){
                            if(mysqli_num_rows($categories) > 0){
                                foreach($categories as $cateItem){
                                    ?>
                                        <option value="<?= $cateItem['id']; ?>'"
                                        <?= $menu['data']['category_id'] == $cateItem['id'] ? 'selected':''; ?>
                                        >
                                            <?= $cateItem['name']; ?>
                                        </option>';
                                    <?php
                                }
                            }else{
                                echo '<option value="">No Categories Found</option>';
                            }
                        }else{
                            echo '<option value="">Something Went Wrong!</option>';
                        }
                        ?>
                    </select>
                </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Menu Name *</label>
                        <input type="text" name="name" required value="<?= $menu['data']['name']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Menu Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= $menu['data']['description']; ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Price *</label>
                        <input type="text" name="price" value="<?= $menu['data']['price']; ?>" required class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Quantity *</label>
                        <input type="text" name="quantity" value="<?= $menu['data']['quantity']; ?>" required class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Image *</label>
                        <input type="file" name="image" class="form-control" />
                        <img src="../<?= $menu['data']['image']; ?>" alt="Img" style="width:40px;height:40px;"/>
                    </div>

                    <div class="col-md-6">
                        <label>Status (UnChecked=Visible, Checked=Hidden)</label>
                        <br/>
                        <input type="checkbox" name="status" value="<?= $menu['data']['status'] == true ? 'checked':''; ?>" style="width:30px;height:30px";>
                    </div>
                    <div class="col-md-12 mb-3 text-end">
                        <br/>
                        <button type="submit" name="updateMenu" class="btn btn-primary">Update</button>
                    </div>
                </div>

                <?php
                
                        }
                        else
                        {
                            echo '<h5>'.$menu['message'].'</h5>';
                        }
                    }
                    else
                    {
                        echo '<h5>Something Went Wrong!</h5>';
                        return false;
                    }
                ?>
            </form>
        </div>
    </div>

</div>  

<?php include('includes/footer.php'); ?>