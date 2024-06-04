<?php
// Kết nối đến cơ sở dữ liệu
include './Config.php';

// Lấy dữ liệu từ form đăng ký
$username = $_POST['username'];
$password = $_POST['password'];

// // Mã hóa mật khẩu
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Thêm dữ liệu vào cơ sở dữ liệu
$sql = "INSERT INTO account (Username, Pass) VALUES ('$username', '$password')";
if ($conn->query($sql) === TRUE) {
    // Đăng ký thành công, chuyển hướng về trang đăng nhập và hiển thị thông báo
    echo "<script>alert('Registered successfully'); window.location='./index.html';</script>";
} else {
    // Nếu có lỗi khi thêm dữ liệu, hiển thị thông báo lỗi
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
