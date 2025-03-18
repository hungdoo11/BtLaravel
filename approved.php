<?php
// Kết nối tới cơ sở dữ liệu MySQL
$conn = new mysqli("localhost", "root", "", "travel_new");
$conn->set_charset("utf8"); // Thiết lập bộ mã UTF-8 để hỗ trợ tiếng Việt

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy bài viết mới nhất đã được duyệt
$sql = "SELECT * FROM posts WHERE status = 'approved' ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

// Nếu có bài viết, hiển thị tiêu đề và nội dung
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h3>" . $row['title'] . "</h3><p>" . $row['content'] . "</p>";
}

// Xử lý duyệt bài viết khi có yêu cầu từ form POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id']; // Ép kiểu ID bài viết thành số nguyên để tránh lỗi SQL Injection
    $sql = "UPDATE posts SET status = 'approved' WHERE id = $post_id"; // Cập nhật trạng thái bài viết thành "approved"

    // Thực thi truy vấn và kiểm tra kết quả
    if ($conn->query($sql) === TRUE) {
        echo "Bài viết đã được duyệt!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// API: Lấy thông tin bài viết dựa trên ID từ tham số GET
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']); // Chuyển đổi ID thành số nguyên để tránh lỗi bảo mật
    $sql = "SELECT title, content, created_at FROM posts WHERE id = $post_id AND status = 'approved'";
    $result = $conn->query($sql);

    // Nếu bài viết tồn tại, trả về JSON chứa dữ liệu bài viết
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "title" => $post['title'],
            "content" => $post['content'],
            "created_at" => $post['created_at']
        ]);
    } else {
        // Trả về JSON báo lỗi nếu bài viết không tồn tại
        echo json_encode(["success" => false, "message" => "Bài viết không tồn tại"]);
    }
} else {
    // Trả về JSON báo lỗi nếu không có ID bài viết
    echo json_encode(["success" => false, "message" => "Thiếu ID bài viết"]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();

// Điều hướng về trang Admin sau khi duyệt bài viết
header("Location: ADM.php");
exit;
