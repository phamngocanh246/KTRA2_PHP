<?php
session_start();

// Kiểm tra xem thông tin kết nối có trong session không
if (!isset($_SESSION['server']) || !isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['database'])) {
    echo '<div class="alert alert-danger">Chưa kết nối cơ sở dữ liệu. Vui lòng kết nối trước khi thực hiện.</div>';
    exit();
}

// Nếu đã có thông tin kết nối trong session
$server = $_SESSION['server'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$database = $_SESSION['database'];

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die('<div class="alert alert-danger">Kết nối thất bại: ' . $conn->connect_error . '</div>');
}

// Xử lý ghi file nếu người dùng nhấn submit
if (isset($_POST['submit'])) {
    $filename = $_POST['filename'] . ".txt"; 
    $fileContent = "\n"; 

    // Lấy dữ liệu từ database
    $sql = "SELECT Title, Description, ImageUrl FROM course"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Kiểm tra xem các khóa có tồn tại trong mảng không
            if (isset($row['Title']) && isset($row['Description'])) {
                $fileContent .= "Tiêu đề: " . $row['Title'] . "\nMô tả: " . $row['Description'] . "\nHình ảnh: " . $row['ImageUrl'] . "\n\n"; // Ghi dữ liệu vào nội dung file
            }
        }
    } else {
        $fileContent .= "Không có khóa học nào.\n"; 
    }

    // Ghi nội dung vào file
    if (file_put_contents($filename, $fileContent) !== false) {
        echo '<div class="alert alert-success">File mới đã được tạo và dữ liệu đã được ghi.</div>';
    } else {
        echo '<div class="alert alert-danger">Không thể ghi vào file.</div>';
    }
}

// Lấy danh sách khóa học để hiển thị trên trang
$courses = [];
$sql = "SELECT Title, Description, ImageUrl FROM course"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row; 
    }
} else {
    echo '<div class="alert alert-warning">Không có khóa học nào để hiển thị.</div>';
}

$conn->close(); // Đóng kết nối
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Khóa học PHP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Trang chủ</a>
                        <a class="nav-link" href="connect.php">Kết nối MySQL</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($courses as $course): ?>
                <div class="col">
                    <div class="card">
                        <img src="<?= $course['ImageUrl'] ?>" class="card-img-top" alt="<?= $course['Title'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $course['Title'] ?></h5>
                            <p class="card-text"><?= $course['Description'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>
        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="filename" placeholder="File name" name="filename" required>
                    <label for="filename">File name</label>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Write file</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
