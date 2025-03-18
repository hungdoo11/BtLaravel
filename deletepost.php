<?php
$conn = new mysqli("localhost", "root", "", "travel_new");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Xóa bài viết thành công!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi xóa!"]);
    }
    $stmt->close();
}
$conn->close();
