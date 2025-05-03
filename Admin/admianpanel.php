<?php
include "../include/connection.php";
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <?php
    if(!isset($_SESSION['EMAIL'])){
        header('location:../index.php');
    } else {
    ?> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>لوحة تحكم الإدارة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --sidebar-width: 250px;
            --sidebar-bg: #2c3e50;
            --content-bg: #fff;
            --stat-box-bg: #f8f9fa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }
        
        .admin-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 100%;
            background: var(--sidebar-bg);
            color: white;
            position: relative;
            z-index: 1000;
        }
        
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        
        .sidebar h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        
        .menu-toggle {
            display: block;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .sidebar-nav {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .sidebar-nav.active {
            max-height: 1000px;
        }
        
        .sidebar ul {
            list-style: none;
        }
        
        .sidebar li a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            border-right: 4px solid transparent;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .sidebar li a:hover {
            background: #34495e;
            border-right: 4px solid var(--primary-color);
        }
        
        .sidebar li a i {
            margin-left: 10px;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            background: var(--content-bg);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .stat-box {
            background: var(--stat-box-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-box i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-box h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 1rem;
        }
        
        .stat-box p {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .welcome-message {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .quick-actions {
            margin-top: 30px;
        }
        
        .quick-actions h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
        }
        
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .action-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        /* For tablets and larger */
        @media (min-width: 768px) {
            .admin-container {
                flex-direction: row;
            }
            
            .sidebar {
                width: var(--sidebar-width);
                height: 100vh;
                position: fixed;
                padding: 20px 0;
            }
            
            .sidebar-header {
                display: block;
                padding: 10px;
                text-align: center;
            }
            
            .menu-toggle {
                display: none;
            }
            
            .sidebar-nav {
                max-height: none;
            }
            
            .main-content {
                margin-right: var(--sidebar-width);
                padding: 30px;
            }
        }
        
        /* For very small screens */
        @media (max-width: 360px) {
            .stats-container,
            .action-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-box {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- القائمة الجانبية -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>لوحة التحكم</h1>
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="sidebar-nav" id="sidebarNav">
                <ul>
                    <li><a href="../index.php" target="_blank"><i class="fa-solid fa-house-chimney"></i> الرئيسية</a></li>
                    <li><a href="admin_orders1.php"><i class="fa-solid fa-folder-open"></i> صفحة الطلبات</a></li>
                    <li><a href="#"><i class="fa-solid fa-users"></i> معلومات المستخدمين</a></li>
                    <li><a href="product.php"><i class="fa-solid fa-gift"></i> صفحة المنتجات</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> إعدادات المتجر</a></li>
                    <li><a href="addproduct.php"><i class="fa-solid fa-plus"></i> اضافة منتج</a></li>
                    <li><a href="logout.php"><i class="fa-solid fa-share-from-square"></i> خروج من النظام</a></li>
                </ul>
            </nav>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <div class="welcome-message">
                <h2>مرحبا بك في لوحة التحكم</h2>
                <p>هنا يمكنك إدارة جميع جوانب متجرك الإلكتروني</p>
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>الطلبات اليوم</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM orders1 WHERE DATE(order_date) = CURDATE()";
                        $result = mysqli_query($conn, $query);
                        echo mysqli_fetch_row($result)[0];
                        ?>
                    </p>
                </div>

                <div class="stat-box">
                    <i class="fa-solid fa-box-open"></i>
                    <h3>المنتجات</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM products";
                        $result = mysqli_query($conn, $query);
                        echo mysqli_fetch_row($result)[0];
                        ?>
                    </p>
                </div>

                <div class="stat-box">
                    <i class="fa-solid fa-users"></i>
                    <h3>العملاء</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM users";
                        $result = mysqli_query($conn, $query);
                        echo mysqli_fetch_row($result)[0];
                        ?>
                    </p>
                </div>
            </div>

            <div class="quick-actions">
                <h3>إجراءات سريعة</h3>
                <div class="action-grid">
                    <a href="addproduct.php" class="action-card">
                        <i class="fa-solid fa-plus-circle"></i>
                        <h4>إضافة منتج جديد</h4>
                    </a>
                    <a href="admin_orders1.php" class="action-card">
                        <i class="fa-solid fa-list-check"></i>
                        <h4>عرض الطلبات</h4>
                    </a>
                    <a href="product.php" class="action-card">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <h4>تعديل المنتجات</h4>
                    </a>
                    <a href="settings.php" class="action-card">
                        <i class="fa-solid fa-paintbrush"></i>
                        <h4>تعديل التصميم</h4>


                        <a href="delete_products.php" class="action-card">
                        <i class="fas fa-trash-alt"></i>
                        <h4> تفريغ المتجر</del></h4>
                    </a>




                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle menu for mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebarNav').classList.toggle('active');
        });
        
        // Close menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                event.target !== menuToggle) {
                document.getElementById('sidebarNav').classList.remove('active');
            }
        });
    </script>
    <?php } ?>
</body>
</html>