<?php
include_once __DIR__ . '/../../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_mahasiswa = isset($_GET['id_mahasiswa']) ? (int)$_GET['id_mahasiswa'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';

$logoSrc = asset_url('public/assets/img/logo-unuha.png');

$single = null;
if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan, matakuliah.kode_matkul, matakuliah.nama_matkul, matakuliah.sks
         FROM krs
         LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
         WHERE krs.id_krs = :id"
    );
    $stmt->execute([':id' => $id]);
    $single = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Group entries
$groupEntries = [];
$student = null;
if (!$single && $id_mahasiswa && $semester && $tahun_ajaran) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, m.nim, m.nama, m.jurusan, mk.kode_matkul, mk.nama_matkul, mk.sks
         FROM krs
         LEFT JOIN mahasiswa m ON krs.id_mahasiswa = m.id_mahasiswa
         LEFT JOIN matakuliah mk ON krs.id_matkul = mk.id_matkul
         WHERE krs.id_mahasiswa = :id_mahasiswa
           AND krs.semester = :semester
           AND krs.tahun_ajaran = :tahun_ajaran
         ORDER BY mk.nama_matkul ASC"
    );
    $stmt->execute([
        ':id_mahasiswa' => $id_mahasiswa,
        ':semester' => $semester,
        ':tahun_ajaran' => $tahun_ajaran,
    ]);
    $groupEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($groupEntries)) {
        $student = [
            'nama' => $groupEntries[0]['nama'],
            'nim' => $groupEntries[0]['nim'],
            'jurusan' => $groupEntries[0]['jurusan']
        ];
    }
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KRS /</span> Detail KRS</h4>

