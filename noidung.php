<?php
include 'config.php'; // File kết nối database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy dữ liệu bài viết
    $query = "SELECT * FROM post_details WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div style="max-width: 800px; margin: auto;">'; // Giới hạn chiều rộng hiển thị
        while ($row = $result->fetch_assoc()) {
            // Hiển thị tiêu đề trước
            // Hiển thị banner cố định (có thể thay đổi thành lấy từ database nếu cần)
            // Hiển thị banner cố định (có thể thay đổi thành lấy từ database nếu cần)
            //             echo '<img src="uploads/banner.jpg" 
            // style="width:100%; height:auto; display:block; margin-bottom: 20px;">';

            echo '<h2 style="color: blue; font-size: 28px; font-weight: bold; text-align: center; margin-top: 20px;">'
                . htmlspecialchars($row['title']) . '</h2>';



            // Hiển thị hình ảnh (nếu có)
            if (!empty($row['image'])) {
                echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" 
                      style="width:100%; max-width: 850px; display: block; margin: 10px auto; border-radius: 8px;">';
            }

            // Hiển thị nội dung bài viết
            echo '<p style="text-align: justify; line-height: 1.6; font-size: 18px; margin-top: 15px;">'
                . nl2br($row['content']) . '</p>';
        }
        echo '</div>';
    } else {
        echo "<p style='text-align: center; font-size: 18px;'>Không tìm thấy bài viết.</p>";
    }
} else {
    echo "<p style='text-align: center; font-size: 18px;'>Thiếu ID bài viết.</p>";
}
