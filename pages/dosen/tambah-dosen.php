<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Tambah Dosen</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Form Tambah Dosen</h5>
                <a href="<?= $base_url ?>dosen/dosen" class="btn btn-sm btn-secondary">
                    <i class='bx bx-arrow-back'></i> &nbsp; Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="<?= $base_url ?>pages/dosen/proses.php" method="POST">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="nidn">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Contoh: 0012345678" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="nip">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" placeholder="Diisi jika PNS (opsional)" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="nama_dosen">Nama Dosen</label>
                                <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" placeholder="Contoh: Dr. John Doe, S.Kom., M.Kom." required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Contoh: Jakarta" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                <input class="form-control" type="date" id="tanggal_lahir" name="tanggal_lahir" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" class="form-control" placeholder="Alamat lengkap"></textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="keahlian">Keahlian</label>
                                <input type="text" class="form-control" id="keahlian" name="keahlian" placeholder="Contoh: Web Development, Data Science" />
                            </div>
                            <div class="mb-3">
                                <label for="jabatan_akademik" class="form-label">Jabatan Akademik</label>
                                <select class="form-select" id="jabatan_akademik" name="jabatan_akademik">
                                    <option value="Asisten Ahli" selected>Asisten Ahli</option>
                                    <option value="Lektor">Lektor</option>
                                    <option value="Lektor Kepala">Lektor Kepala</option>
                                    <option value="Guru Besar">Guru Besar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                    <option value="" disabled selected>-- Pilih Pendidikan --</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <div class="input-group input-group-merge">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="email2" />
                                    <span class="input-group-text" id="email2">@example.com</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="no_hp">No. HP</label>
                                <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" />
                            </div>
                            <div class="mb-3">
                                <label for="status_dosen" class="form-label">Status Dosen</label>
                                <select class="form-select" id="status_dosen" name="status_dosen">
                                    <option value="Tetap" selected>Tetap</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="LB">Luar Biasa (LB)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
