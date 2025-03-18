<!-- H -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_new";

$ketnoi = mysqli_connect($servername, $username, $password, $dbname);

if (!$ketnoi) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
$ketnoi->set_charset("utf8");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// if ($id <= 0) {
//     die("ID không hợp lệ.");
// }
$posts = null;



// Lấy bài viết đã duyệt


if ($id > 0) {
    // Nếu có ID -> Lấy bài viết theo ID
    $sql = "SELECT title, content, created_at FROM posts WHERE id = ?";
    $stmt = $ketnoi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $posts = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    // Nếu không có ID -> Lấy bài viết mới nhất đã duyệt
    $sql = "SELECT title, content, created_at FROM posts WHERE status = 'approved' ORDER BY created_at DESC LIMIT 1";
    $result = $ketnoi->query($sql);

    if ($result->num_rows > 0) {
        $posts = $result->fetch_assoc();
    }
}

// Truy vấn chi tiết bài viết
// $sql = "SELECT title, content, created_at FROM posts WHERE id = ?";
// $stmt = mysqli_prepare($ketnoi, $sql);
// if ($stmt) {
//     mysqli_stmt_bind_param($stmt, "i", $id);
//     if (mysqli_stmt_execute($stmt)) {
//         $result = mysqli_stmt_get_result($stmt);

//         $posts = null;
//         if ($result && mysqli_num_rows($result) > 0) {
//             $posts = mysqli_fetch_assoc($result);
//         }
//     } else {
//         die("Lỗi truy vấn: " . mysqli_error($ketnoi));
//     }

//     // Đóng statement
//     mysqli_stmt_close($stmt);
// } else {
//     die("Lỗi chuẩn bị truy vấn.");
// }

mysqli_close($ketnoi);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bts4.css">
    <link rel="stylesheet" href="css/daw.css">
    <link rel="stylesheet" href="css/fas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>

