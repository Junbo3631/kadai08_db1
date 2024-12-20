<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スリーサイズデータ検索</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        form {
            margin: 20px 0;
        }

        input[type="text"],
        button {
            padding: 10px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>スリーサイズデータ検索</h1>

        <!-- 検索フォーム -->
        <form action="search.php" method="GET">
            <input type="text" name="search" placeholder="名前で検索" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
            <button type="submit">検索</button>
        </form>

        <!-- データ一覧 -->
        <div>
            <table>
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>バスト</th>
                        <th>ウエスト</th>
                        <th>ヒップ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // DB接続情報
                    $host = 'mysql3104.db.sakura.ne.jp';
                    $dbname = 'junbo3631_3size_db';
                    $username = 'junbo3631_3size_db';
                    $password = 'junko3631';


                    try {
                        // データベース接続
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // 検索クエリを取得
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                        // SQLクエリの準備
                        if ($search !== '') {
                            // 名前で検索
                            $stmt = $pdo->prepare("SELECT name, bust, waist, hip FROM 3size_table WHERE name LIKE :search");
                            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
                            $stmt->execute();
                        } else {
                            // 全データ取得
                            $stmt = $pdo->query("SELECT name, bust, waist, hip FROM 3size_table");
                        }

                        // 結果を取得して表示
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($results) {
                            foreach ($results as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['bust'], ENT_QUOTES, 'UTF-8') . " cm</td>";
                                echo "<td>" . htmlspecialchars($row['waist'], ENT_QUOTES, 'UTF-8') . " cm</td>";
                                echo "<td>" . htmlspecialchars($row['hip'], ENT_QUOTES, 'UTF-8') . " cm</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>データが見つかりません。</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='4'>データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>