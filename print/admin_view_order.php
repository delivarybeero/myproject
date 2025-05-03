<?php
session_start();
if (!isset($_SESSION['EMAIL'])) {
    header("Location: admin.php");
    exit();
}

require_once("../include/connection.php");

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
    die("معرف الطلب غير صالح");
}

// جلب بيانات الطلب
$order_query = "SELECT * FROM orders1 WHERE order_id = ?";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$order = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$order) {
    die("الطلب غير موجود");
}

// جلب عناصر الطلب
$items_query = "SELECT oi.*, p.name, p.image FROM orders1_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt_items = mysqli_prepare($conn, $items_query);
mysqli_stmt_bind_param($stmt_items, 'i', $order_id);
mysqli_stmt_execute($stmt_items);
$items = mysqli_stmt_get_result($stmt_items);
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>تفاصيل الطلب #<?= $order_id ?></