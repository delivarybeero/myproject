<?php
include "../include/connection1.php";


session_start();
echo "<center><h3>لوحة تحكم خاصة بالادمن</h3></center>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">  


<link rel="stylesheet" href="style1.css">
    
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>control panel for admin</title>
</head>
<body>
    <?php
if(!isset($_SESSION['EMAIL'])){

header('location:../index.php');


}else{


?>

<?php
$section_name=$_POST['section_name'];
$secadd=$_POST['secadd'];
$id=$_GET['id'];



if(isset($secadd)){
  if(empty($section_name)){
     echo'<script> alert ("ادخل اسم القسم");</script>';
  }
  elseif (strlen($section_name)>50) {
    echo'<script> alert ("اسم القسم طويل");</script>';
  }
  else{
      $query="INSERT INTO section (section_name)VALUES('$section_name')";
      $result=mysqli_query($conn,$query);
      echo'<script> alert ("تم اضافة قسم بنجاح");</script>';

  }

}


?>
<?php
#delete section
if (isset($id)){

  $query="DELETE FROM section  WHERE  id='$id'";
  $delet=mysqli_query($conn,$query);
    if(isset($delet)){

      echo '<script> alert ("تم حذف القسم")</script>';
    }else {
      echo '<script> alert ("لم يتم حذف القسم ")</script>';

    }
}

?>




<!---sidebar start--->
 <div class="sidbar_container">


<div class="sidebar">

<center><h1></h1></center>

    <ul>
   
    <li><a href="../index.php" target="_blank" > الرئيسية<i class="fa-solid fa-house-chimney"></i></a></li>
    <li><a href="admin_orders1.php" target="_blank" > صفحة الطلبات <i class="fa-solid fa-folder-open"></i></a></li>
    <li><a href="" target="_blank" > user info<i class="fa-solid fa-users"></i></a></li>
    <li><a href="product.php" target="_blank" >    صفحة المنتجات <i class="fa-solid fa-gift"></i></a></li>
    <li><a href="design_page.php" target="_blank" >    اعدادت المتجر <i class="fas fa-cog"></i></a></li>

    <li><a href="addproduct.php" target="_blank" >     اضافة منتج<i class="fa-solid fa-plus"></i></a></li>
    <li><a href="logout.php" target="_blank" >         خروج من 
    <i class="fa-solid fa-share-from-square"></i></a></li>
</ul>
<br><br><br><br>
</div>

 
<!---section start--->

<div class="content_sec"> 
    <form action="adminpanal.php"  method="post">
        <label for="section" > اضف قسم جديد</label>
        <input type="text" name="section_name"  id ="section">
        <br>
        <button class="add"  type="submit" name="secadd">اضافة قسم</button>
      </form>

<!---table start--->

        <table dir="rtl">
    <tr>
        <th>الرقم</th>
        <th>اسم القسم</th>
        <th>حذف قسم</th>
   </tr>
   <tr>
    <?php
$query="select *from section; ";
$result=mysqli_query($conn,$query);
while ($row=mysqli_fetch_assoc($result)){
  ?>
    <td><?php echo $row['id'];?></td>
    <td><?php echo $row['section_name'];?></td>
    <td><a href="adminpanal.php?id=<?php echo $row['id'];?>">
      <button type="submit"  class="delet">حذف قسم</button></a></td>

  </tr>
<?php
}

?>
    </table>

    <br><br>
<!---table end--->
  </div>
<!---section end--->


  </div>
<br><br>
<?php
///close else
}
?>
</body>
</html>


