# Schema Sneat (MySQL)

```sql
-- Dosen
CREATE TABLE IF NOT EXISTS dosen (
  id_dosen INT AUTO_INCREMENT PRIMARY KEY,
  nidn VARCHAR(30) NOT NULL UNIQUE,
  nip VARCHAR(30) NULL,
  nama_dosen VARCHAR(150) NOT NULL,
  jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
  tempat_lahir VARCHAR(100) NULL,
  tanggal_lahir DATE NULL,
  keahlian VARCHAR(150) NOT NULL,
  jabatan_akademik VARCHAR(100) NOT NULL DEFAULT 'Asisten Ahli',
  pendidikan_terakhir VARCHAR(100) NOT NULL,
  email VARCHAR(150) NULL,
  no_hp VARCHAR(30) NULL,
  alamat VARCHAR(255) NULL,
  status_dosen ENUM('Tetap','Kontrak','Luar') NOT NULL DEFAULT 'Tetap',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
  id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(150) NOT NULL,
  jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
  jurusan VARCHAR(150) NOT NULL,
  tahun_masuk YEAR NOT NULL,
  status ENUM('Aktif','Cuti','Lulus','DO') NOT NULL DEFAULT 'Aktif',
  tempat_lahir VARCHAR(100) NULL,
  tanggal_lahir DATE NULL,
  email VARCHAR(150) NULL,
  no_hp VARCHAR(30) NULL,
  alamat TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mata Kuliah
CREATE TABLE IF NOT EXISTS matakuliah (
  id_matkul INT AUTO_INCREMENT PRIMARY KEY,
  kode_matkul VARCHAR(30) NOT NULL UNIQUE,
  nama_matkul VARCHAR(200) NOT NULL,
  sks TINYINT UNSIGNED NOT NULL,
  semester TINYINT UNSIGNED NOT NULL,
  id_dosen INT NULL,
  jenis_matkul VARCHAR(100) NOT NULL,
  status ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  CONSTRAINT fk_matkul_dosen FOREIGN KEY (id_dosen) REFERENCES dosen(id_dosen),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kartu Rencana Studi (KRS)
CREATE TABLE IF NOT EXISTS krs (
  id_krs INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  id_matkul INT NOT NULL,
  semester TINYINT UNSIGNED NOT NULL,
  tahun_ajaran VARCHAR(9) NOT NULL, -- format: 2024/2025
  tanggal_pengisian DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Belum Disetujui','Disetujui','Ditolak') NOT NULL DEFAULT 'Belum Disetujui',
  CONSTRAINT fk_krs_mahasiswa FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa),
  CONSTRAINT fk_krs_matkul FOREIGN KEY (id_matkul) REFERENCES matakuliah(id_matkul),
  UNIQUE KEY uniq_krs (id_mahasiswa, id_matkul, semester, tahun_ajaran)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kartu Hasil Studi (KHS)
CREATE TABLE IF NOT EXISTS khs (
  id_khs INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  id_matkul INT NOT NULL,
  semester TINYINT UNSIGNED NOT NULL,
  tahun_ajaran VARCHAR(9) NOT NULL, -- format: 2024/2025
  nilai_angka DECIMAL(5,2) NOT NULL, -- 0-100
  nilai_huruf CHAR(2) NOT NULL,      -- A/B/C/D/E
  status ENUM('Lulus','Tidak Lulus','Remedial') NOT NULL DEFAULT 'Lulus',
  catatan VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_khs_mahasiswa FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa),
  CONSTRAINT fk_khs_matkul FOREIGN KEY (id_matkul) REFERENCES matakuliah(id_matkul),
  UNIQUE KEY uniq_khs (id_mahasiswa, id_matkul, semester, tahun_ajaran)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kartu Tanda Mahasiswa (KTM)
CREATE TABLE IF NOT EXISTS ktm (
  id_ktm INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  nomor_kartu VARCHAR(50) NOT NULL UNIQUE,
  tgl_terbit DATE NOT NULL,
  masa_berlaku DATE NOT NULL,
  status ENUM('Aktif','Tidak Aktif','Hilang','Rusak') NOT NULL DEFAULT 'Aktif',
  foto_kartu VARCHAR(255) NULL,
  keterangan VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_ktm_mahasiswa FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
