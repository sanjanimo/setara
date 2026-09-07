# SETARA - Final QA Audit

Tanggal audit: 2026-09-07

## Ringkasan

Audit dilakukan dari `routes/web.php`, lalu ditelusuri ke controller, middleware, Form Request, model, Blade, JavaScript, migration, seeder, dan test.

Hasil runtime terbaru:

- 81 route terdaftar.
- 34 test lulus.
- 155 assertion lulus.
- Coverage terakhir: 82.5% dengan PCOV.
- Blade view berhasil dikompilasi.
- Test gatekeeping relawan lulus: relawan wajib menyelesaikan modul basic yang relevan sebelum mengajukan kegiatan.

## Yang Dicek

 Status: DIPERBAIKI pada audit lanjutan.
 Status: DIPERBAIKI pada audit lanjutan.
- Detail panti `GET /panti/{slug}` dan alias `GET /pantis/{slug}`.
- Login, register, logout.
 `resources/views/panti/show.blade.php`: copy stale “akan aktif pada tahap berikutnya” sudah diganti dengan copy fitur aktif.
 `resources/views/components/image-placeholder.blade.php`: default placeholder sudah dinetralisasi menjadi `VISUAL-SETARA` dan “Visual pendukung belum tersedia.”
 Map hanya mengambil panti verified dan memiliki koordinat, dengan masking koordinat approximate.
 Gap yang masih tersisa:
 Browser smoke test untuk memastikan location picker mempertahankan state pada browser nyata.
 Browser smoke test untuk Leaflet dan interaksi peta.
 Verifikasi upstream API timeout melalui integration environment nyata.
- CRUD kebutuhan.
- Donasi masuk dan status confirmation flow.
- Kunjungan relawan.
- Profil youth anonim.
- Laporan kunjungan.

### Alur relawan

- Dashboard.
- Modul basic dan advanced.
- Quiz dan pencatatan kelulusan.
- Pencarian panti.
- Gatekeeping sebelum pengajuan.
- Pengajuan kunjungan, mentor, dan bantuan logistik.
- Laporan kunjungan.

### Alur donatur

- Dashboard.
- Pengajuan donasi barang atau tenaga.
- Daftar donasi milik user.
- Profil donatur.

## Command QA

Command yang dijalankan:

```text
php artisan route:list --except-vendor
php artisan test
php artisan test tests\\Feature\\CoreFlowTest.php
php artisan view:cache
php artisan test --coverage
```

Hasil test akhir:

```text
34 tests passed
155 assertions
82.5% total application coverage
```

Catatan: test memakai database MySQL terpisah `db_setara_test`, sesuai `phpunit.xml`. Audit teks dilakukan dengan `Select-String` PowerShell karena `rg` tidak tersedia pada terminal Windows yang digunakan.

## Temuan Prioritas

### QA-001 - High: Koordinat exact tetap bocor ke public map

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `app/Http/Controllers/MapController.php` selalu mengirim `latitude` dan `longitude`.
- Field `location_precision` tersedia di panti dan dipakai pada form profil, tetapi tidak dipakai untuk menyamarkan koordinat public.
- Map public mengambil JSON tersebut dari `/peta/data`.

Dampak sebelum perbaikan: panti yang memilih lokasi approximate tetap mengirim koordinat exact ke browser.

Perubahan: `MapController` sekarang mengirim koordinat exact hanya untuk precision `exact`. Untuk precision `approximate`, latitude dan longitude dibulatkan ke 2 desimal sebelum dikirim ke public map.

Test: ditambahkan regression test untuk panti exact dan approximate di `tests/Feature/CoverageBatchTwoTest.php`.

### QA-002 - High: Landing page menghitung kebutuhan dari panti yang belum verified

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `routes/web.php` menghitung `$needCount` dari `PantiNeed::active()` tanpa `whereHas('panti', verified)`.
- `$waitingNeeds` juga mengambil kebutuhan aktif tanpa filter panti verified.
- Link dari kebutuhan panti pending/rejected dapat menuju detail public yang berakhir 404.

Dampak sebelum perbaikan: angka dan daftar kebutuhan public dapat menampilkan data yang tidak seharusnya public.

Perubahan: query `$needCount` dan `$waitingNeeds` di `routes/web.php` sekarang memakai `whereHas('panti', verified)`.

Test: ditambahkan regression test di `tests/Feature/CoverageBatchTest.php` untuk memastikan kebutuhan panti pending tidak muncul di landing.

