<?php
// index.php - Hiển thị danh sách thành phố + tổng dân số

$servername = "database-primary.c4qgc6wdmope.us-east-1.rds.amazonaws.com"; 
$username = "admin"; 
$password = "12345678"; 
$dbname = "myDB"; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối database thất bại: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Danh sách các thành phố trên thế giới</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #2d3748;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #1a365d;
            margin-bottom: 8px;
        }

        .total {
            text-align: center;
            font-size: 1.4rem;
            margin: 20px 0 40px;
            padding: 16px;
            background-color: #ebf8ff;
            border-radius: 8px;
            font-weight: 500;
        }

        .total strong {
            color: #2b6cb0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #2c5282;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f7fafc;
        }

        tr:hover {
            background-color: #edf2f7;
            transition: background-color 0.2s;
        }

        .city-name {
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Thông tin các thành phố</h1>

    <?php
    // Tính tổng dân số
    $stmt = $pdo->query("SELECT SUM(Population) as total FROM city");
    $total = $stmt->fetch()['total'] ?? 0;
    $total_formatted = number_format($total);
    ?>

    <div class="total">
        Tổng dân số tất cả thành phố trong dữ liệu: 
        <strong><?php echo $total_formatted; ?> người</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thành phố</th>
                <th>Mã quốc gia</th>
                <th>Quận / Khu vực</th>
                <th>Dân số</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM city ORDER BY Name ASC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['ID']}</td>";
            echo "<td class='city-name'>" . htmlspecialchars($row['Name']) . "</td>";
            echo "<td>{$row['CountryCode']}</td>";
            echo "<td>" . htmlspecialchars($row['District']) . "</td>";
            echo "<td>" . number_format($row['Population']) . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
