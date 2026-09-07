# DESIGN SYSTEM — SETARA

**Version:** 1.0.0  
**Status:** Approved for Implementation  
**Product:** SETARA — Sistem Terpadu Akses Relawan dan Donasi untuk Panti  
**Tagline:** Bantuan tepat, karena kebutuhan terlihat.

---

## 1. Design Philosophy

SETARA dirancang dengan prinsip:

> **Dignified Empathy & Warm Professionalism**

Artinya:

- Hangat, tetapi tidak cengeng.
- Manusiawi, tetapi tidak eksploitatif.
- Emosional, tetapi tetap berbasis data dan transparansi.
- Modern, tetapi tetap mudah dipahami oleh masyarakat umum.
- Profesional, tetapi tidak terasa dingin atau birokratis.

SETARA tidak boleh terlihat seperti website yang mengeksploitasi kesedihan panti atau warga panti.

SETARA harus terlihat seperti:

> platform kemanusiaan yang aman, terpercaya, etis, dan mampu mengarahkan bantuan secara tepat.

---

## 2. Core Emotional Keywords

| Keyword | Makna Visual |
|---|---|
| Hangat | Warna amber/oranye lembut, background off-white, radius membulat. |
| Terpercaya | Layout rapi, data jelas, badge verifikasi, konsistensi komponen. |
| Bermartabat | Tidak menampilkan identitas rentan secara sembarangan. |
| Transparan | Kebutuhan, status, urgensi, dan progres ditampilkan jelas. |
| Inklusif | Navigasi sederhana, teks jelas, kontras cukup, mobile-friendly. |
| Urgent but Calm | Status kritis terlihat jelas tanpa membuat panik berlebihan. |

---

## 3. Color Palette

### 3.1 Brand Colors

| Name | Hex | Tailwind Token | Meaning | Usage |
|---|---|---|---|---|
| Teal Forest | `#0F766E` | `--color-teal-forest` | Kepercayaan, pertumbuhan, keberlanjutan | Primary button, active state, navbar link active, primary icon |
| Teal Hover | `#0D655E` | `--color-teal-hover` | Primary interaction hover | Hover/focus primary button |
| Warm Amber | `#D97706` | `--color-warm-amber` | Kehangatan, relawan, energi sosial | Secondary CTA, highlight, badge relawan |
| Amber Hover | `#B45309` | `--color-amber-hover` | Hover secondary | Hover secondary button |

### 3.2 Urgency Colors

| Name | Hex | Token | Meaning | Usage |
|---|---|---|---|---|
| Urgency Red | `#DC2626` | `--color-urgency-red` | Kritis / butuh segera | Pin kritis, badge kritis, alert penting |
| Warning Yellow | `#F59E0B` | `--color-urgency-yellow` | Waspada | Badge waspada, pin waspada |
| Safe Green | `#16A34A` | `--color-urgency-green` | Aman / terpenuhi | Badge aman, success state |

### 3.3 Neutral Colors

| Name | Hex | Token | Usage |
|---|---|---|---|
| Warm Off-White | `#FAFAF9` | `--color-warm-bg` | Background utama |
| Warm Surface | `#FFFFFF` | `--color-warm-surface` | Card, modal, input |
| Stone Ink | `#0F172A` | `--color-stone-ink` | Heading, teks utama |
| Stone Gray | `#57534E` | `--color-stone-gray` | Subtitle, deskripsi, helper text |
| Border Soft | `#E7E5E4` | `--color-border-soft` | Border card, input, divider |

### 3.4 Color Ratio

Gunakan rasio visual:

```text
60% neutral / background
30% primary teal
10% amber / accent / urgency
```

Jangan menggunakan terlalu banyak warna kuat dalam satu layar.

---

## 4. Typography

### 4.1 Font Family

| Role | Font | Usage |
|---|---|---|
| Heading | Plus Jakarta Sans | H1, H2, H3, section title, navbar brand |
| Body | Inter | Paragraf, label form, table, dashboard text |

