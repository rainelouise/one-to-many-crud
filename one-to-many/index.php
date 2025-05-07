<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'one_to_many');

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $user = $_SESSION['user_id'];
    $conn->query("INSERT INTO products (name, description, created_by, updated_by) VALUES ('$name', '$desc', $user, $user)");
}

if (isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $user = $_SESSION['user_id'];
    $conn->query("UPDATE products SET name='$name', description='$desc', updated_by=$user WHERE id=$id");
}

if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

if (isset($_POST['add_review'])) {
    $product_id = $_POST['product_id'];
    $text = $_POST['review_text'];
    $user = $_SESSION['user_id'];
    $conn->query("INSERT INTO reviews (product_id, review_text, created_by, updated_by) VALUES ($product_id, '$text', $user, $user)");
}

if (isset($_POST['edit_review'])) {
    $review_id = $_POST['review_id'];
    $text = $_POST['review_text'];
    $user = $_SESSION['user_id'];
    $conn->query("UPDATE reviews SET review_text='$text', updated_by=$user WHERE id=$review_id");
}

if (isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    $conn->query("DELETE FROM reviews WHERE id=$review_id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
    body {
        font-family: 'Verdana', sans-serif;
        background: #f5f5f5;
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        color: #333;
    }

    h2, h3, h4 {
        margin-top: 20px;
    }

    a {
        color: #FF8FAB;
        text-decoration: none;
    }

    a:hover {
        color: #FB6F92;
        text-decoration: underline;
    }

    input[type="text"], textarea {
        width: 100%; 
        padding: 10px;
        margin: 6px 0 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box; 
    }
    
    textarea {
        resize: vertical; 
        height: 150px; 
    }

    button {
        background: #FF8FAB;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background: #FB6F92;
    }

    .product {
        background: #fff;
        padding: 16px;
        margin: 16px 0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .review-list {
        list-style: none;
        padding-left: 0;
        margin-top: 10px;
    }

    .review-list li {
        margin-bottom: 12px;
        background: #f0f0f0;
        padding: 10px;
        border-radius: 6px;
    }

    .inline-form {
        display: inline-block;
        margin-right: 10px;
    }

    small {
        color: #666;
        font-size: 12px;
    }

    hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 30px 0;
    }
</style>
</head>
<body>
<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="?logout=true">Logout</a></h2>

<h3>Add Product</h3>
<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="add_product">Add</button>
</form>

<hr>

<h2>All Products and Reviews</h2>
<?php
$products = $conn->query("SELECT * FROM products");

while ($product = $products->fetch_assoc()) {
    echo "<div class='product'>";
    echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
    echo "<p>" . htmlspecialchars($product['description']) . "</p>";

    echo "<form method='POST' class='inline-form'>
        <input type='hidden' name='product_id' value='{$product['id']}'>
        <input type='text' name='name' value='" . htmlspecialchars($product['name']) . "' required>
        <input type='text' name='description' value='" . htmlspecialchars($product['description']) . "'>
        <button type='submit' name='edit_product'>Edit</button>
    </form>";

    echo "<form method='POST' class='inline-form'>
        <input type='hidden' name='product_id' value='{$product['id']}'>
        <button type='submit' name='delete_product' onclick='return confirm(\"Delete this product?\")'>Delete</button>
    </form>";

    echo "<h4>Add Review</h4>
    <form method='POST'>
        <input type='hidden' name='product_id' value='{$product['id']}'>
        <textarea name='review_text' placeholder='Write your review' required></textarea>
        <button type='submit' name='add_review'>Submit</button>
    </form>";

    echo "<h4>Reviews</h4><ul class='review-list'>";
    $reviews = $conn->query("
        SELECT r.*, 
               uc.username AS created_by_name, 
               uu.username AS updated_by_name
        FROM reviews r
        LEFT JOIN users uc ON r.created_by = uc.id
        LEFT JOIN users uu ON r.updated_by = uu.id
        WHERE r.product_id = {$product['id']}
    ");
    while ($review = $reviews->fetch_assoc()) {
        $createdBy = $review['created_by_name'] ?? 'Unknown';
        $updatedBy = $review['updated_by_name'] ?? 'Unknown';

        echo "<li>
            <strong>" . htmlspecialchars($review['review_text']) . "</strong><br>
            <small>Created by: " . htmlspecialchars($createdBy) . 
            ", Last updated by: " . htmlspecialchars($updatedBy) . "</small><br>
            <form method='POST' class='inline-form'>
                <input type='hidden' name='review_id' value='{$review['id']}'>
                <input type='text' name='review_text' value='" . htmlspecialchars($review['review_text']) . "'>
                <button name='edit_review'>Edit</button>
            </form>
            <form method='POST' class='inline-form'>
                <input type='hidden' name='review_id' value='{$review['id']}'>
                <button name='delete_review' onclick='return confirm(\"Delete this review?\")'>Delete</button>
            </form>
        </li>";
    }
    echo "</ul></div>";
}
?>

</body>
</html>