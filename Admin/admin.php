<?php
session_start();
include("../include/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <style>
    body{
        margin:0;
        padding:0;
        background-color:#f4f4f4;
    }
    .container{

        width:400px;
        margin:80px auto;
        padding:30px;
        background-color:#fff;
        box-shadow:  16px 6px 17px rgba(0,0,0,0.1);
    }
h1{
text-align:center;
margin-bottom:20px;

}
form{
    display:flex;
    flex-direction:column;
    align-items:center;
}
label{
    display:block;
    margin-bottom:5px;
}
input {
width: 100%;
padding:10px;
border:1px solid #ccc;
margin-bottom:15px;
}
button{
    width:50%;
    padding:10px 20px ;
    background-color:#007bff;
    color:#fff;
    border:none;
    cursor: pointer;
}
    </style>
 
</head>
<body>
    <main>
        <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // الحصول على البيانات من النموذج
    $email = $_POST['ADemail'];
    $password = $_POST['ADpassword'];
    if(empty($email)||empty($password)){
        echo "<script>alert('ENTER EMAIL AND PASS WORD');</script>";

    }else{  
          $sql = "SELECT * FROM admins WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        // التحقق من النتائج
        if ($result->num_rows > 0) {
            // تسجيل الدخول ناجح
           // $_SESSION['admin_logged_in'] = true;
           $_SESSION['EMAIL']=$email;
           // تخزين البريد الإلكتروني في الجلسة إذا كنت تريد استخدامه لاحقًا
    
            echo "<script>alert('مرحبا بك أيها المدير سيتم تحويلك إلى لوحة التحكم');</script>";
            header("REFRESH:2;URL=admianpanel.php");
            exit();
        } else {
            echo "<script>alert('عفواً لا يمكنك الدخول إلى هذه الصفحة سيتم تحويلك إلى المتجر');</script>";
            header("REFRESH:2;URL=../index.php");
            exit();
        }
    
        // إغلاق الاتصال
        $stmt->close();
        $conn->close();}

    // إعداد جملة SQL للتحقق من البريد الإلكتروني وكلمة المرور

}
?>

        
        
        <div class="container">

<h1>login admin</h1>

            <form action="admin.php" method="post">
                
                <label for="em">Email:</label>
                <input type="email" id="em" name="ADemail" >
                <label for="pass">Password:</label>
                <input type="text" id="pass" name="ADpassword" ><br>
                <button type="submit">Login</button>
            </form>
        </div>
</main>
</body>
</html>