### 4.2 Font Weight

| Element | Weight |
|---|---:|
| H1 | 700 |
| H2 | 700 |
| H3 | 600 |
| Button | 500 |
| Body | 400 |
| Label | 500 |
| Badge | 600 |

### 4.3 Type Scale

| Element | Size |
|---|---|
| Hero H1 | `text-4xl` sampai `text-5xl` |
| Section H2 | `text-2xl` sampai `text-3xl` |
| Card Title | `text-lg` |
| Body | `text-sm` atau `text-base` |
| Small | `text-xs` |
| Badge | `text-xs uppercase tracking-wide` |

### 4.4 Typography Rules

- Jangan gunakan lebih dari 2 font.
- Jangan gunakan uppercase panjang untuk paragraf.
- Line height body minimal `leading-relaxed`.
- Heading harus jelas dan tidak terlalu panjang.
- Hindari teks berwarna abu terlalu terang untuk informasi penting.

---

## 5. Spacing & Layout

### 5.1 Base Grid

Gunakan kelipatan:

```text
4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px
```

### 5.2 Container

Gunakan:

```text
max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
```

### 5.3 Section Padding

| Section | Padding |
|---|---|
| Landing page section | `py-16` atau `py-24` |
| Dashboard section | `py-8` atau `py-10` |
| Card internal padding | `p-6` atau `p-8` |
| Form field gap | `space-y-4` atau `space-y-6` |

### 5.4 Whitespace Rule

Jangan takut ruang kosong.

Jika sebuah section terasa kosong, jangan langsung menambah gambar. Cek dulu:

- apakah hierarchy jelas?
- apakah copy terlalu panjang?
- apakah butuh icon?
- apakah butuh card?
- apakah butuh divider?
- apakah butuh data visual?

---

## 6. Shapes & Radius

| Element | Radius |
|---|---|
| Button | `rounded-lg` |
| Input | `rounded-lg` |
| Badge | `rounded-full` |
| Card | `rounded-2xl` |
| Modal | `rounded-3xl` |
| Image | `rounded-2xl` |
| Avatar | `rounded-full` |
| Dropdown | `rounded-xl` |

Hindari sudut tajam 90 derajat pada komponen utama.

---

## 7. Elevation & Shadow

Gunakan shadow halus.

| Element | Shadow |
|---|---|
| Card default | `shadow-sm` |
| Card hover | `shadow-md` |
| Dropdown | `shadow-lg` |
| Modal | `shadow-xl` |
| Floating map control | `shadow-md` |

Jangan gunakan shadow hitam pekat atau terlalu besar.

---

## 8. Component Standards

### 8.1 Button

Primary:

```text
bg-teal-forest text-white rounded-lg px-5 py-2.5
hover:bg-teal-hover
focus:ring-2 focus:ring-teal-forest focus:ring-offset-2
```

Secondary:

```text
bg-white text-teal-forest border border-teal-forest rounded-lg
hover:bg-teal-forest hover:text-white
```

Danger:

```text
bg-urgency-red text-white
```

Disabled:

```text
opacity-50 cursor-not-allowed
```

### 8.2 Card

Standar card:

```text
bg-warm-surface border border-border-soft rounded-2xl shadow-sm p-6
```

Card interaktif:

```text
hover:shadow-md transition-all duration-200
```

### 8.3 Badge

Standar badge:

```text
rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide
```

### 8.4 Input

Standar input:

```text
w-full rounded-lg border-gray-300 shadow-sm
focus:border-teal-forest focus:ring-teal-forest
```

Setiap input wajib punya label.

### 8.5 Empty State

Setiap daftar kosong wajib menampilkan:

- ikon relevan,
- judul singkat,
- deskripsi singkat,
- CTA jika perlu.

Contoh:

```text
Belum ada kebutuhan aktif.
Tambahkan kebutuhan agar donatur dapat melihat prioritas panti.
```

---

## 9. Imagery Policy

### 9.1 Prinsip Gambar

