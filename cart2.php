<?php
session_start();

include("./include/connection.php");
include("file/header5.php");

// التحقق من اتصال قاعدة البيانات
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

$session_id = session_id();

// ======== معالجة تحديث الكمية ========
if (isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $new_quantity = intval($_POST['quantity']);
    
    $update_query = "UPDATE cart1 SET quantity = ? WHERE cart_id = ? AND session_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'iis', $new_quantity, $cart_id, $session_id);
    mysqli_stmt_execute($stmt);
}

// ======== معالجة حذف المنتج ========
if (isset($_POST['remove_item'])) {
    $cart_id = intval($_POST['cart_id']);
    
    $delete_query = "DELETE FROM cart1 WHERE cart_id = ? AND session_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, 'is', $cart_id, $session_id);
    mysqli_stmt_execute($stmt);
}

// ======== استعلام محتويات السلة ========
$cart_query = "SELECT * FROM cart1 WHERE session_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, 's', $session_id);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

$total_price = 0;
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>سلة التسوق</title>
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-table th, .cart-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .cart-table th {
            background-color: #4CAF50;
            color: white;
        }
        .product-image {
            max-width: 80px;
            max-height: 80px;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
        }
        .action-btn {
            padding: 6px 12px;
            margin: 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
        .update-btn {
            background-color: #2196F3;
            color: white;
        }
        .remove-btn {
            background-color: #f44336;
            color: white;
        }
        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2 style="text-align: center; color: #4CAF50;">سلة التسوق</h2>
    
    <?php if (mysqli_num_rows($cart_items) > 0): ?>
    <table class="cart-table">
        <tr>
            <th>الصورة</th>
            <th>المنتج</th>
            <th>السعر</th>
            <th>الكمية</th>
            <th>الإجمالي</th>
            <th>إجراءات</th>
        </tr>
        
        <?php while ($item = mysqli_fetch_assoc($cart_items)): ?>
        <?php 
            $item_total = $item['price'] * $item['quantity'];
            $total_price += $item_total;
        ?>
        <tr>
            <td>
                <img src="uploads/img/<?= htmlspecialchars($item['img']) ?>" class="product-image" 
                     onerror="this.src='images/default-product.png'">
            </td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?> د.ل</td>
            <td>
                <form method="post" action="cart1.php" style="display: inline;">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                           min="1" max="10" class="quantity-input">
                    <button type="submit" name="update_quantity" class="action-btn update-btn">تحديث</button>
                </form>
            </td>
            <td><?= number_format($item_total, 2) ?> د.ل</td>
            <td>
                <form method="post" action="cart1.php" style="display: inline;">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <button type="submit" name="remove_item" class="action-btn remove-btn">حذف</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
        
        <tr style="font-weight: bold; background-color: #f9f9f9;">
            <td colspan="4" style="text-align: left;">المجموع الكلي:</td>
            <td><?= number_format($total_price, 2) ?> د.ل</td>
            <td></td>
        </tr>
    </table>

    <div style="text-align: left; margin-top: 20px;">
        <a href="index.php" style="padding: 8px 16px; background: #2196F3; color: white; 
           text-decoration: none; border-radius: 4px; margin-left: 10px;">
            استمر بالتسوق
        </a>
        <a href="checkout.php" style="padding: 10px 20px; background: #4CAF50; color: white; 
           text-decoration: none; border-radius: 4px; font-size: 16px;">
            إتمام الشراء
        </a>
    </div>
    <?php else: ?>
    <div class="empty-cart">
        <h3>سلة التسوق فارغة</h3>
        <p>لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
        <a href="index.php" style="padding: 10px 20px; background: #4CAF50; color: white; 
           text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 15px;">
            العودة إلى المتجر
        </a>
    </div>
    <?php endif; ?>
</div>

