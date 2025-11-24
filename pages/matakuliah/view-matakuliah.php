<?php
include 'config/koneksi.php';

$kodeMatkul = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$matakuliah = null;

if ($kodeMatkul !== '') {
    $stmt = $pdo->prepare(
        "SELECT m.*, d.nama_dosen
         FROM matakuliah m
         LEFT JOIN dosen d ON m.id_dosen = d.id_dosen
         WHERE m.kode_matkul = :kode_matkul
         LIMIT 1"
    );
    $stmt->execute([':kode_matkul' => $kodeMatkul]);
    $matakuliah = $stmt->fetch(PDO::FETCH_ASSOC);
}
$logoSrc = asset_url('public/assets/img/logo-unuha.png');
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mata Kuliah /</span> Detail Mata Kuliah</h4>

<?php if ($matakuliah) : ?>
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

<div class="card">
    <div class="card-body">
        <?php if ($matakuliah) : ?>
            <div class="detail-card mb-3">
                <div class="detail-head">
                    <div class="detail-brand">
                        <img src="<?php echo $logoSrc; ?>" alt="Logo UNUHA" class="detail-logo">
                        <div>
                            <div class="subtitle">Universitas Nurul Huda</div>
                            <h5 class="title mb-0"><?php echo htmlspecialchars($matakuliah['nama_matkul']); ?></h5>
                            <div class="subtitle">Kode: <?php echo htmlspecialchars($matakuliah['kode_matkul']); ?></div>
                        </div>
                    </div>
                    <div class="detail-chip">Mata Kuliah</div>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Kode</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['kode_matkul']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">SKS</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['sks']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Semester</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['semester']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jenis</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['jenis_matkul']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Dosen Pengampu</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['nama_dosen'] ?? 'Belum Ditentukan'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value"><?php echo htmlspecialchars($matakuliah['status']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning mb-3" role="alert">
                Data mata kuliah tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('matakuliah/matakuliah'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
