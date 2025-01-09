<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Jenis>Daftar transportasi</Jenis>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Jenis>Daftar transportasi</Jenis>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-group-action {
            white-space: nowrap;
        }
    </style>
</head>
<body class="container py-4">
    <h1>Daftar transportasi</h1>
    
    <div class="row mb-3">
        <div class="col">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan ID">
        </div>
        <div class="col-auto">
            <button onclick="searchtransportasi()" class="btn btn-primary">Cari</button>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#transportasiModel">
                Tambah transportasi
            </button>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="transportasiList">
        </tbody>
    </table>

    <!-- Modal for Add/Edit transportasi -->
    <div class="modal fade" id="transportasiModel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-Jenis" id="modalJenis">Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="transportasiForm">
                        <input type="hidden" id="transportasiId">
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <input type="text" class="form-control" id="jenis" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Model</label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">harga</label>
                            <input type="number" class="form-control" id="harga" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="savevehicle()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost/transportasi/transportasi.php';
        let transportasiModel;

        document.addEventListener('DOMContentLoaded', function() {
            transportasiModel = new bootstrap.Modal(document.getElementById('transportasiModel'));
            loadtransportasi();
        });

        function loadtransportasi() {
            fetch(API_URL)
                .then(response => response.json())
                .then(transportasi => {
                    const transportasiList = document.getElementById('transportasiList');
                    transportasiList.innerHTML = '';
                    transportasi.forEach(transportasi => {
                        transportasiList.innerHTML += `
                            <tr>
                                <td>${transportasi.id}</td>
                                <td>${transportasi.Jenis}</td>
                                <td>${transportasi.nama}</td>
                                <td>${transportasi.harga}</td>
                                 <td>${transportasi.price}</td>
                                <td class="btn-group-action">
                                    <button class="btn btn-sm btn-warning me-1" onclick="edittransportasi(${transportasi.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deletetransportasi(${transportasi.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => alert('Error loading transportasi: ' + error));
        }

        function searchtransportasi() {
            const id = document.getElementById('searchInput').value;
            if (!id) {
                loadtransportasi();
                return;
            }
            
            fetch(`${API_URL}/${id}`)
                .then(response => response.json())
                .then(transportasi => {
                    const transportasiList = document.getElementById('transportasiList');
                    if (transportasi.message) {
                        alert('transportasi not found');
                        return;
                    }
                    transportasiList.innerHTML = `
                        <tr>
                            <td>${transportasi.id}</td>
                            <td>${transportasi.Jenis}</td>
                            <td>${transportasi.nama}</td>
                            <td>${transportasi.harga}</td>
                            <td class="btn-group-action">
                                <button class="btn btn-sm btn-warning me-1" onclick="edittransportasi(${transportasi.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deletetransportasi(${transportasi.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                })
                .catch(error => alert('Error searching transportasi: ' + error));
        }

        function edittransportasi(id) {
            fetch(`${API_URL}/${id}`)
                .then(response => response.json())
                .then(transportasi => {
                    document.getElementById('transportasiId').value = transportasi.id;
                    document.getElementById('Jenis').value = transportasi.Jenis;
                    document.getElementById('nama').value = transportasi.nama;
                    document.getElementById('harga').value = transportasi.harga;
                    document.getElementById('modalJenis').textContent = 'Edit transportasi';
                    transportasiModel.show();
                })
                .catch(error => alert('Error loading transportasi details: ' + error));
        }

        function deletetransportasi(id) {
            if (confirm('Are you sure you want to delete this transportasi?')) {
                fetch(`${API_URL}/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert('transportasi deleted successfully');
                    loadtransportasi();
                })
                .catch(error => alert('Error deleting transportasi: ' + error));
            }
        }

        function savevehicle() {
            const transportasiId = document.getElementById('transportasiId').value;
            const vehicleData = {
                Jenis: document.getElementById('Jenis').value,
                nama: document.getElementById('nama').value,
                harga: document.getElementById('harga').value,
            };

            const method = transportasiId ? 'PUT' : 'POST';
            const url = transportasiId ? `${API_URL}/${transportasiId}` : API_URL;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(vehicleData)
            })
            .then(response => response.json())
            .then(data => {
                alert(transportasiId ? 'transportasi updated successfully' : 'transportasi added successfully');
                transportasiModel.hide();
                loadtransportasi();
                resetForm();
            })
            .catch(error => alert('Error saving transportasi: ' + error));
        }

        function resetForm() {
            document.getElementById('transportasiId').value = '';
            document.getElementById('transportasiForm').reset();
            document.getElementById('modalJenis').textContent = 'Tambah transportasi';
        }

        // Reset form when modal is closed
        document.getElementById('transportasiModel').addEventListener('hidden.bs.modal', resetForm);
    </script>
                </body>
    <!-- Modal Tambah transportasi -->
    <div class="modal fade" id="adddbModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-Jenis">Tambah transportasi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="adddbForm">
                        <!-- <div class="mb-3">
                            <label class="form-label">Id</label>
                            <input type="text" name="Jenis" class="form-control" required>
                        </div> -->
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <input type="text" name="Jenis" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">harga</label>
                            <input type="harga" name="harga" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="adddb()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function adddb() {
        const form = document.getElementById('adddbForm');
        const formData = new FormData(form);
        const data = {
            Jenis: formData.get('Jenis'),
            nama: formData.get('nama'),
            harga: parseInt(formData.get('harga')), 
        };

        fetch('http://localhost/transportasi/transportasi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if(data.message === "transportasi created") {
                alert('transportasi berhasil ditambahkan!');
                window.location.reload();
            } else {
                alert('Gagal menambahkan transportasi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
    </script>
</body>
</html>