### QA-003 - High: Edit profil panti dapat kehilangan data lokasi

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `resources/js/locationpicker.js` memulai `province_id`, `city_id`, `district_id`, latitude, dan longitude sebagai string kosong.
- State Alpine tidak di-hydrate dari panti existing.
- `onCityChange()` mengisi `cityName`, tetapi tidak mengisi `districtName`.

Dampak: edit profil yang tidak menyentuh lokasi dapat mereset state lokasi atau gagal saat data lokasi diwajibkan.

Perubahan: location picker menerima state lokasi existing dari Blade, memuat pilihan bertingkat berdasarkan data tersimpan, dan memusatkan map pada koordinat existing.

Test browser lanjutan masih direkomendasikan untuk interaksi dropdown dan map nyata.

### QA-004 - High: Admin dapat approve panti tanpa validasi consent/completeness

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `VerificationController@approve` langsung mengubah status menjadi `verified`.
- Tidak ada pemeriksaan `consent_agreement` atau field profile wajib pada action approval.

Dampak: data dapat dipublikasikan walau consent false atau profil belum lengkap.

Perubahan: approval menolak panti tanpa consent atau field profile wajib.

Test: regression test approval tanpa consent sudah ditambahkan.

### QA-005 - Medium: User nonaktif masih dapat memakai session lama

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- Login baru memeriksa `is_active` di `AuthController`.
- `EnsureUserHasRole` hanya memeriksa user ada dan role cocok, tidak memeriksa `is_active`.
- Admin dapat menonaktifkan user yang sedang login di sesi lain.

Dampak: deactivation tidak langsung mencabut akses session yang sudah ada.

Perubahan: role middleware memutus session user nonaktif, menginvalidasi session, dan mengembalikan error login.

### QA-006 - Medium: Relawan dapat POST quiz untuk modul unpublished

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `Relawan\\ModuleController@show` menolak modul unpublished.
- `Relawan\\ModuleController@attempt` tidak memeriksa `is_published` sebelum memproses POST.

Dampak: request langsung dapat membuat attempt untuk modul yang seharusnya tersembunyi.

Perubahan: `attempt()` sekarang menolak modul unpublished dengan 404.

### QA-007 - Medium: Youth yang dipilih untuk mentor belum divalidasi statusnya

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- Request hanya memastikan `youth_profile_id` milik panti yang dipilih.
- Tidak memeriksa `activity_type=mentor`.
- Tidak memeriksa `mentor_needed=true` dan `status=baru`.

Dampak: request crafted dapat menghubungkan pengajuan ke youth yang tidak meminta mentor atau sudah selesai didampingi.

Perubahan: Form Request memvalidasi ownership panti, activity mentor, `mentor_needed=true`, dan `status=baru`.

### QA-008 - Medium: Fallback API lokasi tidak memiliki timeout/error handling/cache/throttle

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `LocationController` memanggil `Http::get()` langsung ke API eksternal.
- Tidak ada `timeout`, `connectTimeout`, `failed`, fallback response terstruktur, cache, atau throttle.

Dampak: endpoint public dapat lambat, gagal 500, atau membebani upstream jika dipanggil berulang.

Perubahan: fallback memakai timeout, cache satu hari, fallback array kosong saat upstream gagal, dan throttle 60 request per menit.

### QA-009 - Medium: Kartu modul relawan dirender dua kali

Status: DIPERBAIKI pada audit lanjutan.

Bukti:

- `resources/views/dashboard/relawan/modules/partials/card.blade.php` berisi dua blok `<x-card>` lengkap berturut-turut.
- Keduanya membuat link ke modul yang sama.

Dampak: setiap modul tampil ganda dengan dua desain berbeda.

Perubahan: implementasi card duplikat dihapus dan satu card dipertahankan.

## Copy, Placeholder, dan Residue

### Teks user-facing yang perlu ditinjau

- `resources/views/panti/show.blade.php`: copy stale “akan aktif pada tahap berikutnya” sudah diganti dengan copy fitur aktif.
- `resources/views/auth/login.blade.php`: “Gunakan akun demo atau akun yang sudah kamu daftarkan.” Ini sesuai untuk kompetisi/demo, tetapi tidak ideal untuk production.
- `resources/views/components/image-placeholder.blade.php`: default placeholder sudah dinetralisasi menjadi `VISUAL-SETARA` dan “Visual pendukung belum tersedia.”
- `database/seeders/DemoSeeder.php`: “Data ini merupakan data simulasi untuk demo.” dan “Data simulasi untuk demo.” Ini wajar untuk seed kompetisi, tetapi harus jelas bahwa hanya seed data.
- `resources/views/home.blade.php` dan beberapa seed/data memuat bahasa demo/kompetisi. Tidak semuanya salah, tetapi perlu dibedakan dari copy production.

