<?php
session_start();
include("include/connection.php");
include("file/header.php");

if (isset($_POST['complete_order'])) {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Pending';
    $session_id = session_id();
    $user_id = $_SESSION['user_id'];

    $insert_order_query = "INSERT INTO orders1 (user_id, order_date, total_amount, status, phone, session_id, shipping_address) VALUES ('$user_id', '$order_date', 0, '$order_status', '$phone', '$session_id', '$address')";
    if ($result = mysqli_query($conn, $insert_order_query)) {
        $order_id = mysqli_insert_id($conn);

        $query_cart = "SELECT * FROM cart1 WHERE session_id='$session_id'";
        $result_cart = mysqli_query($conn, $query_cart);

        if (mysqli_num_rows($result_cart) > 0) {
            $total_amount = 0;
            while ($rpow = mysqli_fetch_assoc($result_cart)) {
                $product_id = $row['product_id'];
                $product_price = $row['price'];
                $product_quantity = $row['quantity'];
                $total_amount += $product_price * $product_quantity;

                $insert_order_item_query = "INSERT INTO orders1_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$product_quantity', '$product_price')";
                mysqli_query($conn, $insert_order_item_query);
            }

            $update_order_query = "UPDATE orders1 SET total_amount='$total_amount' WHERE order_id='$order_id'";
            mysqli_query($conn, $update_order_query);

            $clear_cart_query = "DELETE FROM cart1 WHERE session_id='$session_id'";
            mysqli_query($conn, $clear_cart_query);

            echo '<script>alert("تم اتمام الطلب بنجاح")</script>';
        }
    } else {
        die('Error: ' . mysqli_error($conn));
    }
}
?>

<main>
    <h1>إتمام الطلب</h1>
    <form action="checkout2.php" method="post">
        <label for="address">العنوان:</label>
        <input type="text" id="address" name="address" required>
        <br>
        <label for="phone">رقم الهاتف:</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        <label for="payment_method">طريقة الدفع:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="cash">نقداً</option>
            <option value="credit_card">بطاقة ائتمان</option>
        </select>
        <br>
        <button type="submit" name="complete_order">إتمام الطلب</button>
    </form>
</main>

//<?php include("file/footer.php"); ?>