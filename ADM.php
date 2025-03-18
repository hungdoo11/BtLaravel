<?php
session_start();


// session_unset();
// session_destroy();
// echo '<pre>';
// print_r($_SESSION['location_id']);
// echo '</pre>';

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

// Kiểm tra nếu session chưa có dữ liệu, thì truy vấn database
if (!isset($_SESSION['locations'])) {
    $result_locations = $conn->query("SELECT * FROM locations");
    $locations = [];

    if ($result_locations) {
        while ($row = $result_locations->fetch_assoc()) {
            $locations[] = $row;
        }
    }

    // Lưu vào session
    $_SESSION['locations'] = $locations;
}



// Hiển thị danh sách locations
if (isset($_SESSION['locations'])) {
    foreach ($_SESSION['locations'] as $location) {
        echo "<option value='{$location['id']}'>{$location['name']}</option>";
    }
}

$query = "SELECT id, name FROM locations"; // Thay tên bảng locations theo thực tế
$results = $conn->query($query);
// Truy vấn danh sách bài viết
$sql = "SELECT id, title, created_at, status FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Tin Tức Du Lịch</title>
    <link rel="stylesheet" href="css/adm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>

                <li><a href="home.php" class="logout">🚪 Đăng xuất</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <h2>Thêm bài viết</h2>
            <form action="addpost.php" method="POST" enctype="multipart/form-data">
                <label for="image">Chọn Ảnh:</label>
                <input type="file" name="image" id="image" required>

                <label for="title">Tiêu đề:</label>
                <input type="text" name="title" id="title" required>

                <label for="location">Chọn Tỉnh Thành:</label>
                <select name="location" id="location" required>
                    <?php
                    session_start();
                    $locations = $_SESSION['locations'] ?? [];
                    foreach ($locations as $loc) {
                        echo "<option value='{$loc['id']}'>{$loc['name']}</option>";
                    }
                    ?>
                </select>

                <button type="submit">Thêm Bài Viết</button>
            </form>
            <header>
                <h1>Dashboard</h1>
                <!-- <button class="btn btn-primary">Thêm Bài Viết</button> -->

            </header>

            <!-- <section class="stats">
                <div class="stat-box">
                    <h3>👁️ Lượt Truy Cập</h3>
                    <p>120,345</p>
                </div>
                <div class="stat-box">
                    <h3>📰 Bài Viết</h3>
                    <p>58</p>
                </div>
                <div class="stat-box">
                    <h3>👤 Người Dùng</h3>
                    <p>1,200</p>
                </div>
            </section> -->

            <section class="news-management">
                <h2>Quản lý Tin Tức</h2>
                <table class="news-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Ngày đăng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['title'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <?php if ($row['status'] == 'approved') { ?>
                                        <span class="status approved">✔️ Đã duyệt</span>
                                    <?php } else { ?>
                                        <span class="status pending">⏳ Chờ duyệt</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 'pending') { ?>
                                        <form method="post" action="approved.php">
                                            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn approve">✔️ Duyệt</button>
                                        </form>
                                    <?php } else { ?>

                                    <?php } ?>
                                </td>
                                <td style="display: flex;">
                                    <a href="editpost.php?id=<?= $row['id'] ?>" class="btn edit">✏️ Sửa</a>
                                    <button class="btn delete-btn" data-id="<?= $row['id'] ?>">🗑️ Xóa</button>
                                </td>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </section>
        </main>
        <!-- Modal Thêm Bài Viết -->
        <!-- <div class="modal" id="addPostModal">
            <div class="modal-content">
                <h2>Thêm Bài Viết</h2>
                <form id="addPostForm">
                    <label for="title">Tiêu đề:</label>
                    <input type="text" id="title" name="title" required>

                    <label for="content">Nội dung:</label>
                    <textarea id="content" name="content" rows="5" required></textarea>
                    <label for="user_id">User ID:</label>
                    <input type="number" id="user_id" name="user_id" required><br>

                    <button type="submit" class=" btn-outline-primary">Đăng bài</button>
                    <button type="button" class="modal-btn btn-outline-secondary">Đóng</button>
                </form>
            </div>
        </div> -->

    </div>
    <!-- <div class="modal" id="editPostModal">
        <div class="modal-content">
            <h2>Sửa Bài Viết</h2>
            <form id="editPostForm">
                <input type="hidden" id="edit_post_id" name="id">
                <label for="edit_title">Tiêu đề:</label>
                <input type="text" id="edit_title" name="title" required>

                <label for="edit_content">Nội dung:</label>
                <textarea id="edit_content" name="content" rows="5" required></textarea>

                <button type="submit" class="btn save">Lưu</button>
                <button type="button" class="btn close-modal">Đóng</button>
            </form>
        </div>
    </div> -->


    <script src="js/jquery.js"></script>
    <script src="js/adm.js"></script>
</body>

</html>