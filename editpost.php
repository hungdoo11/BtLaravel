<?php
include 'config.php'; // Kết nối database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy bài viết chính
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    // Lấy danh sách hình ảnh + nội dung liên quan
    $queryDetails = "SELECT * FROM post_details WHERE post_id = ?";
    $stmtDetails = $conn->prepare($queryDetails);
    $stmtDetails->bind_param("i", $id);
    $stmtDetails->execute();
    $resultDetails = $stmtDetails->get_result();
    $postDetails = $resultDetails->fetch_all(MYSQLI_ASSOC);
}

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newTitle = $_POST['main_title']; // Tiêu đề mới
    $titles = $_POST['title'] ?? [];
    $contents = $_POST['content'] ?? [];
    $existingImages = $_POST['existing_images'] ?? [];
    $existingIds = $_POST['existing_ids'] ?? [];
    $deleteIds = $_POST['delete'] ?? [];

    // Xóa nhóm được chọn
    if (!empty($deleteIds)) {
        $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
        $deleteQuery = "DELETE FROM post_details WHERE id IN ($placeholders)";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param(str_repeat('i', count($deleteIds)), ...$deleteIds);
        $stmtDelete->execute();
    }

    // Cập nhật tiêu đề bài viết chính
    if (!empty($newTitle)) {
        $updatePostQuery = "UPDATE posts SET title = ? WHERE id = ?";
        $stmt = $conn->prepare($updatePostQuery);
        $stmt->bind_param("si", $newTitle, $id);
        $stmt->execute();
    }

    // Cập nhật hoặc thêm nhóm mới
    for ($i = 0; $i < count($titles); $i++) {
        $title = trim($titles[$i]);
        $content = trim($contents[$i]);
        $image = $existingImages[$i] ?? '';
        $detailId = $existingIds[$i] ?? 0;

        if (!empty($_FILES['images']['name'][$i])) {
            $targetDir = "uploads/";
            $image = basename($_FILES["images"]["name"][$i]);
            $targetFilePath = $targetDir . $image;
            move_uploaded_file($_FILES["images"]["tmp_name"][$i], $targetFilePath);
        }

        if ($detailId > 0 && !in_array($detailId, $deleteIds)) {
            // UPDATE
            $updateQuery = "UPDATE post_details SET title = ?, image = ?, content = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("sssi", $title, $image, $content, $detailId);
            $stmtUpdate->execute();
        } else if ($detailId == 0) {
            // INSERT
            $insertQuery = "INSERT INTO post_details (post_id, title, image, content) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($insertQuery);
            $stmtInsert->bind_param("isss", $id, $title, $image, $content);
            $stmtInsert->execute();
        }
    }
    echo "<script>alert('Cập nhật thành công!'); window.location.href='ADM.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài viết</title>
    <script>
        function addContentField() {
            let container = document.getElementById("contentContainer");
            let div = document.createElement("div");
            div.classList.add("content-group");
            div.innerHTML = `
                <input type="hidden" name="existing_ids[]" value="0">
                <label>Tiêu đề:</label><br>
                <input type="text" name="title[]"><br><br>
                <label>Hình ảnh:</label><br>
                <input type="file" name="images[]"><br>
                <label>Nội dung:</label><br>
                <textarea name="content[]" rows="3"></textarea><br><br>
                <button type="button" onclick="removeContentField(this)">Xóa</button><br><br>
            `;
            container.appendChild(div);
        }

        function removeContentField(button) {
            button.parentElement.remove();
        }
    </script>
</head>

<body>
    <h2>Chỉnh sửa bài viết</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Tiêu đề bài viết chính:</label><br>
        <input type="text" name="main_title" value="<?php echo htmlspecialchars($post['title']); ?>"><br><br>
        <div id="contentContainer">
            <?php foreach ($postDetails as $detail): ?>
                <div class="content-group">
                    <input type="hidden" name="existing_ids[]" value="<?php echo $detail['id']; ?>">
                    <label>Tiêu đề:</label><br>
                    <input type="text" name="title[]" value="<?php echo htmlspecialchars($detail['title']); ?>"><br><br>
                    <label>Hình ảnh:</label><br>
                    <?php if (!empty($detail['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($detail['image']); ?>" width="150"><br>
                        <input type="hidden" name="existing_images[]" value="<?php echo htmlspecialchars($detail['image']); ?>">
                    <?php endif; ?>
                    <input type="file" name="images[]"><br>
                    <label>Nội dung:</label><br>
                    <textarea name="content[]" rows="3"><?php echo htmlspecialchars($detail['content']); ?></textarea><br><br>
                    <label>
                        <input type="checkbox" name="delete[]" value="<?php echo $detail['id']; ?>"> Xóa nhóm này
                    </label><br><br>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" onclick="addContentField()">Thêm nhóm mới</button><br><br>
        <button type="submit">Lưu thay đổi</button>
    </form>
</body>

</html>