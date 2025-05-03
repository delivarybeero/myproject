<?php
session_start();
include("./include/connection.php");
include("file/header5.php");

// التحقق من وجود عناصر في السلة
$session_id = session_id();
$cart_query = "SELECT * FROM cart1 WHERE session_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, 's', $session_id);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($cart_items) == 0) {
    header("Location: cart1.php");
    exit();
}
?>
<!DOCTYPE html>

<html lang="ar" dir="rtl">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="checkout-container">
    <h2>إتمام عملية الشراء</h2>
    
    <div class="checkout-steps">
        <!-- الخطوة 1: تأكيد الطلب -->
        <div class="step active">
            <h3>1. تأكيد الطلب</h3>
            <div class="order-summary">
                <?php
                $total = 0;
                mysqli_data_seek($cart_items, 0);
                while ($item = mysqli_fetch_assoc($cart_items)) {
                    $item_total = $item['price'] * $item['quantity'];
                    $total += $item_total;
                    echo '<div class="order-item">';
                    echo '<img src="uploads/img/'.$item['img'].'" width="50">';
                    echo '<span>'.$item['name'].' ('.$item['quantity'].')</span>';
                    echo '<span>'.number_format($item_total, 2).' د.ل</span>';
                    echo '</div>';
                }
                ?>
                <div class="order-total">
                    <strong>المجموع الكلي: <?= number_format($total, 2) ?> د.ل</strong>
                </div>
            </div>
        </div>
        
        <!-- الخطوة 2: معلومات الشحن -->
        <div class="step">
            <h3>1. معلومات الشحن</h3>
            <form method="post" action="process_checkout.php">
                
                <div class="form-group">
                    <label>عنوان التوصيل:</label>
                    <textarea name="address" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>رقم الهاتف:</label>
                    <input type="tel" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label>طريقة الدفع:</label>
                    <select name="payment_method" required>
                        <option value="cash">نقداً عند الاستلام</option>
                    </select>
                </div>
                
                <input type="hidden" name="total_amount" value="<?= $total ?>">
                
                <button type="submit" name="complete_order" class="checkout-btn">
                    تأكيد الطلب
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .checkout-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
    }
    
    .checkout-steps {
        margin-top: 30px;
    }
    
    .step {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    
    .step.active {
        border-color: #4CAF50;
    }
    
    .order-summary {
        margin: 15px 0;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .order-item img {
        margin-right: 15px;
    }
    
    .order-item span {
        flex: 1;
    }
    
    .order-total {
        padding: 15px;
        text-align: right;
        font-size: 1.2em;
        border-top: 2px solid #4CAF50;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .checkout-btn {
        background: #4CAF50;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }
</style>


</body>
</html>
