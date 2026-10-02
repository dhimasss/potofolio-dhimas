# Deploy ke Vercel

Satu deployment melayani **dua sisi** sekaligus:

- Publik → `https://<domain-anda>/`
- Admin  → `https://<domain-anda>/admin`

```
Pengunjung ──► Vercel (Laravel, PHP serverless, region Singapura)
                 ├──► TiDB Cloud  (MySQL online: projects, users, sessions, cache)
                 └──► Cloudflare R2 (file cover proyek)
```

Kenapa butuh dua layanan tambahan? Vercel **serverless**: tidak ada MySQL, dan file yang
di-upload ke server akan hilang. Jadi database dan gambar disimpan di layanan terpisah
(keduanya punya paket gratis).

---

## 1. Database MySQL online — TiDB Cloud (gratis)

1. Daftar di <https://tidbcloud.com> → buat cluster **Serverless/Starter**,
   region **AWS Singapore (ap-southeast-1)** (dekat dengan region Vercel `sin1`).
2. Klik **Connect** → pilih *General* → **Generate Password**. Catat:
   `HOST`, `PORT` (4000), `USERNAME`, `PASSWORD`.
3. Buka **SQL Editor**, jalankan:
   ```sql
   CREATE DATABASE portfolio_dhimas;
   ```

> Alternatif MySQL lain (Aiven, Railway, dll.) juga bisa — cukup ganti nilai `DB_*`.

## 2. Penyimpanan gambar — Cloudflare R2 (gratis 10 GB)

1. Daftar/login di <https://dash.cloudflare.com> → **R2 Object Storage** → **Create bucket**,
   nama misalnya `portfolio-media`.
2. Buka bucket → **Settings** → **Public Development URL** → **Enable**.
   Catat URL-nya (`https://pub-xxxx.r2.dev`) → ini `AWS_URL`.
3. Kembali ke R2 → **Manage API Tokens** → **Create API Token** → izin **Object Read & Write**,
   batasi ke bucket tadi. Catat **Access Key ID**, **Secret Access Key**, dan endpoint
   `https://<ACCOUNT_ID>.r2.cloudflarestorage.com` → ini `AWS_ENDPOINT`.

## 3. Isi database produksi (dari komputer Anda)

1. Salin `.env` menjadi **`.env.production`** (file ini otomatis diabaikan git), lalu ubah:
   ```dotenv
   DB_HOST=<host TiDB>
   DB_PORT=4000
   DB_DATABASE=portfolio_dhimas
   DB_USERNAME=<username TiDB>
   DB_PASSWORD=<password TiDB>
   MYSQL_ATTR_SSL_CA="D:\xampp\apache\bin\curl-ca-bundle.crt"

   ADMIN_PASSWORD=<password admin untuk situs online — buat yang kuat>
   ```
2. Jalankan migrasi + buat akun admin di database online:
   ```bash
   php artisan migrate --seed --force --env=production
   ```
   (Opsional, data contoh: `php artisan db:seed --class=ProjectSeeder --force --env=production`)

## 4. Upload kode ke GitHub

1. Buat repository **kosong** di <https://github.com/new> (misal `portfolio`, boleh *Private*).
2. Jalankan (ganti URL sesuai repo Anda):
   ```bash
   git remote add origin https://github.com/dhimasss/portfolio.git
   git push -u origin main
   ```

## 5. Deploy di Vercel

1. Login <https://vercel.com> dengan akun GitHub → **Add New… → Project** → import repo tadi.
2. **Framework Preset: Other**. Build & output settings biarkan (sudah diatur `vercel.json`).
3. Buka **Environment Variables**, tambahkan:

   | Nama | Nilai |
   |---|---|
   | `APP_KEY` | hasil `php artisan key:generate --show` (jalankan di komputer Anda) |
   | `APP_URL` | `https://<nama-project>.vercel.app` |
   | `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | dari langkah 1 |
   | `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` | dari langkah 2 |
   | `AWS_BUCKET` | `portfolio-media` |
   | `AWS_ENDPOINT` | `https://<ACCOUNT_ID>.r2.cloudflarestorage.com` |
   | `AWS_URL` | `https://pub-xxxx.r2.dev` |

   Variabel lain (path `/tmp`, SSL, `MEDIA_DISK=s3`, dll.) sudah ada di `vercel.json`.
4. Klik **Deploy**. Setelah selesai, buka:
   - `https://<nama-project>.vercel.app` → halaman publik
   - `https://<nama-project>.vercel.app/admin` → login dengan `ADMIN_EMAIL` + `ADMIN_PASSWORD` dari `.env.production`

Bila nama domain berbeda dari `APP_URL`, perbarui `APP_URL` lalu **Redeploy**.

## Update berikutnya

Cukup:
```bash
git add -A
git commit -m "Pesan perubahan"
git push
```
Vercel otomatis build & deploy ulang. Bila ada migration baru, jalankan lagi
`php artisan migrate --force --env=production` dari komputer Anda.

## Catatan

- Proyek & gambar di XAMPP lokal **tidak ikut pindah** — tambahkan ulang lewat admin online.
- Ganti password admin online: ubah `ADMIN_PASSWORD` di `.env.production`, lalu
  `php artisan db:seed --class=AdminUserSeeder --force --env=production`.
- Error 500? Lihat **Vercel → Project → Logs** (log Laravel dikirim ke sana lewat `stderr`).
