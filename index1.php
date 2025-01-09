<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Clothes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Jenis Clothes</h2>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Ambil data dari API
                        $db = file_get_contents('http://localhost/transportasi/transportasi.php');

                        // Ubah JSON menjadi array PHP
                        $db = json_decode($db, true);

                        // Tampilkan data dalam tabel
                        if (!empty($db)) {
                            foreach ($db as $data) {
                                echo "<tr>";
                                echo "<td>{$data['id']}</td>";
                                echo "<td>{$data['brand']}</td>";
                                echo "<td>{$data['model']}</td>";
                                echo "<td>{$data['year']}</td>";
                                echo "<td>{$data['price']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Tidak ada data buku</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='4' class='text-center text-danger'>Error: Tidak dapat mengambil data dari API</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>