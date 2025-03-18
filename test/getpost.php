<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_new";

// Kết nối đến MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra xem có tham số 'id' được truyền qua URL không
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Chuyển đổi ID thành số nguyên để tránh lỗi bảo mật

    // Chuẩn bị truy vấn SQL để lấy bài viết theo ID (Sử dụng Prepared Statements để tránh SQL Injection)
    $query = $conn->prepare("SELECT title, created_at, content FROM posts WHERE id = ?");
    $query->bind_param("i", $id); // Gán giá trị ID vào câu lệnh SQL

    // Thực thi truy vấn
    $query->execute();

    // Liên kết kết quả vào các biến
    $query->bind_result($title, $created_at, $content);

    // Kiểm tra xem có dữ liệu trả về hay không
    if ($query->fetch()) {
        // Hiển thị tiêu đề bài viết
        echo "<h1>" . htmlspecialchars($title) . "</h1>";

        // Hiển thị ngày đăng, đảm bảo dữ liệu an toàn với `htmlspecialchars`
        echo "<p class='meta'>Ngày đăng: " . htmlspecialchars($created_at) . "</p>";

        // Hiển thị nội dung bài viết, dùng `nl2br` để giữ nguyên định dạng xuống dòng
        echo "<div class='content'>" . nl2br(htmlspecialchars($content)) . "</div>";
    } else {
        // Thông báo nếu không tìm thấy bài viết
        echo "<p>Không tìm thấy bài viết.</p>";
    }

    // Đóng câu lệnh truy vấn
    $query->close();
}

// Đóng kết nối CSDL
$conn->close();
