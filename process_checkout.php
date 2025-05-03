<?php
session_start();
include("./include/connection.php");

// التحقق من وجود طلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit();
}

// جمع بيانات الطلب
$session_id = session_id();
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
$total_amount = floatval($_POST['total_amount']);

// 1. إنشاء الطلب في جدول orders1
$order_query = "INSERT INTO orders1 (
    user_id,
    order_date,
    total_amount,
    status,
    phone,
    session_id,
    shipping_address
) VALUES (
    0,  -- يمكن استبدالها بـ user_id إذا كان لديك نظام مستخدمين
    NOW(),
    ?,
    'pending',
    ?,
    ?,
    ?
)";

$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, 'dsss', $total_amount, $phone, $session_id, $address);
mysqli_stmt_execute($stmt);
$order_id = mysqli_insert_id($conn);

// 2. إضافة عناصر الطلب إلى orders1_items
$items_query = "SELECT * FROM cart1 WHERE session_id = ?";
$stmt = mysqli_prepare($conn, $items_query);
mysqli_stmt_bind_param($stmt, 's', $session_id);
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt);

while ($item = mysqli_fetch_assoc($items)) {
    $insert_item = "INSERT INTO orders1_items (
        order_id,
        product_id,
        quantity,
        price
    ) VALUES (?, ?, ?, ?)";
    
    $stmt_item = mysqli_prepare($conn, $insert_item);
    mysqli_stmt_bind_param($stmt_item, 'iiid', 
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    );
    mysqli_stmt_execute($stmt_item);
}

// 3. تفريغ السلة
$clear_cart = "DELETE FROM cart1 WHERE session_id = ?";
$stmt = mysqli_prepare($conn, $clear_cart);
mysqli_stmt_bind_param($stmt, 's', $session_id);
mysqli_stmt_execute($stmt);

// 4. توجيه إلى صفحة التأكيد
$_SESSION['order_id'] = $order_id;
header("Location: order_success.php");
exit();
?>