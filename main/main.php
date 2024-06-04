<?php
include './ConnectDb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt Đồ Ăn</title>
  <link rel="stylesheet" href="./main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">
    <div>
        <a href="../index.html" class="active">
                  <i class="fa-solid fa-right-from-bracket"></i>
                  <span>Đăng xuất</span>
        </a>
    </div>

    <h1>Đặt Đồ Ăn</h1>
    <div class="menu">
      <div class="product-container">
        <?php foreach ($products as $product): ?>
          <div class="menu-item">
            <img src="../asset/image/food<?php echo $product['ID']; ?>.jpg">
            <h3><?php echo $product['nameProducts']; ?></h3>
            <p>Số lượng: <?php echo $product['Quantity']; ?></p>
            <span class="price">$<?php echo $product['Price']; ?></span>
            <button <?php echo "onclick='addToCartClicked(" . $product["ID"] . ")'" ?> class="add-to-cart">Thêm vào giỏ hàng</button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="pagination">
      <button class="prev-page">Trước</button>
      <span style="margin: 10px" class="current-page">1</span>
      <button class="next-page">Sau</button>
    </div>
    <main class="main-dashboard">
      <main class="page">
        <div class="box-dashboard">
          <table class="table caption-top">
            <caption>Danh sách sản phẩm</caption>
            <thead>
              <tr>
                <th scope="col" onclick="sortTable(0, true, true)">ID &#x2191;</th>
                <th scope="col">Hình ảnh</th>
                <th scope="col" onclick="sortTable(2, false, true)">Tên Sản Phẩm &#x2191;</th>
                <th scope="col" onclick="sortTable(3, true, true)">Giá tiền &#x2191;</th>
                <th scope="col" onclick="sortTable(4, true, true)">Số lượng &#x2191;</th>
                <th scope="col">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result2->num_rows > 0) {
                  while ($row = $result2->fetch_assoc()) {
                      $imagePath = "../asset/image/Food" . $row["ID"] . ".jpg";
                      echo "<tr class='tr-hover'>";
                      echo "<th scope='row'>" . $row["ID"] . "</th>";
                      echo "<td><img style='object-fit: cover;' src='" . $imagePath . "' alt='product image' width='70' height='70'></td>";
                      echo "<td>" . $row["nameProducts"] . "</td>";
                      echo "<td>" . $row["Price"] . "$</td>";
                      echo "<td>" . $row["Quantity"] ."</td>";
                      echo "<td>
                              <button class='btnDelete' onclick='deleteProduct(" . $row["ID"] . ")'>Xóa</button>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='6'>Không có dữ liệu</td></tr>";
              }
              ?>
            </tbody>
          </table>
          <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i' class='" . ($i == $page ? "active" : "") . "'>$i</a>";
            }
            ?>
          </div>
        </div>

        <div class="box-dashboard">
          <h1 class="caption-pay">Thanh toán</h1>
          <div class="box-dashboard-row"> 
            <p class="title-row">Tổng tiền</p>   
            <?php echo "$" . number_format($totalPrice, 2); ?>
          </div>
          <div class="box-dashboard-row"></div>
          <div class="box-dashboard-row"></div>
          <button onclick='confirmPayment()' id="openModalBtn" class="payBtn">Thanh toán</button>
        </div>
      </main>
    </main>

        <!-- modal xóa 1 sản phẩm -->
        <div id="confirmModal" class="modal">
      <div class="modal-content">
        <p>Bạn có chắc chắn muốn xóa sản phẩm này không?</p>
        <div class="modal-buttons">
          <button id="confirmYes">Có</button>
          <button id="confirmNo">Không</button>
        </div>
      </div>
    </div>
    
  </div>
  <script src="./main.js"></script>
  <script>
  function addToCartClicked(productId) {
    $.ajax({
      url: "./ConnectDb.php",
      type: "POST",
      data: { productId: productId },
      success: function () {
        console.log("success");
        alert('Thêm vào giỏ hàng thành công');
      },
      error: function (xhr, status, error) {
        console.log("error");
        console.log("Product ID:", productId);
      },
    });
  }

  // xóa 1 sản phẩm
    function deleteProduct(productId) {
      var modal = document.getElementById("confirmModal");
      var btnYes = document.getElementById("confirmYes");
      var btnNo = document.getElementById("confirmNo");

      // Mở modal
      modal.style.display = "block";

      // Xử lý khi nhấn Có
      btnYes.onclick = function () {
        // Gửi yêu cầu xóa bằng AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./ConnectDb.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
          if (xhr.status === 200) {
            // Tải lại trang
            window.location.reload();
          } else {
            alert("Có lỗi xảy ra khi xóa sản phẩm.");
          }
        };
        xhr.send("deleteProduct=true&productId=" + productId);

        // Đóng modal
        modal.style.display = "none";
      };

      // Xử lý khi nhấn Không
      btnNo.onclick = function () {
        // Đóng modal

        modal.style.display = "none";
      };
    }

    function confirmPayment() {
    if (confirm('Bạn có chắc chắn muốn thanh toán không?')) {
        // Gửi yêu cầu xóa tất cả dữ liệu bằng AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Hiển thị thông báo thanh toán thành công
                alert('Thanh toán thành công!');
                // Tải lại trang để cập nhật giao diện
                window.location.reload();
            } else {
                // Hiển thị thông báo lỗi
                alert('Có lỗi xảy ra khi thanh toán.');
            }
        };
        xhr.send('deleteAll=true');
    }
}
  </script>
</body>
</html>
