<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_new";

$ketnoi = mysqli_connect($servername, $username, $password, $dbname);

if (!$ketnoi) {
    die("Kết nối thất bại: " . mysqli_connect_error());
} // Kết nối database

if (isset($_GET['location_id'])) {
    $location_id = intval($_GET['location_id']);

    $sql = "SELECT id, title, content, image_url, created_at FROM articles WHERE location_id = ?";
    $stmt = $ketnoi->prepare($sql);
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $articles = [];
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }

    echo json_encode($articles);
}
