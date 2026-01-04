<?php
include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>





<?php

//check  whether the id is set or not
if(isset($_GET['id']))
{
    //GEt the ID and all other details
    //echo "Getting The data";
    $id=$_GET['id'];

    //Create SQL Query to get all other details
    $sql2="SELECT * FROM tbl_food WHERE id=$id";

    //Execute the Query
    $res2=mysqli_query($conn,$sql2);

    ////Count the rows to check whether the id is valid or not 
    $count=mysqli_num_rows($res2);
    //if($count==1){
        //Get all the data 
        $row=mysqli_fetch_assoc($res2);
        $title=$row['title'];
       $description=$row['description'];
       $price=$row['price'];
      $current_image=$row['image_name'];
      $current_category=$row['category_id'];
        $featured=$row['featured'];
        $active=$row['active'];

   // }else{
        //Redirect to manage category with session message
     //   $_SESSION['no-category-found']="<div class='error'>Category not found.</div>";
      //  header('location:'.SITEURL.'admin/manage-category.php');
   // }

}else{
    //Redirect to Manage category
    header('location:'.SITEURL.'admin/manage-food.php');
}

?>




        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Food Title goes here." value="<?php echo $title; ?>">
    
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" id="" col="30" rows="5" placeholder="Description of the Food." ><?php echo $description;?></textarea>
                    </td>
                </tr>

                 <tr>
                <td>Price:</td>
                    <td>
                        <input type="num" name="price" value="<?php echo $price ; ?>">
                    </td>
                </tr>
    
              
     <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php

                        if($current_image==""){
                            //Display the image
                            

                             echo "<div class='error'>Image Not Added.</div>";
                            
                            
                        }else{

                            ?>
                           
                            <img src="<?php echo SITEURL;?>images/food/<?php echo $current_image; ?>" width="150px">

                            <?php
                        }
                       
                        ?>
                    </td>
                </tr>

                 <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>


                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category" id="">
                        <?php
                        //Current PHP Code to display categories from database
                        //1.Create SQL to get all active categories from database
                        $sql="SELECT * FROM tbl_category WHERE active='Yes'";

                        $res=mysqli_query($conn,$sql);

                        //Count Rows to check whether we have categories or not
                        $count=mysqli_num_rows($res);

                        //If count is greater than zero, we have categories else we do not have categories
                        if($count>0){
                           //Category available
                            while($row=mysqli_fetch_assoc($res))
                            {
                                $category_title=$row['title'];
                                $category_id=$row['id'];

                                ?>
 <option <?php if($current_category==$category_id){echo "selected";}?> value="<?php echo $category_id; ?>"><?php echo $category_title;?></option>
                              
                                <?php
                            }

                        }else{
                            //We do not have category
                            ?>
                            <option value="0">No Category Found</option>
                            <?php
                        }


                        //Display on Dropdown
                         ?>
                           
                        </select>
                    </td>
                </tr>

                 <tr>
                    <td>Featured:</td>
                    <td>
                         <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>

                                <tr>
                    <td>Active:</td>
                    <td>
                          <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if($featured=="No"){echo "checked";} ?>  type="radio" name="active" value="No">No
                    </td>
                </tr>

                   <tr>
                    <td colspan="2">
                     <input type="hidden" name="id" value="<?php echo $id;?>"> 
                    <input type="hidden" name="current_image" value="<?php echo $current_image;?> ">
                   
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
                
                
            </table>

        </form>












   



<?php 
if(isset($_POST['submit']))
{
   // echo "Clicked";
   //1.Get all the values from our form
   $id=$_POST['id'] ;
   $title=$_POST['title'];

    
      $current_image=$_POST['current_image'];
     
        $featured=$_POST['featured'];
        $active=$_POST['active'];
   

   //2.Uploading new image if selected 
   //Check whether the image is selected or not
   if(isset($_FILES['image']['name']))
   {
    //Get the Image Details
    $image_name=$_FILES['image']['name'];

    //Check whether the image is selected or not
    if($image_name!=""){
        //Image available
        //Upload the New Image

            //Auto Rename our Image
            //Get the Extension of our image(jpg,png,gif,etc)e.g."specialfood1.jpg"
            $ext=end(explode('.',$image_name));

            //Rename the Image
            $image_name="Food-Category-".rand(0000,9999).'.'.$ext;//e.g.Food_Category_834.jpg
            

            $source_path=$_FILES['image']['tmp_name'];

            $destination_path="../images/category/".$image_name;

            //Finally Upload the image
            $upload=move_uploaded_file($source_path,$destination_path);

            //Check whether the image is uploaded or not
            //And if the image is not uploaded then we will stoop the process and redirect with error message
            if($upload==false){
                //Set message
                $_SESSION['upload']="<div class='error'>Failed to Upload Image.</div>";

                //Redirect to Add Category Page
                header('location:'.SITEURL.'admin/manage-category.php');
                //Stop the process
                die();
            }

        //Remove the Current Image if available
        if($current_image!=""){
                



$remove_path="../images/category/".$current_image;
        $remove=unlink($remove_path);

        //check whether the image is removed  or not
        //If failed to remove then display message and stop the process
        if($remove==false){
            //Failed to remove image
            $_SESSION['failed-remove']="<div class='error'>Failed to remove current Image.</div>";
            header('location:'.SITEURL.'admin/manage-category.php');
            die();//Stop the Process
        }


        }
        

    }else{
        $image_name=$current_image;
    }
   }else{
    $image_name=$current_image;
   }

   //3.Update the database
   $sql3="UPDATE tbl_food SET
   title='$title',
   
   image_name='$image_name',
   
   featured='$featured',
   active='$active'
   WHERE id=$id
   ";

   //Execute the Query
   $res3=mysqli_query($conn,$sql3);

   //4.Redirect to Manage Category with message
   //Check whether executed or not
   if($res3==true) {
    //Category Updated
    $_SESSION['update']="<div class='success'>Food Updated Successfully.</div>";
    header('location:'.SITEURL.'admin/manage-food.php');
   }else{
    //Failed to update category
     $_SESSION['update']="<div class='error'>Failed to Update Foody .</div>";
    header('location:'.SITEURL.'admin/manage-food.php');
   }
}
?>



 </div>
</div>

<?php
include('partials/footer.php');
?>