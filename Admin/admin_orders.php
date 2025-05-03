<?php
session_start();

// التحقق من أن المستخدم مسجل دخوله وهو مدير
if (!isset($_SESSION['EMAIL']) ){
    header("Location: admin_login.php");
    exit();
}

include("../include/connection.php");
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>إدارة الطلبات - لوحة التحكم</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
            border-left: 4px solid #3498db;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .orders-table th {
            background-color: #3498db;
            color: white;
        }
        .orders-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-completed {
            color: #27ae60;
            font-weight: bold;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 3px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .view-btn {
            background: #3498db;
            color: white;
        }
        .edit-btn {
            background: #f39c12;
            color: white;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
        }
        .page-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- القائمة الجانبية -->
        <div class="sidebar">
            <h3 style="text-align: center; padding: 10px;">لوحة التحكم</h3>
            <a href="admianpanel.php">الرئيسية</a>
            <a href="product.php">إدارة المنتجات</a>
            <a href="admin_orders.php" style="background: #34495e; border-left: 4px solid #3498db;">إدارة الطلبات</a>
            <a href="admin_users.php">إدارة العملاء</a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <h2 class="page-title">إدارة الطلبات</h2>
            
            <!-- فلترة الطلبات -->
            <div class="filters">
                <form method="get">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">كل الطلبات</option>
                        <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>>قيد المعالجة</option>
                        <option value="completed" <?= isset($_GET['status']) && $_GET['status'] == 'completed' ? 'selected' : '' ?>>مكتملة</option>
                    </select>
                </form>
            </div>
            
            <!-- جدول الطلبات -->
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>تاريخ الطلب</th>
                        <th>العميل</th>
                        <th>المبلغ الإجمالي</th>
                        <th>حالة الطلب</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // بناء استعلام الطلبات مع الفلترة
                    $query = "SELECT * FROM orders1";
                    if (isset($_GET['status']) && !empty($_GET['status'])) {
                        $status = mysqli_real_escape_string($conn, $_GET['status']);
                        $query .= " WHERE status = '$status'";
                    }
                    $query .= " ORDER BY order_date DESC";
                    
                    $result = mysqli_query($conn, $query);
                    
                    while ($order = mysqli_fetch_assoc($result)) {
                        $status_class = $order['status'] == 'completed' ? 'status-completed' : 'status-pending';
                        echo "<tr>";
                        echo "<td>#" . $order['order_id'] . "</td>";
                        echo "<td>" . date('Y-m-d H:i', strtotime($order['order_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($order['shipping_address']) . "<br>" . $order['phone'] . "</td>";
                        echo "<td>" . number_format($order['total_amount'], 2) . " د.ل</td>";
                        echo "<td class='$status_class'>" . $order['status'] . "</td>";
                        echo "<td>
                                <a href='admin_order_details.php?id=" . $order['order_id'] . "' class='action-btn view-btn'>عرض</a>
                                <a href='admin_edit_order.php?id=" . $order['order_id'] . "' class='action-btn edit-btn'>تعديل</a>
                              </td>";
                        echo "</tr>";
                    }
                    
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='6' style='text-align:center;'>لا توجد طلبات</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>