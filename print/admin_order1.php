<?php
session_start();
if (!isset($_SESSION['EMAIL'])) {
    header("Location: admin.php");
    exit();
}

require_once("../include/connection.php");

// جلب جميع الطلبات
$query = "SELECT * FROM orders1 ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>إدارة الطلبات</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Tahoma; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px 15px; text-align: right; border: 1px solid #ddd; }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .status { padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .pending { background: #fff3cd; color: #856404; }
        .processing { background: #cce5ff; color: #004085; }
        .completed { background: #d4edda; color: #155724; }
        .cancelled { background: #f8d7da; color: #721c24; }
        .shipped { background: #e2e3e5; color: #383d41; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-view { background: #17a2b8; color: white; }
        .btn-edit { background: #ffc107; color: black; }
        .btn-delete { background: #dc3545; color: white; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout { color: #dc3545; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>إدارة الطلبات</h1>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>التاريخ</th>
                    <th>العميل</th>
                    <th>المجموع</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['order_date'])) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= number_format($row['total_amount'], 2) ?> د.ل</td>
                        <td><span class="status <?= $row['status'] ?>">
                            <?php
                            switch($row['status']) {
                                case 'pending': echo 'قيد المعالجة'; break;
                                case 'processing': echo 'جار التجهيز'; break;
                                case 'completed': echo 'مكتمل'; break;
                                case 'cancelled': echo 'ملغي'; break;
                                case 'shipped': echo 'تم الشحن'; break;
                                default: echo $row['status'];
                            }
                            ?>
                        </span></td>
                        <td>
                            <a href="admin_view_order.php?id=<?= $row['order_id'] ?>" class="btn btn-view"><i class="fas fa-eye"></i> عرض</a>
                            <a href="admin_edit_order.php?id=<?= $row['order_id'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i> تعديل</a>
                            <a href="admin_delete_order.php?id=<?= $row['order_id'] ?>" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')"><i class="fas fa-trash"></i> حذف</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>