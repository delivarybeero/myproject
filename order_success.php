<?php
session_start();
include("./include/connection.php");

include("file/header5.php");

if (!isset($_SESSION['order_id'])) {
    header("Location: cart1.php");
    exit();
}

$order_id = $_SESSION['order_id'];
unset($_SESSION['order_id']);
?>

<div class="order-success">
    <div class="success-icon">✓</div>
    <h2>شكراً لطلبك!</h2>
    <p>تم استلام طلبك بنجاح</p>
    <p>رقم الطلب: <strong>#<?= $order_id ?></strong></p>
    <p>سيتم التواصل معك قريباً لتأكيد التفاصيل</p>
    <a href="index.php" class="back-to-shop">العودة إلى المتجر</a>
</div>

<style>
    .order-success {
        text-align: center;
        padding: 50px 20px;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .success-icon {
        font-size: 60px;
        color: #4CAF50;
        margin: 20px 0;
    }
    
    .back-to-shop {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #333;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }
</style>

