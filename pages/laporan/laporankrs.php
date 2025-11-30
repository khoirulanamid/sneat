<?php
include 'config/koneksi.php';

// Hanya mahasiswa yang sudah memiliki KRS
$mahasiswaList = $pdo->query(
    "SELECT DISTINCT m.id_mahasiswa, m.nim, m.nama
     FROM krs k
     JOIN mahasiswa m ON k.id_mahasiswa = m.id_mahasiswa
     ORDER BY m.nama ASC"
)->fetchAll(PDO::FETCH_ASSOC);

$idMahasiswa = $_GET['id_mahasiswa'] ?? '';
$semester = $_GET['semester'] ?? '';
$tahunAjaran = $_GET['tahun_ajaran'] ?? '';

$availableSemester = [];
$availableTahunAjaran = [];
$logoSrc = asset_url('public/assets/img/logo-unuha.png');

// Endpoint ringan untuk memuat opsi semester/tahun ajaran ketika mahasiswa dipilih (AJAX)
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    // Bersihkan buffer agar respons JSON tidak tercampur markup layout
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Type: application/json');
    $response = ['semester' => [], 'tahun_ajaran' => []];
    $ajaxMahasiswa = $_GET['id_mahasiswa'] ?? '';

    if ($ajaxMahasiswa) {
        $stmtSem = $pdo->prepare("SELECT DISTINCT semester FROM krs WHERE id_mahasiswa = :id ORDER BY semester ASC");
        $stmtSem->execute([':id' => $ajaxMahasiswa]);
        $response['semester'] = $stmtSem->fetchAll(PDO::FETCH_COLUMN);

        $stmtTh = $pdo->prepare("SELECT DISTINCT tahun_ajaran FROM krs WHERE id_mahasiswa = :id ORDER BY tahun_ajaran DESC");
        $stmtTh->execute([':id' => $ajaxMahasiswa]);
        $response['tahun_ajaran'] = $stmtTh->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode($response);
    exit;
}

if ($idMahasiswa) {
    $availableSemester = $pdo->prepare("SELECT DISTINCT semester FROM krs WHERE id_mahasiswa = :id ORDER BY semester ASC");
    $availableSemester->execute([':id' => $idMahasiswa]);
    $availableSemester = $availableSemester->fetchAll(PDO::FETCH_COLUMN);

    $availableTahunAjaran = $pdo->prepare("SELECT DISTINCT tahun_ajaran FROM krs WHERE id_mahasiswa = :id ORDER BY tahun_ajaran DESC");
    $availableTahunAjaran->execute([':id' => $idMahasiswa]);
    $availableTahunAjaran = $availableTahunAjaran->fetchAll(PDO::FETCH_COLUMN);
}

$filters = [];
$params = [];

if ($idMahasiswa) {
    $filters[] = 'krs.id_mahasiswa = :id_mahasiswa';
    $params[':id_mahasiswa'] = $idMahasiswa;
}

if ($semester) {
    $filters[] = 'krs.semester = :semester';
    $params[':semester'] = (int)$semester;
}

if ($tahunAjaran) {
    $filters[] = 'krs.tahun_ajaran = :tahun_ajaran';
    $params[':tahun_ajaran'] = $tahunAjaran;
}

$whereClause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

$data = [];
$mahasiswaInfo = null;

if ($idMahasiswa) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan,
                matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
         FROM krs
         LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
         $whereClause
         ORDER BY krs.tanggal_pengisian DESC"
    );
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        $mahasiswaInfo = [
            'nim' => $data[0]['nim'] ?? '',
            'nama' => $data[0]['nama'] ?? '',
            'jurusan' => $data[0]['jurusan'] ?? '',
        ];
    } else {
        $stmtInfo = $pdo->prepare("SELECT nim, nama, jurusan FROM mahasiswa WHERE id_mahasiswa = :id");
        $stmtInfo->execute([':id' => $idMahasiswa]);
        $mahasiswaInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<style id="krs-print-style">
    /* Paksa warna kontras saat dicetak/pdf */
    #laporan-krs {
        color: #0f172a;
        background: #ffffff;
    }
    #laporan-krs strong {
        color: #0f172a;
    }
#laporan-krs table {
    color: #0f172a;
    background: #ffffff;
}
#laporan-krs table thead tr {
        background: #0b1220;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.5px;
}
#laporan-krs table th,
#laporan-krs table td {
        border-color: #e2e8f0;
}
</style>

