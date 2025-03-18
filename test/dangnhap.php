<?php
session_start();
$ketnoi = mysqli_connect("localhost", "root", "", "travel_new");
if (!$ketnoi) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tk = trim($_POST['tk']);
    $mk = trim($_POST['mk']);

    // Kiểm tra tài khoản có tồn tại không
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($ketnoi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $tk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Kiểm tra mật khẩu
        if ($user['password'] === $mk) {
            $_SESSION['username'] = $tk;
            $_SESSION['role'] = $user['role'];

            // Chuyển hướng trang phù hợp
            $redirect = ($user['role'] === 'admin') ? 'ADM.php' : 'home.php';
            header("Location: $redirect");
            exit;
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}

// Đóng kết nối
mysqli_close($ketnoi);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Tài khoản:</label>
            <input type="text" name="tk" required><br>

            <label>Mật khẩu:</label>
            <input type="password" name="mk" required><br>

            <input type="submit" value="Đăng nhập">
        </form>
    </div>
</body>
</html>
