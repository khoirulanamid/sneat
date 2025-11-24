<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT ktm.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan
         FROM ktm
         LEFT JOIN mahasiswa ON ktm.id_mahasiswa = mahasiswa.id_mahasiswa
         WHERE id_ktm = :id"
    );
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KTM /</span> Detail KTM</h4>

<div class="card">
    <div class="card-body">
        <?php if ($data) : ?>
            <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                <h5 class="mb-0">Detail KTM</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm" id="btn-print-ktm">Cetak PDF</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-print-ktm-paper">Cetak Printer</button>
                </div>
            </div>

            <?php
            $terbit = !empty($data['tgl_terbit']) ? date('d/m/Y', strtotime($data['tgl_terbit'])) : '-';
            $berlaku = !empty($data['masa_berlaku']) ? date('d/m/Y', strtotime($data['masa_berlaku'])) : '-';
            $fotoSrc = !empty($data['foto_kartu'])
                ? htmlspecialchars($data['foto_kartu'])
                : asset_url('public/assets/img/illustrations/undraw_profile.svg');
            $logoSrc = asset_url('public/assets/img/logo-unuha.png');
            ?>

            <style id="ktm-print-style">
                /* Kartu KTM khusus halaman ini */
                .ktm-card-wrapper {
                    background: #f1f7f1;
                    padding: 16px;
                    border-radius: 12px;
                }
                body.custom-dark .ktm-card-wrapper {
                    background: #0b1220;
                }
                .ktm-card {
                    max-width: 780px;
                    margin: 0 auto;
                    border-radius: 14px;
                    overflow: hidden;
                    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
                    background: linear-gradient(135deg, #0b5129, #0f6a34 55%, #13873f);
                    color: #e6f6e8;
                    position: relative;
                    width: 100%;
                    max-width: 780px;
                }
                .ktm-card, .ktm-card * {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .ktm-card::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Crect width='220' height='220' fill='none'/%3E%3Cpath d='M30 30c28 12 32 46 0 60s-28 46 0 60' fill='none' stroke='%23e3f5df' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M190 40c-26 10-30 42 0 56s26 42 0 56' fill='none' stroke='%23d2f2d2' stroke-width='5' stroke-linecap='round' stroke-linejoin='round' opacity='0.35'/%3E%3Cpath d='M110 0c14 24 14 48 0 72s-14 48 0 72s14 48 0 72' fill='none' stroke='%23f0fff0' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.28'/%3E%3Ccircle cx='60' cy='140' r='10' fill='%23b7e4c7' opacity='0.35'/%3E%3Ccircle cx='160' cy='90' r='12' fill='%23c6f2c1' opacity='0.3'/%3E%3Ccircle cx='120' cy='170' r='8' fill='%23a9dbb4' opacity='0.32'/%3E%3Cpath d='M70 200q10-12 20 0t20 0' fill='none' stroke='%23f7fff7' stroke-width='4' stroke-linecap='round' stroke-linejoin='round' opacity='0.4'/%3E%3C/svg%3E");
                    background-repeat: repeat;
                    background-size: 280px 280px;
                    opacity: 0.4;
                    mix-blend-mode: overlay;
                    pointer-events: none;
                }
                .ktm-card-header {
                    padding: 16px 20px;
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    backdrop-filter: blur(4px);
                    gap: 12px;
                    position: relative;
                }
                .ktm-card-header .brand {
                    font-weight: 700;
                    letter-spacing: 0.5px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }
                .ktm-brand-text {
                    display: flex;
                    flex-direction: column;
                    line-height: 1.1;
                }
                .ktm-brand-text .campus {
                    font-size: 15px;
                    font-weight: 600;
                    color: #d8f3da;
                }
                .ktm-brand-text .title {
                    font-size: 20px;
                    font-weight: 800;
                    color: #ffffff;
                }
                .ktm-chip {
                    background: rgba(255, 255, 255, 0.15);
                    padding: 6px 14px;
                    border-radius: 999px;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                }
                .ktm-card-body {
                    display: grid;
                    grid-template-columns: 220px 1fr;
                    gap: 18px;
                    padding: 18px 20px 6px;
                    background: rgba(255, 255, 255, 0.05);
                    backdrop-filter: blur(4px);
                    position: relative;
                }
                .ktm-photo {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.18);
                    border-radius: 12px;
                    padding: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    aspect-ratio: 3 / 4;
                }
                .ktm-photo img {
                    width: auto;
                    height: auto;
                    max-width: 100%;
                    max-height: 100%;
                    object-fit: contain;
                    border-radius: 10px;
                    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
                }
                .ktm-info {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px 18px;
                }
                .ktm-field {
                    padding: 10px 12px;
                    border-radius: 10px;
                    background: rgba(255, 255, 255, 0.08);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                }
                .ktm-label {
                    display: block;
                    font-size: 11px;
                    letter-spacing: 0.3px;
                    opacity: 0.8;
                    margin-bottom: 3px;
                }
                .ktm-value {
                    font-size: 15px;
                    font-weight: 600;
                    line-height: 1.2;
                }
                .ktm-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 18px 16px;
                    background: rgba(0, 0, 0, 0.08);
                    position: relative;
                }
                .ktm-status {
                    padding: 6px 12px;
                    border-radius: 10px;
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.16);
                    font-weight: 600;
                    letter-spacing: 0.4px;
                }
                @media (max-width: 768px) {
                    .ktm-card-body { grid-template-columns: 1fr; }
                    .ktm-info { grid-template-columns: 1fr; }
                }
                @media print {
                    body {
                        background: #f1f7f1;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                    .ktm-card {
                        box-shadow: none;
                    }
                    .ktm-card::before {
                        opacity: 0.3;
                        mix-blend-mode: soft-light;
                    }
                }
            </style>

            <div id="ktm-print-area" class="ktm-card-wrapper mb-4">
                <div class="ktm-card">
                    <div class="ktm-card-header">
                        <div class="brand">
                            <img src="<?php echo $logoSrc; ?>" alt="Logo UNUHA" style="height: 56px; width: auto; border-radius: 6px; background: rgba(255,255,255,0.12); padding: 6px;">
                            <div class="ktm-brand-text">
                                <span class="campus">Universitas Nurul Huda</span>
                                <span class="title">Kartu Tanda Mahasiswa</span>
                            </div>
                        </div>
                        <div class="ktm-chip">UNUHA</div>
                    </div>
                    <div class="ktm-card-body">
                        <div class="ktm-photo">
                            <img src="<?php echo $fotoSrc; ?>" alt="Foto KTM">
                        </div>
                        <div class="ktm-info">
                            <div class="ktm-field">
                                <span class="ktm-label">Nama</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['nama'] ?? '-'); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">NIM</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['nim'] ?? '-'); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Nomor KTM</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['nomor_kartu']); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Jurusan</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['jurusan'] ?? '-'); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Terbit</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($terbit); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Berlaku s.d</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($berlaku); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Status</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['status']); ?></span>
                            </div>
                            <div class="ktm-field">
                                <span class="ktm-label">Keterangan</span>
                                <span class="ktm-value"><?php echo htmlspecialchars($data['keterangan'] ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="ktm-footer">
                        <div>Universitas / Institut</div>
                        <div class="ktm-status"><?php echo htmlspecialchars($data['status']); ?></div>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Data KTM tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<?php if ($data) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btn-print-ktm');
            const btnPrintPaper = document.getElementById('btn-print-ktm-paper');
            const area = document.getElementById('ktm-print-area');
            const styleTag = document.getElementById('ktm-print-style');
            if (!btn || !area) return;

            btn.addEventListener('click', function() {
                if (typeof html2pdf === 'undefined') {
                    alert('Cetak belum siap. Muat ulang halaman lalu coba lagi.');
                    return;
                }
                const opt = {
                    margin: 0.4,
                    filename: 'ktm-<?php echo htmlspecialchars($data['nim'] ?? $data['nomor_kartu']); ?>.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
                };
                html2pdf().set(opt).from(area).save();
            });

            if (btnPrintPaper) {
                btnPrintPaper.addEventListener('click', function() {
                    const styleContent = styleTag ? styleTag.innerHTML : '';
                    const win = window.open('', '_blank', 'width=1000,height=700');
                    if (!win) {
                        alert('Popup diblokir. Izinkan popup untuk mencetak.');
                        return;
                    }
                    const html = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="utf-8">
                            <title>Cetak KTM</title>
                            <style>body{margin:16px;font-family:'Segoe UI',Arial,sans-serif;background:#f6f6f6;}${styleContent}</style>
                        </head>
                        <body>
                            ${area.outerHTML}
                        </body>
                        </html>
                    `;
                    win.document.open();
                    win.document.write(html);
                    win.document.close();
                    win.focus();
                    win.print();
                    setTimeout(() => win.close(), 500);
                });
            }
        });
    </script>
<?php endif; ?>