<h4 class="fw-bold"><span class="text-muted fw-light">Laporan /</span> KRS</h4>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo page_url('laporan/laporankrs'); ?>" id="form-krs">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="mahasiswa" name="id_mahasiswa" required>
                        <?php if (!$mahasiswaList) : ?>
                            <option value="">Belum ada data KRS</option>
                        <?php else : ?>
                            <option value="">Pilih Mahasiswa</option>
                            <?php foreach ($mahasiswaList as $mhs) : ?>
                                <option value="<?php echo $mhs['id_mahasiswa']; ?>" <?php echo ($idMahasiswa == $mhs['id_mahasiswa']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($mhs['nim'] . ' - ' . $mhs['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select" id="semester" name="semester" <?php echo $idMahasiswa ? '' : 'disabled'; ?> required>
                        <?php if (!$idMahasiswa) : ?>
                            <option value="">Pilih mahasiswa terlebih dahulu</option>
                        <?php elseif (!$availableSemester) : ?>
                            <option value="">Belum ada KRS untuk mahasiswa ini</option>
                        <?php else : ?>
                            <option value="">Pilih Semester</option>
                            <?php foreach ($availableSemester as $sem) : ?>
                                <option value="<?php echo $sem; ?>" <?php echo ($semester == $sem) ? 'selected' : ''; ?>>
                                    Semester <?php echo htmlspecialchars($sem); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun Ajaran</label>
                    <select class="form-select" id="tahun" name="tahun_ajaran" <?php echo $idMahasiswa ? '' : 'disabled'; ?> required>
                        <?php if (!$idMahasiswa) : ?>
                            <option value="">Pilih mahasiswa terlebih dahulu</option>
                        <?php elseif (!$availableTahunAjaran) : ?>
                            <option value="">Belum ada KRS untuk mahasiswa ini</option>
                        <?php else : ?>
                            <option value="">Pilih Tahun Ajaran</option>
                            <?php foreach ($availableTahunAjaran as $th) : ?>
                                <option value="<?php echo htmlspecialchars($th); ?>" <?php echo ($tahunAjaran == $th) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($th); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="<?php echo page_url('laporan/laporankrs'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($idMahasiswa && $semester && $tahunAjaran) : ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data KRS</h5>
                <?php if ($data) : ?>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success btn-sm" id="btn-print-krs">Cetak PDF</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-print-krs-paper">Cetak Printer</button>
                    </div>
                <?php endif; ?>
            </div>
            <div id="laporan-krs">
                <?php if ($mahasiswaInfo) : ?>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3 mb-2 mb-md-0 d-flex">
                            <img src="<?php echo $logoSrc; ?>" alt="Logo UNUHA" style="height: 64px; width: auto; border-radius: 6px; background: rgba(0,0,0,0.02); padding: 6px;">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div><strong>Nama:</strong> <?php echo htmlspecialchars($mahasiswaInfo['nama'] ?? '-'); ?></div>
                                    <div><strong>NIM:</strong> <?php echo htmlspecialchars($mahasiswaInfo['nim'] ?? '-'); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div><strong>Jurusan:</strong> <?php echo htmlspecialchars($mahasiswaInfo['jurusan'] ?? '-'); ?></div>
                                    <div><strong>Semester/Tahun:</strong> <?php echo htmlspecialchars($semester . ' / ' . $tahunAjaran); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Status</th>
                                <th>Tanggal Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data) : ?>
                                <?php foreach ($data as $index => $row) : ?>
                                    <?php $tanggal = !empty($row['tanggal_pengisian']) ? date('d/m/Y H:i', strtotime($row['tanggal_pengisian'])) : '-'; ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($row['kode_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['sks'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td><?php echo htmlspecialchars($tanggal); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data untuk kombinasi filter ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0" role="alert">
                Silakan pilih mahasiswa, semester, dan tahun ajaran terlebih dahulu.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mahasiswaSelect = document.getElementById('mahasiswa');
        const semesterSelect = document.getElementById('semester');
        const tahunSelect = document.getElementById('tahun');
        const initialMahasiswa = '<?php echo htmlspecialchars($idMahasiswa, ENT_QUOTES); ?>';
        const currentSemester = '<?php echo htmlspecialchars($semester, ENT_QUOTES); ?>';
        const currentTahun = '<?php echo htmlspecialchars($tahunAjaran, ENT_QUOTES); ?>';
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (m) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m]));

        const resetSelect = (select, placeholder, disable = false) => {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = disable;
        };

        const populateSelect = (select, items, placeholder, selectedValue = '') => {
            let options = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                const safeValue = escapeHtml(item);
                const isSelected = item == selectedValue ? 'selected' : '';
                const label = select.id === 'semester' ? 'Semester ' + safeValue : safeValue;
                options += `<option value="${safeValue}" ${isSelected}>${label}</option>`;
            });
            select.innerHTML = options;
            select.disabled = !items.length;
        };

        mahasiswaSelect.addEventListener('change', function() {
            const id = this.value;
            if (!id) {
                resetSelect(semesterSelect, 'Pilih mahasiswa terlebih dahulu', true);
                resetSelect(tahunSelect, 'Pilih mahasiswa terlebih dahulu', true);
                return;
            }

            resetSelect(semesterSelect, 'Memuat semester...', true);
            resetSelect(tahunSelect, 'Memuat tahun ajaran...', true);

            const selectedSemester = id === initialMahasiswa ? currentSemester : '';
            const selectedTahun = id === initialMahasiswa ? currentTahun : '';

            const url = new URL(window.location.href);
            url.searchParams.set('ajax', '1');
            url.searchParams.set('id_mahasiswa', id);

            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.json())
                .then(data => {
                    const semData = Array.isArray(data.semester) ? data.semester : [];
                    const thData = Array.isArray(data.tahun_ajaran) ? data.tahun_ajaran : [];

                    if (semData.length) {
                        populateSelect(semesterSelect, semData, 'Pilih Semester', selectedSemester);
                    } else {
                        resetSelect(semesterSelect, 'Belum ada KRS untuk mahasiswa ini', true);
                    }

                    if (thData.length) {
                        populateSelect(tahunSelect, thData, 'Pilih Tahun Ajaran', selectedTahun);
                    } else {
                        resetSelect(tahunSelect, 'Belum ada KRS untuk mahasiswa ini', true);
                    }
                })
                .catch(() => {
                    resetSelect(semesterSelect, 'Gagal memuat data', true);
                    resetSelect(tahunSelect, 'Gagal memuat data', true);
                });
        });

        // Otomatis isi ulang jika halaman sudah punya mahasiswa terpilih (mis. setelah submit)
        if (mahasiswaSelect.value) {
            mahasiswaSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<?php if ($idMahasiswa && $semester && $tahunAjaran && $data) : ?>
    <script>
        document.getElementById('btn-print-krs').addEventListener('click', function() {
            const element = document.getElementById('laporan-krs');
            const opt = {
                margin: 0.5,
                filename: 'laporan-krs-<?php echo htmlspecialchars($semester . '-' . $tahunAjaran); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        });

        const btnPrintPaperKrs = document.getElementById('btn-print-krs-paper');
        if (btnPrintPaperKrs) {
            btnPrintPaperKrs.addEventListener('click', function() {
                const area = document.getElementById('laporan-krs');
                const logoHtml = '';
                const style = `
                    body { font-family: 'Segoe UI', Arial, sans-serif; margin: 16px; }
                    h5 { margin: 0 0 8px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #333; padding: 8px; font-size: 13px; }
                    th { background: #f0f0f0; }
                    .text-center { text-align: center; }
                    .text-end { text-align: end; }
                    .mb-3 { margin-bottom: 12px; }
                    .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
                    .col-md-6 { flex: 0 0 50%; max-width: 50%; padding: 0 8px; box-sizing: border-box; }
                    .d-flex { display: flex; }
                    .align-items-center { align-items: center; }
                    .gap-2 { gap: 8px; }
                `;
                const win = window.open('', '_blank', 'width=900,height=700');
                if (!win) {
                    alert('Popup diblokir. Izinkan popup untuk mencetak.');
                    return;
                }
                win.document.open();
                win.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head><meta charset="utf-8"><title>Cetak KRS</title><style>${style}</style></head>
                    <body>${area.outerHTML}</body>
                    </html>
                `);
                win.document.close();
                win.focus();
                win.print();
                setTimeout(() => win.close(), 500);
            });
        }
    </script>
<?php endif; ?>
