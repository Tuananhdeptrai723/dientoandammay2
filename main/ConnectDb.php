<?php
session_start();
ob_start();

// Kết nối đến CSDL
include '../config.php';

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM cart LIMIT 15";
$result = $conn->query($sql);

// Tạo mảng chứa dữ liệu sản phẩm
$products = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    $sql = "SELECT nameProducts, Price FROM cart WHERE ID = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productName = $row['nameProducts'];
        $productPrice = $row['Price'];
        $quantity = 1; // Số lượng mặc định khi thêm vào giỏ hàng

        // Kiểm tra sản phẩm đã có trong giỏ hàng hay chưa
        $sql = "SELECT * FROM ordercart WHERE ID = '$productId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
            $sql = "UPDATE ordercart SET Quantity = Quantity + 1 WHERE ID = '$productId'";
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            $sql = "INSERT INTO ordercart (nameProducts, ID, Price, Quantity) VALUES ('$productName', '$productId', '$productPrice', '$quantity')";
        }

        if ($conn->query($sql) === TRUE) {
            
            echo "<script>window.location.reload();</script> Add to cart success!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Product not found!";
    }
}

// Xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteProduct']) && $_POST['deleteProduct'] == true) {
    $id = $_POST['productId'];
    $sql3 = "DELETE FROM ordercart WHERE ID = '$id'";
    if ($conn->query($sql3) === TRUE) {
        http_response_code(200);
        echo "Delete success!";
    } else {
        http_response_code(500);
        echo "Error deleting record: " . $conn->error;
    }
}

// thanh toán 
// Xử lý yêu cầu POST để xóa toàn bộ dữ liệu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteAll']) && $_POST['deleteAll'] == true) {
    $sql = "DELETE FROM ordercart";
    if ($conn->query($sql) === TRUE) {
        // Nếu xóa thành công, trả về mã 200 (OK)
        http_response_code(200);
    } else {
        // Nếu có lỗi, trả về mã lỗi 500 (Internal Server Error)
        http_response_code(500);
    }
  }

// Truy vấn dữ liệu từ bảng ordercart cho phần quản lý sản phẩm
$items_per_page = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Truy vấn tổng số sản phẩm
$total_sql = "SELECT COUNT(*) as total FROM ordercart";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Truy vấn tổng tiền
$totalPriceQuery = "SELECT SUM(Price * Quantity) AS total FROM ordercart";
$totalPriceResult = $conn->query($totalPriceQuery);
$totalPriceRow = $totalPriceResult->fetch_assoc();
$totalPrice = $totalPriceRow['total'];

// Truy vấn dữ liệu từ bảng ordercart
$sql = "SELECT ID, nameProducts, Price, Quantity FROM ordercart LIMIT $offset, $items_per_page";
$result2 = $conn->query($sql);

$conn->close();
?>
