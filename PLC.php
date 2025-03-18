<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travel_new";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách tỉnh thành

$result = $conn->query("
    SELECT id, name, region FROM locations 
    ORDER BY 
        FIELD(region, 'Bắc', 'Trung', 'Nam'), name ASC
");

// ORDER BY  Sắp xếp
// CASE 
//    WHEN region = 'Bắc' THEN 1
//    WHEN region = 'Trung' THEN 2
//    WHEN region = 'Nam' THEN 3
// END, 
// name ASC;

$regions = []; // Đảm bảo biến tồn tại trước khi dùng

while ($row = $result->fetch_assoc()) {
    $regions[$row['region']][] = $row;
}

if (empty($regions)) {
    die("Dữ liệu regions rỗng, kiểm tra lại database!");
}


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
    <style>
        .drop-info {
            display: flex;
            justify-content: center;
            width: 100%;
            /* Đảm bảo chiếm toàn bộ chiều rộng */
        }

        .drop-info ul {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
            /* Đảm bảo danh sách tỉnh phủ kín */
            padding: 15px;
            list-style: none;
            text-align: center;
        }



        li.dropdow-domain {
            display: flex;
            width: 100%;
            height: 67px;
            align-items: center;
            padding-left: 20px;
        }

        .domain-drop.domain-bắc {
            position: absolute;
            top: 0%;
            left: 125px;
            width: 85%;
            display: none;
            background: white;
            padding: 5px 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            height: 300%;
        }

        .domain-drop.domain-trung {
            position: absolute;
            display: none;
            top: -98%;
            left: 125px;
            width: 85%;
            background: white;
            padding: 5px 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            height: 300%;
        }

        .domain-drop.domain-nam {
            position: absolute;
            top: -200%;
            display: none;
            left: 125px;
            width: 85%;
            background: white;
            padding: 5px 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            height: 300%;
        }


        .destination-dropdow li:hover.drop-info-iteam a {
            color: #f58220;
            background: #fff;
            text-decoration: none;
        }

        .dropdow-domain:hover {
            background-color: #ff7f50;
            color: #fff;
            cursor: pointer;
        }



        .dropdow-domain {
            position: relative;
            /* Giữ vị trí cho dropdown */
            padding: 10px;
            cursor: pointer;
        }

        .dropdow-domain:hover .domain-drop {
            display: block;
            /* Hiện danh sách tỉnh khi hover vào miền */
        }
    </style>
</head>

<body>
    <div class="app">
        <header>
            <div class="containers">
                <div class="header">
                    <div class="header-logo">
                        <a href="home.php">
                            <div class="header-img"><img src="img/logo.png" alt=""></div>
                        </a>
                    </div>
                    <div class="hotline">
                        <div class="header-hotline">
                            <i class="fa-solid fa-headphones-simple"></i>
                            <p>0909090909</p>
                        </div>
                        <div class="header-user">
                            <button class="btn btn-outline-primary" aria-placeholder="  Đăng nhập">
                                <p> Đăng nhập</p>
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
                <div class="destination">
                    <div class="destination-menu">

                        <a href="PLC.php?location_id=1" class="destination-menu-item here">
                            Điểm đến ▼
                        </a>
                        <div class="destination-dropdow">
                            <ul>
                                <?php foreach ($regions as $region => $provinces): ?>
                                    <li class="dropdow-domain">
                                        <?= "Miền " . $region ?>
                                        <div class="domain-drop domain-<?= strtolower($region) ?>"> <!-- Thêm class động -->
                                            <div class="drop-info">
                                                <ul>
                                                    <?php foreach ($provinces as $province): ?>
                                                        <li class="drop-info-iteam">
                                                            <a href="PLC.php?location_id=<?= $province['id'] ?>">
                                                                <?= htmlspecialchars($province['name']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                        </div>
                        <a href="#" class="destination-menu-item">Bà Rịa</a>
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
            </div>
        </main>
    </div>
    <?php
    if (isset($_GET['location_id'])) {
        $location_id = intval($_GET['location_id']);

        $stmt = $conn->prepare("SELECT id ,title, image, created_at, views FROM posts WHERE location_id = ?");
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Bài viết liên quan</h2>";
        echo "<div style='display: flex; flex-direction: column; gap: 20px;'>";

        while ($row = $result->fetch_assoc()) {
            echo "<a href='noidung.php?id=" . htmlspecialchars($row['id']) . "' style='text-decoration: none; color: inherit;'>";
            echo "<div style='display: flex; border: 1px solid #ccc; padding: 10px; gap: 20px; align-items: center;'>";

            if (!empty($row['image'])) {
                echo "<img src='" . htmlspecialchars($row['image']) . "' style='width: 150px; height: auto; object-fit: cover;'>";
            }

            echo "<div>";
            echo "<h3 style='margin: 0;'>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p style='margin: 5px 0; font-size: 14px; color: #888;'>📅 " . date('d M, Y', strtotime($row['created_at'])) . " </p>";
            echo "</div>";

            echo "</div>";
            echo "</a>";
        }

        // while ($row = $result->fetch_assoc()) {
        //     echo "<div style='display: flex; border: 1px solid #ccc; padding: 10px; gap: 20px; align-items: center;'>";
        //     if (!empty($row['image'])) {
        //         echo "<img src='" . htmlspecialchars($row['image']) . "' style='width: 150px; height: auto; object-fit: cover;'>";
        //     }
        //     echo "<div>";
        //     echo "<h3 style='margin: 0;'>" . htmlspecialchars($row['title']) . "</h3>";
        //     echo "<p style='margin: 5px 0; font-size: 14px; color: #888;'>📅 " . date('d M, Y', strtotime($row['created_at'])) . " | 👁️ " . htmlspecialchars($row['views']) . " lượt xem</p>";
        //     echo "</div>";
        //     echo "</div>";
        // }
        echo "</div>";
    }
    $conn->close();
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuItem = document.querySelector(".here");
            const dropdown = document.querySelector(".destination-dropdow");

            if (menuItem && dropdown) {
                menuItem.addEventListener("mouseenter", function() {
                    dropdown.classList.add("show");
                });

                menuItem.addEventListener("mouseleave", function(event) {
                    // Kiểm tra nếu chuột rời khỏi `.here` nhưng vẫn trong `.destination-dropdown`
                    if (!dropdown.contains(event.relatedTarget)) {
                        dropdown.classList.remove("show");
                    }
                });

                dropdown.addEventListener("mouseleave", function() {
                    dropdown.classList.remove("show");
                });
            }
        });
    </script>




    <script src="js/anime.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/letterning.js"></script>
    <script src="js/scroll.js"></script>
    <script src="js/daw.js"></script>
</body>

</html>