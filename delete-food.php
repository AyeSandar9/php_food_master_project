
<?php 


//include Constants file

include('../config/Constants.php');

//chec whether the id and image_name value is set or not
if(isset($_GET['id'])AND isset($_GET['image_name']))
{
//Get the value and deleted 

//echo "h";

$id=$_GET['id'];
$image_name=$_GET['image_name'];

//Remove the physical image file is available
if($image_name!=""){
    //Image is available , So remove it 
    $path="../images/food/".$image_name;
    //Remove the image
    $remove=unlink($path);

//If failed to remove image
    if($remove==false)
    {
        //Set the Sesssion message
        $_SESSION['upload']="<div class='error'>Failed to Remove Food image.</div>";

        // Redirect to Manage Category Page
        header('location:'.SITEURL.'admin/manage-food.php');

        //Stop the process
        die();
    }
}

//Delete Data From Database
//SQL Query to Delete Data from database
    $sql="DELETE FROM tbl_food WHERE id=$id";


//Execute the Query
 $res=mysqli_query($conn,$sql);

 //Check whether the data is delete from database or not
  if($res==true){
        //Set Success Message and Redirect
        $_SESSION['delete']="<div class='success'>Food Deleted successfully</div>";
        //Redirect to Message Category
        header('location:'.SITEURL.'admin/manage-food.php');

    }else{
        //set fail message and Redirect
        $_SESSION['delete']="<div class='error'>Failed to Delete Food</div>";
        //Redirect to Message Category
        header('location:'.SITEURL.'admin/manage-food.php');
    }

}
else{
    //redirect to manage category page
    $_SESSION['unauthorize']="<div class='error'>Unauthorized Access.</div>";
    header('location:'.SITEURL.'admin/manage-food.php') ;
}



 ?>