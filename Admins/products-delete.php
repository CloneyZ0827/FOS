<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $menuId = validate($paraResultId);
    
    $menu = getById('menu',$menuId);

    if($menu['status'] == 200)
    {
        $response = delete('menu', $menuId);
        if($response)
        {
            $deleteImage = "../".$product['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
            
            redirect('products.php','Menu Deleted Successfully.');
        }
        else
        {
            redirect('products.php','Something Went Wrong.');
        }
    }
    else
    {
        redirect('products.php',$menu['message']);
    }

} else {
    redirect('products.php','Something Went Wrong.');
}


?>