<body>
    <div class="app">

        <header>
            <div class="containers">
                <div class="header">
                    <div class="header-logo">
                        <div class="header-img"><img src="img/logo.png" alt=""></div>
                    </div>
                    <div class="hotline">
                        <div class="header-hotline">
                            <i class="fa-solid fa-headphones-simple"></i>
                            <p>0909090909</p>
                        </div>
                        <div class="header-user">
                            <button class="btn btn-outline-primary" aria-placeholder="  Đăng nhập">
                                <a href="dangnhap.php">
                                    <p> Đăng nhập</p>
                                </a>
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </header>

        <main>
            <div class="banner">

                <div class="hero-section">
                    <img src="img/1.jpg" alt="Background" class="background-image">
                    <h1 class="title">Xách vali và đi</h1>
                </div>

                <div class="carousel-container">
                    <button class="carousel-prev">&lt;</button>
                    <div class="carousel">

                        <div class="carousel-item">
                            <img src="img/2.webp" alt="Quy Nhơn" data-id="14">
                            <p>Quy Nhơn</p>
                        </div>
                        <div class="carousel-item">
                            <img src="img/3.jpg" alt="Hạ Long" data-id="33">
                            <p>Hạ Long</p>
                        </div>
                        <div class="carousel-item">
                            <img src="img/4.jpg" alt="Sài Gòn" data-id="3">
                            <p>Sài Gòn</p>
                        </div>
                        <div class="carousel-item">
                            <img src="img/4.webp" alt="Đà Lạt" data-id="3">
                            <p>Đà Lạt</p>
                        </div>
                        <div class="carousel-item">
                            <img src="img/5.jpg" alt="Đà Nẵng" data-id="10">
                            <p>Đà Nẵng</p>
                        </div>
                        <div class="carousel-item">
                            <img src="img/3.jpg" alt="Cần Thơ" data-id="12">
                            <p>Cần Thơ</p>
                        </div>
                    </div>
                    <button class="carousel-next">&gt;</button>
                </div>
            </div>
            <div class="destination">
                <div class="destination-menu">

                    <a href="PLC.php?location_id=1" class="destination-menu-item here">
                        Điểm đến ▼
                    </a>
                    <!-- <div class="destination-dropdow">
                        <ul>
                            <li class="dropdow-domain_n">
                                Miền Bắc
                                <div class="domain_n-drop">
                                    <div class="drop-info">
                                        <ul>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=1">Hà Nội</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=2">Hà Giang</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=3">Hải Phòng</a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdow-domain_c">Miền Trung
                                <div class="domain_c-drop">
                                    <div class="drop-info">
                                        <ul>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=5">Đà nẵng</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=6">TP.Huế</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=77">Quảng Bình</a>
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                            </li>
                            <li class="dropdow-domain_s">Miền Nam
                                <div class="domain_s-drop">
                                    <div class="drop-info">
                                        <ul>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=11">Hồ Chí Minh</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=12">Cà Mau</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=13">Bến Tre</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="drop-info">
                                        <ul>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=11">Nha Trang</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=12">Cần Thơ</a>
                                            </li>
                                            <li class="drop-info-iteam">
                                                <a href="PLC.php?location_id=13">Đà Lạt
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div> -->
                    <a href="#" class="destination-menu-item">TP.Huế</a>
                    <a href="#" class="destination-menu-item">Đà Nẵng</a>
                    <a href="#" class="destination-menu-item">Hà Nội</a>
                    <a href="#" class="destination-menu-item">Du lịch Đà Lạt</a>
                    <a href="#" class="destination-menu-item">Quảng Nam</a>
                    <a href="#" class="destination-menu-item">Sài Gòn</a>
                </div>
                <div class="right-section">
                    <div class="icon"></div>
                    <span>Đã quan tâm</span>
                </div>
            </div>
            <div class="news">
                <div class="news-banner">
                    <div class="news-banner_left">
                        <div class="news-img-left   "> <img src="img/banner1.jpg" alt="">
                            <div class="text-overlay">
                                <h2>2222 NGÀY YÊU, CÙNG SĂN DEAL</h2>
                                <p>📅 13 Th2, 2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="news-banner_right">
                        <div class="news-img-right">
                            <img src="img/banner2.jpg" alt="">
                            <div class="text-overlay-right_top">
                                <h2>2222 NGÀY YÊU, CÙNG SĂN DEAL</h2>
                                <p>📅 13 Th2, 2025</p>
                            </div>
                        </div>
                        <div class="news-img-right">
                            <img src="img/banner4.jpg" alt="">
                            <div class="text-overlay-right_bot">
                                <h2>2222 NGÀY YÊU, CÙNG SĂN DEAL</h2>
                                <p>📅 13 Th2, 2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="travel">
                <div class="travel-container">

                    <div class="travel-title">
                        <p>Các điểm đến du lịch</p>
                    </div>
                    <div class="travel-wed">

                        <div class="travel-left">
                            <?php
                            include 'config.php';

                            $query = "SELECT id, title, image, created_at, content FROM posts WHERE id < 6";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="travel-item">.';
                                echo '  <div class="travel-img">';
                                echo '      <img src="' . $row['image'] . '" alt="">';
                                echo '  </div>';
                                echo '  <div class="travel-text">';
                                echo '      <h2 ><a  href="noidung.php?id=' . $row['id'] . '">' . $row['title'] . '</a></h2>';
                                echo '      <div class="travel-time">';
                                echo '          <i class="fa-regular fa-calendar-days"></i>';
                                echo '          <span> ' . date("d M Y", strtotime($row['created_at'])) . '</span>';
                                echo '      </div>';
                                echo '      <p>' . substr($row['content'], 0, 150) . '...</p>';
                                echo '  </div>';
                                echo '</div>';
                            }
                            ?>
                        </div>


                        <div class="travel-right">
                            <div class="travel-right-img">
                                <img src="img/banner10.jpg" alt="">
                            </div>
                            <div class="travel-right-img">
                                <img src="img/banner11.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </main>

        <footer style="background-color: #fff; color: black; padding: 40px 0; font-size: 14px; border-top: 3px solid #ff8000;">
            <div style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between;">
                <div style="flex: 2; min-width: 300px;">
                    <h3 style="font-weight: bold;">Khách sạn theo Tỉnh Thành</h3>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 150px;">
                            <p><strong>Khách sạn Bà Rịa - Vũng Tàu</strong></p>
                            <p>Khách sạn Hà Nội</p>
                            <p><strong>Khách sạn Đà Nẵng</strong></p>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <p>Khách sạn Phú Yên</p>
                            <p><strong>Khách sạn Khánh Hòa</strong></p>
                            <p><strong>Khách sạn TP Hồ Chí Minh</strong></p>
                        </div>
                    </div>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h3>Về HTHT</h3>
                    <p>Liên hệ</p>
                    <p>Điều khoản sử dụng</p>
                    <p>Chính sách bảo mật</p>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h3>Tải ứng dụng</h3>
                    <img src="appstore.png" alt="App Store" style="width: 120px; display: block;">
                    <img src="googleplay.png" alt="Google Play" style="width: 120px; margin-top: 5px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h3>Đăng ký nhận tin</h3>
                    <input type="email" placeholder="Email của bạn" style="width: 100%; padding: 5px;">
                    <button style="background: #ff8000; color: white; padding: 5px; width: 100%; margin-top: 5px; border: none;">Đăng ký</button>
                </div>
            </div>
            <div style="text-align: center; padding-top: 20px; border-top: 1px solid #ddd; margin-top: 20px;">
                <p>Bản quyền © 2025 HTHT.vn</p>
                <a href="#" style="color: black; margin-right: 10px;">Facebook</a>
                <a href="#" style="color: black; margin-right: 10px;">Instagram</a>
                <a href="#" style="color: black;">Blog du lịch</a>
            </div>
        </footer>



    </div>

    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            let select = document.querySelector("select[name='location_id']");
            let items = document.querySelectorAll(".drop-info-iteam a");

            // Xóa dữ liệu cũ trong select (nếu có)
            select.innerHTML = '<option value="">Chọn tỉnh thành</option>';

            // Duyệt danh sách và thêm vào select
            items.forEach(item => {
                let locationId = new URL(item.href).searchParams.get("location_id");
                let locationName = item.innerText;

                let option = document.createElement("option");
                option.value = locationId;
                option.textContent = locationName;

                select.appendChild(option);
            });

            // Khi chọn tỉnh thành từ danh sách
            items.forEach(item => {
                item.addEventListener("click", function(event) {
                    event.preventDefault(); // Ngăn chuyển trang

                    let locationId = new URL(this.href).searchParams.get("location_id");

                    // Chọn option tương ứng trong select
                    select.value = locationId;
                });
            });
        });
    </script> -->



    <script src=" js/anime.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/letterning.js"></script>
    <script src="js/scroll.js"></script>
    <script src="js/daw.js"></script>
</body>

</html>