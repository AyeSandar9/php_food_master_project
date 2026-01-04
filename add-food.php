<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>
        <br><br>

        <?php 
    if(isset($_SESSION['upload'])){
        echo $_SESSION['upload'];
        unset($_SESSION['upload']);
    }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table  class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" required name="title" placeholder="Title of the Food">
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" id="" col="30" rows="5" placeholder="Description of the Food."></textarea>
                    </td>
                </tr>

                <tr>
                <td>Price:</td>
                    <td>
                        <input type="num" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
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
                            //We have categories
                            while($row=mysqli_fetch_assoc($res))
                            {
                                //get the details of categories
                                $id=$row['id'];
                                $title=$row['title'];
                                ?>
                                <option value="<?php echo $id; ?>"><?php echo $title;?></option>
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
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No  
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                    </td>
                </tr>

            </table>


        </form>







<?php
//Check whether the button is clicked or not
if(isset($_POST['submit'])){
    //Add the Food in database
   // echo "Cl";

   //1.Get the Data from Form 
   $title=$_POST['title'];
   $description=$_POST['description'];
   $price=$_POST['price'];
   $category=$_POST['category'];

   //Check whether radion button for featured and active are checked or not

   if(isset($_POST['featured']))
   {
    $featured=$_POST['featured'];
   }
   else{
    $featured="No";//Setting the default value
   }

   if(isset($_POST['active']))
   {
    $active=$_POST['active'];
   }
   else{
    $active="No";//Setting default value
   }



   //2.Upload the image if selected 
   //Check whether the select image is clicked or not and upload the  image only if the image is selected
   if(isset($_FILES['image']['name']))
   {
    //GEt the details of the selected image
     $image_name=$_FILES['image']['name'];

     //Check whether the image is selected or not and upload image only if selected

      if($image_name!=""){
         //image is selected
        //A.Rename the image 
        //Get the extension of selected image (jpg,png,gif,etc.) "viajy-th.jpg"


        $ext=end(explode('.',$image_name));


        //Create new name for image
        $image_name="Food-Name-".rand(0000,9999).".".$ext;//New Image Name May Be "Food_Name-553";

         //B.Upload the image
        //Get the src path and destination path

//source path is the current location of the image 
        $src=$_FILES['image']['tmp_name'];

         //destination path for the image to be uploaded
        $dst="../images/food/".$image_name;

                //Finally upload the food image 
        $upload=move_uploaded_file($src,$dst);

        //check whether image uploaded of not
        if($upload==false){
            //Failed to upload the image
            //Redirect to add food page with error messate
            $_SESSION['upload']="<div class='error'>Failed to Upload Image .</div>";
            header('location:'.SITEURL.'admin/add-food.php');
            //Stop the process

            die();
        }




       












      }

   }
  
 
else{
   $image_name="";//Select default value as blank
   }


   //3.Insert into Database

   //Create a SQL Query to Save or add food

   $sql2="INSERT INTO tbl_food SET
   title='$title',
   description='$description',
   price='$price',
   image_name='$image_name',
   category_id='$category',
   featured='$featured',
   active='$active'
   ";

   //Execute the Query
   $res2=mysqli_query($conn,$sql2);

   //check whether data  inserted or not 
//4.Redirect with message to Manage Food Page

   if($res2==true){
    //Data inserted successfully
    $_SESSION['add']="<div class='success'>Food Added successfully.</div>";
    header('location:'.SITEURL.'admin/manage-food.php');
   }
   else{
    //failed to INsert data
    $_SESSION['add']="<div class='error'>Failed to Add Food.</div>";
    header('location:'.SITEURL.'admin/manage-food.php');
   }
   
}

?>


















    </div>
</div>

<?php include('partials/footer.php'); ?>