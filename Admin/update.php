<?php
// Include the connection file if needed
 include("../include/connection.php");

// Check if ID is set in the GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL query to fetch the product details
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (isset($_POST['update_pro'])) {
        $proname = $_POST['name'];
        $proprice = $_POST['price'];
        $prosection = $_POST['prosection'];
        $prodescrip = $_POST['description'];
        $prosize = $_POST['prosize'];
        $prounv = $_POST['prounv'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];

        if (empty($prodescrip)) {
            echo '<script>alert("PLEASE ENTER DETAILS OF PRODUCT");</script>';
        } else {
            if (!empty($imageName)) {
                $proimg = rand(0, 5000) . "_" . $imageName;
                move_uploaded_file($imageTmp, "../uploads/img/" . $proimg);
            } else {
                $proimg = $row['image'];
            }

            // Prepare the SQL query to update the product details
            $query = "UPDATE products SET 
                name = ?, 
                price = ?, 
                prosection = ?, 
                description = ?, 
                prosize = ?, 
                image = ?, 
                prounv = ?, 
                quantity = ?
                WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssii", $proname, $proprice, $prosection, $prodescrip, $prosize, $proimg, $prounv, $quantity, $id);
            $result = $stmt->execute();
            if (!$result) {
                echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
                die();
            }

            if ($result) {
                echo '<script>alert(" تم التعديل بنجاح");</script>';
                header("Refresh:1; url=admianpanel.php");
            } else {
                echo '<script>alert(" فشلت عمليه التعديل");</script>';
            }
        }
    }
} else {
    echo "ID not set";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPDATE PRODUCTS</title>
</head>
<body>
    <center>
        <main>
            <div class="form_product">
                <h1>UPDATE PRODUCT</h1>
                <form action="update.php?id=<?php echo $row['id']; ?>" method="POST" enctype="multipart/form-data">
                    <label for="name">Address of Product</label>
                    <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>">

                    <label for="file">Image of Product</label>
                    <input type="file" name="image" id="file">

                    <label for="price">Price of Product</label>
                    <input type="number" name="price" id="price" value="<?php echo $row['price']; ?>">

                    <label for="description">Description of Product</label>
                    <input type="text" name="description" id="description" value="<?php echo $row['description']; ?>">

                    <label for="prosize">Size of Product</label>
                    <input type="text" name="prosize" id="prosize" value="<?php echo $row['prosize']; ?>">

                    <label for="quantity">Quantity of Product</label>
                    <input type="number" name="quantity" id="quantity" value="<?php echo $row['quantity']; ?>">

                    <label for="prounv">Availability of Product</label>
                    <input type="text" name="prounv" id="prounv" value="<?php echo $row['prounv']; ?>">

                    <div>
                        <label for="prosection">Section of Product</label>
                        <select name="prosection" id="prosection">
                            <?php
                            $query = "SELECT * FROM section";
                            $result = mysqli_query($conn, $query);
                            while ($section = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $section['section_name'] . '">' . $section['section_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div><br><br>

                    <input class="button" type="submit" name="update_pro" value="UPDATE">
                </form>
            </div>
        </main>
    </center>
</body>
</html>