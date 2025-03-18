<?php
session_start(); // Bắt đầu session
session_unset(); // Xóa tất cả biến session
session_destroy(); // Hủy session

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_new";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nếu chưa có session locations thì truy vấn và lưu vào session
if (!isset($_SESSION['locations'])) {
    $result_locations = $conn->query("SELECT id, name FROM locations");
    $locations = [];

    if ($result_locations) {
        while ($row = $result_locations->fetch_assoc()) {
            $locations[] = $row; // Lưu danh sách locations vào mảng
        }
    }
    $_SESSION['locations'] = $locations; // Lưu vào session để sử dụng lại
}

// Lấy dữ liệu từ form
$title = trim($_POST['title'] ?? ''); // Lấy tiêu đề bài viết từ form, nếu không có thì để chuỗi rỗng
$location_id = intval($_POST['location'] ?? 0); // Chuyển đổi location_id sang kiểu số nguyên
$image_path = ''; // Đường dẫn ảnh mặc định

// Xử lý upload ảnh nếu có
if (!empty($_FILES['image']['name'])) {
    $target_dir = "uploads/"; // Thư mục lưu ảnh
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
    }

    $image_name = time() . "_" . basename($_FILES["image"]["name"]); // Đặt tên ảnh theo timestamp
    $target_file = $target_dir . $image_name; // Đường dẫn lưu ảnh

    $valid_types = ['jpg', 'jpeg', 'png', 'gif']; // Các định dạng ảnh hợp lệ
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Lấy phần mở rộng của file

    // Kiểm tra định dạng ảnh và dung lượng (tối đa 5MB)
    if (in_array($image_file_type, $valid_types) && $_FILES["image"]["size"] <= 5 * 1024 * 1024) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file; // Lưu đường dẫn ảnh nếu upload thành công
        } else {
            die("Lỗi tải ảnh lên."); // Thông báo lỗi nếu upload thất bại
        }
    } else {
        die("Ảnh không hợp lệ."); // Thông báo lỗi nếu file không đúng định dạng hoặc quá lớn
    }
}

// Kiểm tra dữ liệu hợp lệ trước khi thêm vào database
if (!empty($title) && $location_id > 0) {
    // Chuẩn bị câu lệnh SQL để tránh lỗi SQL Injection
    $stmt = $conn->prepare("INSERT INTO posts (title, location_id, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $title, $location_id, $image_path);

    // Thực thi truy vấn
    if ($stmt->execute()) {
        echo "✅ Thêm bài viết thành công!";
    } else {
        echo "❌ Lỗi: " . $stmt->error; // Hiển thị lỗi nếu có
    }
    $stmt->close(); // Đóng statement
} else {
    echo "❌ Vui lòng nhập đủ thông tin."; // Báo lỗi nếu thiếu dữ liệu
}

$conn->close(); // Đóng kết nối database

// ========================== PHẦN MÃ CŨ (COMMENT LẠI) ==========================

// Kiểm tra nếu request là POST
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $title = $_POST['title'] ?? '';
//     $content = $_POST['content'] ?? '';
//     $user_id = $_POST['user_id'] ?? 0;
//     $location_id = $_POST['location_id'] ?? 0;

//     if (!empty($title) && !empty($content) && $location_id > 0) {
//         $stmt = $conn->prepare("INSERT INTO posts (title, content, users_id, location_id, created_at) VALUES (?, ?, ?, ?, NOW())");
//         $stmt->bind_param("ssii", $title, $content, $user_id, $location_id);

//         if ($stmt->execute()) {
//             echo "Thêm bài viết thành công!";
//         } else {
//             echo "Lỗi khi thêm bài viết!";
//         }
//         $stmt->close();
//     } else {
//         echo "Vui lòng nhập đầy đủ thông tin.";
//     }
// }

// $conn->close();
