<?php
// Thiết lập thông tin kết nối database
$servername = "database-server-lab7.c0shi1mfrpld.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "29122025";
$dbname = "myDB";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$error = ""; // Biến lưu thông báo lỗi

// Xử lý khi form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = trim($_POST["username"] ?? "");
    $password_input = trim($_POST["password"] ?? "");

    // Sử dụng prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username_input, $password_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Đăng nhập thành công!'); window.location.href='welcome.php';</script>";
        // Ở đây bạn có thể dùng session để redirect thực tế
        // session_start(); $_SESSION['user'] = $username_input; header("Location: welcome.php");
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Modern UI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
            opacity: 0.15;
            z-index: -2;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
        }

        .login-container h2 {
            font-size: 2.1rem;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .login-container p {
            margin-bottom: 30px;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        }

        .form-group input::placeholder {
            color: rgba(255,255,255,0.6);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .error {
            color: #ff6b6b;
            margin: 15px 0;
            font-size: 0.92rem;
            background: rgba(255,107,107,0.15);
            padding: 10px;
            border-radius: 8px;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <p>Chào mừng trở lại! Vui lòng nhập thông tin</p>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn-login">Đăng Nhập</button>
        </form>
    </div>

</body>
</html>