### Scaffold/internal residue

- `composer.json` masih memakai package name `laravel/laravel` dan description default Laravel.
- `config/app.php` memiliki fallback app name `Laravel` jika `APP_NAME` tidak tersedia.
- Komentar route seperti “Pengguna (semua route mengarah ke AdminUserController)” dan komentar implementasi lain bukan masalah runtime, tetapi bukan copy production.
- Tidak ditemukan indikasi teks “Lorem ipsum” atau `TODO/FIXME` yang tampil pada alur utama berdasarkan scan source.

### Placeholder dokumentasi

README perlu tetap diaudit terpisah sebelum submission karena placeholder URL/demo/repository/video dan username GitHub pernah ditemukan di sana. Audit ini tidak mengubah README selain perubahan bagian Testing yang sudah dilakukan sebelumnya.

## Flow yang Terverifikasi Sehat

- Landing route tersedia.
- Auth login/register/logout tersedia.
- Redirect dashboard per role tersedia.
- Public detail panti singular dan alias plural tersedia.
- Admin module CRUD dan module content route tersedia.
- Panti need ownership check berjalan.
- Donation ownership dan status transition berjalan.
- Volunteer application gatekeeping berjalan di UI dan server.
- Volunteer approval/rejection ownership check berjalan.
- Youth ownership check berjalan.
- API local location dan fallback mocked berjalan.
- Map hanya mengambil panti verified dan memiliki koordinat, dengan masking koordinat approximate.
- CSRF tersedia pada form mutating utama.
- Blade compile berhasil.

## Test Gap yang Masih Ada

Gap yang masih tersisa:

- Browser smoke test untuk memastikan location picker mempertahankan state pada browser nyata.
- Browser smoke test untuk Leaflet dan interaksi peta.
- Verifikasi upstream API timeout melalui integration environment nyata.

## Perubahan: Dari Apa Menjadi Apa

### Perubahan pada audit terakhir ini

Perbaikan QA-001 dilakukan setelah audit menemukan kebocoran koordinat:

- Sebelum: `MapController` selalu mengirim latitude/longitude exact.
- Sesudah: precision `exact` tetap exact; precision `approximate` dibulatkan ke 2 desimal.
- Test baru: membuktikan kedua perilaku tersebut.
- Sebelum: landing mengambil semua kebutuhan aktif tanpa filter status panti.
- Sesudah: landing hanya mengambil kebutuhan dari panti `verified`.
- Test baru: kebutuhan panti pending dipastikan tidak tampil di landing.

Aktivitas audit yang juga dilakukan:

- Membaca dan menelusuri route/controller/view terkait.
- Menjalankan route list.
- Menjalankan regression test.
- Mengompilasi Blade.
- Menjalankan scan teks placeholder/scaffold.
- Memperbarui dokumen ini.

### Perubahan yang sudah ada sebelum audit ini

Perubahan berikut terdeteksi sebagai bagian dari state project saat audit:

- Test database diarahkan ke `db_setara_test` dan Feature test memakai `RefreshDatabase`.
- Gatekeeping modul relawan dipindahkan dari sekadar UI menjadi pemeriksaan server-side pada endpoint pengajuan.
- Test suite bertambah hingga 29 test dan 135 assertion.
- Kolom `sort_order` ditambahkan ke `module_quizzes` melalui migration baru karena controller relawan mengurutkan quiz berdasarkan kolom tersebut.
- Coverage terukur mencapai 81.7%.

## Rekomendasi Urutan Perbaikan

1. Perbaiki privacy koordinat dan filter landing page.
2. Perbaiki hydration location picker saat edit profil.
3. Tambahkan approval guard untuk consent dan completeness.
4. Blokir user nonaktif dan module unpublished di semua endpoint.
5. Perbaiki validasi youth mentor.
6. Tambahkan timeout/cache/throttle untuk API location.
7. Hapus salah satu blok kartu modul relawan.
8. Bersihkan copy “akan aktif nanti” dan placeholder component.
9. Tambahkan regression test untuk seluruh temuan High/Medium.
10. Jalankan kembali `php artisan test --coverage` dan browser smoke test setelah perbaikan.