<?php if ($single) : ?>
    <?php $recordedAt = !empty($data['tanggal_pengisian']) ? date('d/m/Y H:i', strtotime($data['tanggal_pengisian'])) : '-'; ?>
    <style>
        .detail-card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5ecf6;
            position: relative;
        }
        .detail-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Crect width='220' height='220' fill='none'/%3E%3Cpath d='M30 30c28 12 32 46 0 60s-28 46 0 60' fill='none' stroke='%23dcefe0' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M190 40c-26 10-30 42 0 56s26 42 0 56' fill='none' stroke='%23d2f2d2' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M110 0c14 24 14 48 0 72s-14 48 0 72s14 48 0 72' fill='none' stroke='%23e8f8e4' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.28'/%3E%3Ccircle cx='60' cy='140' r='10' fill='%23b7e4c7' opacity='0.35'/%3E%3Ccircle cx='160' cy='90' r='12' fill='%23c6f2c1' opacity='0.3'/%3E%3Ccircle cx='120' cy='170' r='8' fill='%23a9dbb4' opacity='0.32'/%3E%3Cpath d='M70 200q10-12 20 0t20 0' fill='none' stroke='%23f7fff7' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.4'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 260px 260px;
            opacity: 0.4;
            mix-blend-mode: soft-light;
            pointer-events: none;
        }
        .detail-head {
            background: linear-gradient(135deg, #0b5129, #13873f),
                        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Crect width='220' height='220' fill='none'/%3E%3Cpath d='M30 30c28 12 32 46 0 60s-28 46 0 60' fill='none' stroke='%23dcefe0' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M190 40c-26 10-30 42 0 56s26 42 0 56' fill='none' stroke='%23d2f2d2' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M110 0c14 24 14 48 0 72s-14 48 0 72s14 48 0 72' fill='none' stroke='%23e8f8e4' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.28'/%3E%3Ccircle cx='60' cy='140' r='10' fill='%23b7e4c7' opacity='0.35'/%3E%3Ccircle cx='160' cy='90' r='12' fill='%23c6f2c1' opacity='0.3'/%3E%3Ccircle cx='120' cy='170' r='8' fill='%23a9dbb4' opacity='0.32'/%3E%3Cpath d='M70 200q10-12 20 0t20 0' fill='none' stroke='%23f7fff7' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.4'/%3E%3C/svg%3E");
            background-repeat: no-repeat, repeat;
            background-size: cover, 240px 240px;
            background-blend-mode: overlay, soft-light;
            color: #f4fff5;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        .detail-brand { display: flex; align-items: center; gap: 12px; }
        .detail-logo { height: 48px; width: 48px; object-fit: contain; background: rgba(255,255,255,0.14); border-radius: 10px; padding: 6px; }
        .detail-head .title { margin: 0; font-weight: 800; letter-spacing: 0.2px; color: #ffffff; }
        .detail-head .subtitle { margin: 2px 0 0; font-size: 13px; color: #e6f6e8; }
        .detail-chip {
            background: rgba(255, 255, 255, 0.14);
            color: #f4fff5;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            letter-spacing: 0.4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .detail-body {
            background: #fff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Crect width='220' height='220' fill='none'/%3E%3Cpath d='M30 30c28 12 32 46 0 60s-28 46 0 60' fill='none' stroke='%23e3efe8' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M190 40c-26 10-30 42 0 56s26 42 0 56' fill='none' stroke='%23d7edde' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M110 0c14 24 14 48 0 72s-14 48 0 72s14 48 0 72' fill='none' stroke='%23e8f8e4' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.28'/%3E%3Ccircle cx='60' cy='140' r='10' fill='%23c7e9d1' opacity='0.28'/%3E%3Ccircle cx='160' cy='90' r='12' fill='%23cfeedd' opacity='0.25'/%3E%3Ccircle cx='120' cy='170' r='8' fill='%23b8e2c5' opacity='0.26'/%3E%3Cpath d='M70 200q10-12 20 0t20 0' fill='none' stroke='%23f7fff7' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.32'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 240px 240px;
            background-blend-mode: soft-light;
            padding: 18px 20px;
            position: relative;
            z-index: 1;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px 18px;
        }
        .detail-item {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #e9eef6;
            background: #f9fbfd;
        }
        .detail-label {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #6f7b8a;
            margin-bottom: 4px;
            display: block;
        }
        .detail-value {
            font-weight: 700;
            color: #1c2430;
        }
    </style>
<?php endif; ?>

<?php if (!empty($groupEntries)) : ?>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <h5><?php echo htmlspecialchars($student['nama'] ?? '-'); ?></h5>
                <div class="text-muted">NIM: <?php echo htmlspecialchars($student['nim'] ?? '-'); ?> &middot; Jurusan: <?php echo htmlspecialchars($student['jurusan'] ?? '-'); ?></div>
                <div class="mt-2">Semester: <strong><?php echo htmlspecialchars($semester); ?></strong> &middot; Tahun Ajaran: <strong><?php echo htmlspecialchars($tahun_ajaran); ?></strong></div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; $totalSks = 0; foreach ($groupEntries as $entry) : $totalSks += (int)($entry['sks'] ?? 0); ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($entry['kode_matkul'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['nama_matkul'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['sks'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['status'] ?? '-'); ?></td>
                                <td>
                                    <!-- Edit single entry -->
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('krs/update-krs') . '?id=' . urlencode($entry['id_krs']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <!-- Delete single entry -->
                                    <a class="btn btn-icon btn-outline-danger btn-sm" title="Hapus" href="<?php echo page_url('krs/delete-krs') . '?id=' . urlencode($entry['id_krs']); ?>">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total SKS</strong></td>
                            <td><strong><?php echo $totalSks; ?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
                <?php $deleteGroupUrl = page_url('krs/delete-krs') . '?id_mahasiswa=' . urlencode($id_mahasiswa) . '&semester=' . urlencode($semester) . '&tahun_ajaran=' . urlencode($tahun_ajaran); ?>
                <a href="<?php echo $deleteGroupUrl; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus semua entri KRS untuk mahasiswa ini pada semester dan tahun ajaran ini?');">Hapus Semua</a>
            </div>
        </div>
    </div>
<?php elseif (!$single) : ?>
    <div class="alert alert-warning" role="alert">Data KRS tidak ditemukan.</div>
    <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
<?php endif; ?>

    <div class="card-body">
        <?php if ($data) : ?>
            <div class="detail-card mb-3">
                <div class="detail-head">
                    <div class="detail-brand">
                        <img src="<?php echo $logoSrc; ?>" alt="Logo UNUHA" class="detail-logo">
                        <div>
                            <div class="subtitle">Kartu Rencana Studi</div>
                            <h5 class="title mb-0"><?php echo htmlspecialchars($data['nama'] ?? '-'); ?></h5>
                            <div class="subtitle">NIM: <?php echo htmlspecialchars($data['nim'] ?? '-'); ?></div>
                        </div>
                    </div>
                    <div class="detail-chip">KRS</div>
                </div>
                <div class="detail-body">
                    <div class="detail-grid mb-2">
                        <div class="detail-item">
                            <span class="detail-label">NIM</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['nim'] ?? '-'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jurusan</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['jurusan'] ?? '-'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Semester</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['semester']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tahun Ajaran</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['tahun_ajaran']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mata Kuliah</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['nama_matkul'] ?? '-'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Kode MK</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['kode_matkul'] ?? '-'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">SKS</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['sks'] ?? '-'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value"><?php echo htmlspecialchars($data['status']); ?></span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Pengisian</span>
                        <span class="detail-value"><?php echo htmlspecialchars($recordedAt); ?></span>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Data KRS tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