Gambar hanya boleh digunakan jika memenuhi minimal satu fungsi berikut:

1. Menjelaskan masalah.
2. Menunjukkan cara kerja.
3. Membangun kepercayaan.
4. Menampilkan dampak.
5. Mengarahkan aksi.

Jika tidak memenuhi fungsi tersebut, gunakan:

- whitespace,
- icon,
- pola halus,
- card data,
- ilustrasi abstrak.

### 9.2 Hal yang Dilarang

- Menampilkan close-up wajah anak panti tanpa consent.
- Menampilkan wajah lansia tanpa consent.
- Menampilkan identitas pribadi lengkap.
- Menampilkan alamat detail panti secara publik jika diset approximate.
- Menggunakan foto dramatis untuk memancing rasa kasihan.
- Menggunakan gambar yang tidak relevan dengan isi section.
- Menggunakan gambar berkualitas rendah atau pecah.
- Menggunakan gambar hanya untuk mengisi ruang kosong.

### 9.3 Arah Visual yang Disarankan

Gunakan:

- tangan yang sedang membantu,
- aktivitas dari samping atau belakang,
- objek seperti paket sembako, buku, alat tulis, laptop, puzzle,
- ilustrasi hangat dengan palette SETARA,
- peta dan kartu kebutuhan,
- dashboard blur atau UI mock.

### 9.4 Image Treatment

Semua gambar wajib:

- `rounded-2xl`,
- memiliki border tipis jika background terang,
- memiliki alt text deskriptif,
- menggunakan aspect ratio tetap,
- lazy loading,
- dioptimasi ukurannya.

### 9.5 Aspect Ratio

| Usage | Ratio |
|---|---|
| Hero | 4/3 atau 16/10 |
| Section banner | 16/9 |
| Card thumbnail | 4/3 |
| Square feature | 1/1 |
| Avatar | 1/1 |

### 9.6 Image Naming Convention

Gunakan format:

```text
IMG-[PAGE]-[SECTION]-[NUMBER]
```

Contoh:

```text
IMG-HOME-HERO-01
IMG-HOME-FLOW-01
IMG-HOME-RELAWAN-01
IMG-HOME-YOUTH-01
IMG-HOME-LANSIA-01
IMG-MAP-LEGEND-01
```

### 9.7 File Placement

Struktur gambar:

```text
public/images/
├── home/
├── map/
├── panti/
├── relawan/
├── youth/
├── lansia/
└── placeholders/
```

Format yang disarankan:

```text
.webp
```

Jika belum ada gambar asli, gunakan komponen `<x-image-placeholder>`.

---

## 10. Icon Policy

### 10.1 Icon Style

Gunakan ikon:

- outline,
- rounded stroke,
- stroke 1.5 atau 2,
- ukuran default 24px,
- warna mengikuti konteks.

### 10.2 Icon Colors

| Context | Color |
|---|---|
| Primary action | Teal Forest |
| Human/social accent | Warm Amber |
| Critical | Urgency Red |
| Success | Safe Green |
| Neutral | Stone Gray |

### 10.3 Icon Rules

- Jangan mencampur gaya ikon berbeda.
- Jangan gunakan emoji sebagai ikon utama UI profesional.
- Jangan gunakan terlalu banyak warna dalam satu icon group.
- Icon harus punya label atau tooltip jika berdiri sendiri.
- Ukuran tap target tetap minimal 44x44px jika icon menjadi tombol.

---

## 11. Tone of Voice

### 11.1 Pr Bahasa

Bahasa SETARA harus:

- bermartabat,
- tenang,
- jelas,
- tidak berlebihan,
- tidak mengasihani,
- mengajak,
- berbasis kebutuhan.

### 11.2 Do and Don't

| Jangan | Gunakan |
|---|---|
| Anak yatim piatu yang malang | Anak asuh / generasi panti |
| Panti miskin | Panti dengan urgensi tinggi |
| Mengemis bantuan | Membutuhkan dukungan |
| Menyelamatkan mereka | Mendampingi komunitas |
| Kasihanilah mereka | Mari bergotong royong |
| Sumbangkan uangmu sekarang | Lihat kebutuhan paling mendesak |

### 11.3 CTA Style

CTA harus berbasis aksi nyata.

Contoh baik:

```text
Lihat Peta Kebutuhan
Bantu Kebutuhan Ini
Daftar sebagai Relawan
Perbarui Kebutuhan Panti
Konfirmasi Bantuan
```

Hindari CTA yang terlalu agresif:

```text
Donasi Sekarang Juga!!!
Selamatkan Mereka!
Bantu Sebelum Terlambat!
```

---

## 12. Accessibility

Semua halaman wajib memperhatikan:

- kontras teks minimal AA,
- label form jelas,
- focus state terlihat,
- alt text gambar,
- tombol dapat diakses keyboard,
- tidak hanya mengandalkan warna,
- teks tidak terlalu kecil,
- mobile tap target cukup besar.

Contoh status tidak boleh hanya warna:

```text
Badge harus tetap punya teks: Kritis / Waspada / Aman
```

---

## 13. Page Quality Parameters

Sebuah halaman dianggap “siap” jika memenuhi parameter berikut:

| Parameter | Standard |
|---|---|
| First impression | User paham tujuan halaman dalam 3 detik. |
| Hierarchy | Judul, subtitle, CTA, dan konten utama jelas. |
| Spacing | Tidak sesak, ritme konsisten. |
| Consistency | Warna, radius, font, shadow sama. |
| Responsive | Rapi di mobile, tablet, desktop. |
| State | Ada loading, empty, error, success. |
| Trust | Ada indikator verifikasi, status, atau data yang jelas. |
| Ethics | Tidak menampilkan data/gambar sensitif. |
| Performance | Tidak berat, gambar optimized. |
| Purpose | Setiap elemen punya alasan keberadaan. |

---

## 14. Do's and Don'ts Global

### Do

- Gunakan background warm off-white.
- Gunakan card putih dengan border halus.
- Gunakan radius membulat.
- Gunakan copy yang bermartabat.
- Gunakan ikon outline konsisten.
- Gunakan badge urgensi dengan teks.
- Gunakan placeholder dengan brief jika gambar belum ada.

### Don't

- Jangan gunakan hitam murni untuk teks utama.
- Jangan gunakan terlalu banyak animasi.
- Jangan gunakan foto anak/lansia tanpa arah etis.
- Jangan gunakan gambar hanya untuk dekorasi kosong.
- Jangan gunakan warna merah berlebihan.
- Jangan membuat halaman terlalu ramai.
- Jangan menggunakan template instan atau gaya yang terlihat seperti theme marketplace generik.
```

---

# 2. Buat File `docs/UI_PAGE_MAP.md`

Buat folder:

```text
docs
```

Buat file:

```text
docs/UI_PAGE_MAP.md
```

Isi dengan dokumen berikut.

```markdown
# UI PAGE MAP — SETARA

Dokumen ini memetakan struktur halaman SETARA agar setiap page memiliki tujuan, hierarchy, CTA, image slot, icon slot, dan state yang jelas.

Status:

- Home: v1 approved for implementation.
- Auth: planned.
- Map: planned.
- Panti Detail: planned.
- Dashboard: planned.

---

## A. Global Layout Rules

### Container

```text
max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
```

### Section Spacing

```text
py-16 lg:py-24
```

### Section Title Pattern

Setiap section memiliki:

1. Eyebrow label kecil, opsional.
2. Judul utama.
3. Deskripsi singkat.
4. Konten utama.
5. CTA jika diperlukan.

Contoh:

```text
Eyebrow: Cara Kerja
Judul: SETARA membuat kebutuhan terlihat.
Deskripsi: Panti memperbarui kebutuhan, sistem menghitung urgensi, bantuan diarahkan ke tempat yang paling membutuhkan.
```
