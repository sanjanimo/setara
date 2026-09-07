<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Donation;
use App\Models\Module;
use App\Models\ModuleAttempt;
use App\Models\ModuleLesson;
use App\Models\ModuleQuiz;
use App\Models\NeedCategory;
use App\Models\Panti;
use App\Models\PantiNeed;
use App\Models\User;
use App\Models\VisitReport;
use App\Models\VolunteerApplication;
use App\Models\YouthProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ==================================================
        // 1. USERS
        // ==================================================

        $admin = User::create([
            'name' => 'Admin SETARA',
            'email' => 'admin@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'phone' => '081200000001',
            'organization_name' => null,
            'is_active' => true,
        ]);

        $pantiCahayaUser = User::create([
            'name' => 'Pengelola Panti Anak Cahaya Dago',
            'email' => 'cahaya.dago@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PANTI,
            'phone' => '081200000011',
            'organization_name' => 'Panti Anak Cahaya Dago',
            'is_active' => true,
        ]);

        $pantiSenjaUser = User::create([
            'name' => 'Pengelola Panti Lansia Senja Damai',
            'email' => 'senja.damai@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PANTI,
            'phone' => '081200000012',
            'organization_name' => 'Panti Lansia Senja Damai',
            'is_active' => true,
        ]);

        $pantiHarapanUser = User::create([
            'name' => 'Pengelola Panti Anak Harapan Baru',
            'email' => 'harapan.baru@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PANTI,
            'phone' => '081200000013',
            'organization_name' => 'Panti Anak Harapan Baru',
            'is_active' => true,
        ]);

        $pantiBhaktiUser = User::create([
            'name' => 'Pengelola Panti Lansia Bhakti Insani',
            'email' => 'bhakti.insani@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PANTI,
            'phone' => '081200000014',
            'organization_name' => 'Panti Lansia Bhakti Insani',
            'is_active' => true,
        ]);

        $pantiPelitaUser = User::create([
            'name' => 'Pengelola Panti Campuran Pelita Bangsa',
            'email' => 'pelita.bangsa@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PANTI,
            'phone' => '081200000015',
            'organization_name' => 'Panti Campuran Pelita Bangsa',
            'is_active' => true,
        ]);

        $relawanSiti = User::create([
            'name' => 'Siti Rahma',
            'email' => 'relawan.siti@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RELAWAN,
            'phone' => '081200000021',
            'organization_name' => 'Komunitas Relawan Kampus',
            'is_active' => true,
        ]);

        $relawanDina = User::create([
            'name' => 'Dina Ayu',
            'email' => 'relawan.dina@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RELAWAN,
            'phone' => '081200000022',
            'organization_name' => 'Komunitas Peduli Anak',
            'is_active' => true,
        ]);

        $relawanRaka = User::create([
            'name' => 'Raka Pratama',
            'email' => 'relawan.raka@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RELAWAN,
            'phone' => '081200000023',
            'organization_name' => 'Relawan Logistik Bandung',
            'is_active' => true,
        ]);

        $donaturBudi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'donatur.budi@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DONATUR,
            'phone' => '081200000031',
            'organization_name' => null,
            'is_active' => true,
        ]);

        $donaturSari = User::create([
            'name' => 'Sari Wulandari',
            'email' => 'donatur.sari@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DONATUR,
            'phone' => '081200000032',
            'organization_name' => null,
            'is_active' => true,
        ]);

        $donaturAndi = User::create([
            'name' => 'Andi Nugroho',
            'email' => 'donatur.andi@setara.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DONATUR,
            'phone' => '081200000033',
            'organization_name' => null,
            'is_active' => true,
        ]);

        // ==================================================
        // 2. KATEGORI KEBUTUHAN
        // ==================================================

        $categoryData = [
            [
                'name' => 'Pangan',
                'slug' => 'pangan',
                'description' => 'Kebutuhan makanan pokok seperti beras, lauk pauk, dan bahan pangan lain.',
                'target' => NeedCategory::TARGET_UMUM,
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'description' => 'Kebutuhan kesehatan dasar seperti obat ringan, popok, dan perlengkapan kesehatan.',
                'target' => NeedCategory::TARGET_UMUM,
                'is_active' => true,
            ],
            [
                'name' => 'Sanitasi',
                'slug' => 'sanitasi',
                'description' => 'Kebutuhan kebersihan seperti sabun, alat mandi, dan perlengkapan sanitasi.',
                'target' => NeedCategory::TARGET_UMUM,
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Kebutuhan pendidikan seperti buku, alat tulis, dan perlengkapan belajar.',
                'target' => NeedCategory::TARGET_ANAK,
                'is_active' => true,
            ],
            [
                'name' => 'Keterampilan & Pelatihan',
                'slug' => 'keterampilan-pelatihan',
                'description' => 'Kebutuhan pelatihan keterampilan untuk anak usia produktif.',
                'target' => NeedCategory::TARGET_ANAK,
                'is_active' => true,
            ],
            [
                'name' => 'Nutrisi Lansia',
                'slug' => 'nutrisi-lansia',
                'description' => 'Kebutuhan nutrisi khusus untuk lansia.',
                'target' => NeedCategory::TARGET_LANSIA,
                'is_active' => true,
            ],
            [
                'name' => 'Aktivitas Lansia',
                'slug' => 'aktivitas-lansia',
                'description' => 'Kebutuhan aktivitas kognitif dan fisik ringan untuk lansia.',
                'target' => NeedCategory::TARGET_LANSIA,
                'is_active' => true,
            ],
            [
                'name' => 'Operasional Panti',
                'slug' => 'operasional-panti',
                'description' => 'Kebutuhan operasional harian panti.',
                'target' => NeedCategory::TARGET_UMUM,
                'is_active' => true,
            ],
        ];

        $categories = [];

        foreach ($categoryData as $category) {
            $categories[$category['slug']] = NeedCategory::create($category);
        }

        // ==================================================
        // 3. DATA PANTI
        // ==================================================

        $pantiCahaya = Panti::create([
            'user_id' => $pantiCahayaUser->id,
            'name' => 'Panti Anak Cahaya Dago',
            'slug' => 'panti-anak-cahaya-dago',
            'type' => Panti::TYPE_ANAK,
            'description' => 'Panti anak dengan kebutuhan pangan dan pendidikan yang sedang mendesak. Data ini merupakan data simulasi untuk demo.',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'address' => 'Alamat simulasi area Dago',
            'latitude' => -6.8875000,
            'longitude' => 107.6165000,
            'manager_name' => 'Pengelola Panti Cahaya Dago',
            'manager_phone' => '081200000011',
            'capacity' => 30,
            'total_residents' => 35,
            'children_count' => 30,
            'elderly_count' => 0,
            'staff_count' => 5,
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => 'Data simulasi untuk demo.',
            'logo_url' => null,
            'cover_url' => null,
            'consent_agreement' => true,
            'location_precision' => Panti::LOCATION_APPROXIMATE,
            'urgency_score' => 86,
            'urgency_status' => Panti::URGENCY_KRITIS,
            'urgency_calculated_at' => now(),
        ]);

        $pantiSenja = Panti::create([
            'user_id' => $pantiSenjaUser->id,
            'name' => 'Panti Lansia Senja Damai',
            'slug' => 'panti-lansia-senja-damai',
            'type' => Panti::TYPE_JOMPO,
            'description' => 'Panti lansia yang membutuhkan nutrisi dan perlengkapan kesehatan. Data ini merupakan data simulasi untuk demo.',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'address' => 'Alamat simulasi area Dago',
            'latitude' => -6.8823000,
            'longitude' => 107.6197000,
            'manager_name' => 'Pengelola Panti Senja Damai',
            'manager_phone' => '081200000012',
            'capacity' => 25,
            'total_residents' => 24,
            'children_count' => 0,
            'elderly_count' => 20,
            'staff_count' => 4,
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => 'Data simulasi untuk demo.',
            'logo_url' => null,
            'cover_url' => null,
            'consent_agreement' => true,
            'location_precision' => Panti::LOCATION_APPROXIMATE,
            'urgency_score' => 58,
            'urgency_status' => Panti::URGENCY_WASPADA,
            'urgency_calculated_at' => now(),
        ]);

        $pantiHarapan = Panti::create([
            'user_id' => $pantiHarapanUser->id,
            'name' => 'Panti Anak Harapan Baru',
            'slug' => 'panti-anak-harapan-baru',
            'type' => Panti::TYPE_ANAK,
            'description' => 'Panti anak yang membutuhkan dukungan belajar dan gizi. Data ini merupakan data simulasi untuk demo.',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'address' => 'Alamat simulasi area Dago',
            'latitude' => -6.8901000,
            'longitude' => 107.6123000,
            'manager_name' => 'Pengelola Panti Harapan Baru',
            'manager_phone' => '081200000013',
            'capacity' => 28,
            'total_residents' => 26,
            'children_count' => 22,
            'elderly_count' => 0,
            'staff_count' => 4,
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => 'Data simulasi untuk demo.',
            'logo_url' => null,
            'cover_url' => null,
            'consent_agreement' => true,
            'location_precision' => Panti::LOCATION_APPROXIMATE,
            'urgency_score' => 52,
            'urgency_status' => Panti::URGENCY_WASPADA,
            'urgency_calculated_at' => now(),
        ]);

        $pantiBhakti = Panti::create([
            'user_id' => $pantiBhaktiUser->id,
            'name' => 'Panti Lansia Bhakti Insani',
            'slug' => 'panti-lansia-bhakti-insani',
            'type' => Panti::TYPE_JOMPO,
            'description' => 'Panti lansia dengan kondisi kebutuhan relatif stabil. Data ini merupakan data simulasi untuk demo.',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'address' => 'Alamat simulasi area Dago',
            'latitude' => -6.8798000,
            'longitude' => 107.6215000,
            'manager_name' => 'Pengelola Panti Bhakti Insani',
            'manager_phone' => '081200000014',
            'capacity' => 30,
            'total_residents' => 22,
            'children_count' => 0,
            'elderly_count' => 18,
            'staff_count' => 4,
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => 'Data simulasi untuk demo.',
            'logo_url' => null,
            'cover_url' => null,
            'consent_agreement' => true,
            'location_precision' => Panti::LOCATION_APPROXIMATE,
            'urgency_score' => 25,
            'urgency_status' => Panti::URGENCY_AMAN,
            'urgency_calculated_at' => now(),
        ]);

        $pantiPelita = Panti::create([
            'user_id' => $pantiPelitaUser->id,
            'name' => 'Panti Campuran Pelita Bangsa',
            'slug' => 'panti-campuran-pelita-bangsa',
            'type' => Panti::TYPE_CAMPURAN,
            'description' => 'Panti campuran anak dan lansia dengan kebutuhan operasional dan pangan yang relatif stabil. Data ini merupakan data simulasi untuk demo.',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'address' => 'Alamat simulasi area Dago',
            'latitude' => -6.8846000,
            'longitude' => 107.6109000,
            'manager_name' => 'Pengelola Panti Pelita Bangsa',
            'manager_phone' => '081200000015',
            'capacity' => 40,
            'total_residents' => 34,
            'children_count' => 18,
            'elderly_count' => 12,
            'staff_count' => 6,
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => 'Data simulasi untuk demo.',
            'logo_url' => null,
            'cover_url' => null,
            'consent_agreement' => true,
            'location_precision' => Panti::LOCATION_APPROXIMATE,
            'urgency_score' => 18,
            'urgency_status' => Panti::URGENCY_AMAN,
            'urgency_calculated_at' => now(),
        ]);

        // ==================================================
        // 4. KEBUTUHAN PANTI
        // ==================================================

        $needBerasCahaya = PantiNeed::create([
            'panti_id' => $pantiCahaya->id,
            'need_category_id' => $categories['pangan']->id,
            'title' => 'Beras',
            'description' => 'Stok beras menipis dan hanya cukup untuk beberapa hari.',
            'unit' => 'kg',
            'quantity_needed' => 50,
            'current_stock' => 5,
            'stock_days_remaining' => 2,
            'priority' => PantiNeed::PRIORITY_KRITIS,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => 'Kebutuhan pangan utama.',
        ]);

        PantiNeed::create([
            'panti_id' => $pantiCahaya->id,
            'need_category_id' => $categories['sanitasi']->id,
            'title' => 'Perlengkapan Kebersihan Anak',
            'description' => 'Sabun, sampo, dan perlengkapan mandi anak.',
            'unit' => 'paket',
            'quantity_needed' => 30,
            'current_stock' => 8,
            'stock_days_remaining' => 5,
            'priority' => PantiNeed::PRIORITY_TINGGI,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiCahaya->id,
            'need_category_id' => $categories['pendidikan']->id,
            'title' => 'Buku Tulis dan Alat Belajar',
            'description' => 'Kebutuhan belajar anak usia sekolah.',
            'unit' => 'paket',
            'quantity_needed' => 30,
            'current_stock' => 12,
            'stock_days_remaining' => 14,
            'priority' => PantiNeed::PRIORITY_SEDANG,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        $needPopokSenja = PantiNeed::create([
            'panti_id' => $pantiSenja->id,
            'need_category_id' => $categories['kesehatan']->id,
            'title' => 'Popok Dewasa',
            'description' => 'Kebutuhan popok untuk lansia dengan keterbatasan mobilitas.',
            'unit' => 'pak',
            'quantity_needed' => 40,
            'current_stock' => 10,
            'stock_days_remaining' => 5,
            'priority' => PantiNeed::PRIORITY_TINGGI,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiSenja->id,
            'need_category_id' => $categories['nutrisi-lansia']->id,
            'title' => 'Susu dan Nutrisi Lansia',
            'description' => 'Nutrisi tambahan untuk lansia.',
            'unit' => 'kaleng',
            'quantity_needed' => 30,
            'current_stock' => 10,
            'stock_days_remaining' => 6,
            'priority' => PantiNeed::PRIORITY_TINGGI,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiSenja->id,
            'need_category_id' => $categories['aktivitas-lansia']->id,
            'title' => 'Alat Aktivitas Kognitif',
            'description' => 'Puzzle sederhana, kartu memori, dan alat aktivitas ringan.',
            'unit' => 'paket',
            'quantity_needed' => 10,
            'current_stock' => 2,
            'stock_days_remaining' => 20,
            'priority' => PantiNeed::PRIORITY_SEDANG,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        $needLaukHarapan = PantiNeed::create([
            'panti_id' => $pantiHarapan->id,
            'need_category_id' => $categories['pangan']->id,
            'title' => 'Lauk Pauk Bergizi',
            'description' => 'Kebutuhan lauk pauk untuk gizi anak.',
            'unit' => 'paket',
            'quantity_needed' => 40,
            'current_stock' => 15,
            'stock_days_remaining' => 7,
            'priority' => PantiNeed::PRIORITY_TINGGI,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiHarapan->id,
            'need_category_id' => $categories['pendidikan']->id,
            'title' => 'Buku Bacaan Anak',
            'description' => 'Buku bacaan ringan untuk mendukung literasi.',
            'unit' => 'buah',
            'quantity_needed' => 50,
            'current_stock' => 25,
            'stock_days_remaining' => 20,
            'priority' => PantiNeed::PRIORITY_SEDANG,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiBhakti->id,
            'need_category_id' => $categories['pangan']->id,
            'title' => 'Beras',
            'description' => 'Stok beras masih relatif aman.',
            'unit' => 'kg',
            'quantity_needed' => 40,
            'current_stock' => 35,
            'stock_days_remaining' => 21,
            'priority' => PantiNeed::PRIORITY_RENDAH,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiBhakti->id,
            'need_category_id' => $categories['kesehatan']->id,
            'title' => 'Obat Ringan dan Vitamin',
            'description' => 'Vitamin dan obat dasar untuk lansia.',
            'unit' => 'paket',
            'quantity_needed' => 20,
            'current_stock' => 8,
            'stock_days_remaining' => 15,
            'priority' => PantiNeed::PRIORITY_SEDANG,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiPelita->id,
            'need_category_id' => $categories['operasional-panti']->id,
            'title' => 'Perlengkapan Kebersihan Panti',
            'description' => 'Pembersih lantai, sabun cuci, dan alat kebersihan.',
            'unit' => 'paket',
            'quantity_needed' => 25,
            'current_stock' => 10,
            'stock_days_remaining' => 12,
            'priority' => PantiNeed::PRIORITY_SEDANG,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        PantiNeed::create([
            'panti_id' => $pantiPelita->id,
            'need_category_id' => $categories['pangan']->id,
            'title' => 'Beras',
            'description' => 'Kebutuhan beras untuk panti campuran.',
            'unit' => 'kg',
            'quantity_needed' => 45,
            'current_stock' => 30,
            'stock_days_remaining' => 18,
            'priority' => PantiNeed::PRIORITY_RENDAH,
            'status' => PantiNeed::STATUS_AKTIF,
            'fulfilled_at' => null,
            'note' => null,
        ]);

        // ==================================================
        // 5. MODUL PEMBEKALAN RELAWAN
        // ==================================================

        $moduleUmum = Module::create([
            'title' => 'Etika Berkunjung ke Panti',
            'slug' => 'etika-berkunjung-ke-panti',
            'target' => Module::TARGET_UMUM,
            'description' => 'Modul dasar etika berkunjung ke panti asuhan dan panti jompo.',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $moduleAnak = Module::create([
            'title' => 'Komunikasi dengan Anak',
            'slug' => 'komunikasi-dengan-anak',
            'target' => Module::TARGET_ANAK,
            'description' => 'Modul komunikasi dan aktivitas yang aman untuk anak.',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $moduleLansia = Module::create([
            'title' => 'Komunikasi dengan Lansia',
            'slug' => 'komunikasi-dengan-lansia',
            'target' => Module::TARGET_LANSIA,
            'description' => 'Modul komunikasi dan aktivitas ringan untuk lansia.',
            'is_published' => true,
            'sort_order' => 3,
        ]);

        $lessonsUmum = [
            [
                'title' => 'Datang dengan Izin dan Jadwal',
                'content' => 'Kunjungan ke panti harus dilakukan dengan izin dan jadwal yang jelas. Panti membutuhkan waktu untuk menyiapkan kegiatan dan menjaga kenyamanan warga panti.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Menghormati Privasi Warga Panti',
                'content' => 'Jangan mengambil foto, video, atau menyebarkan identitas anak maupun lansia tanpa izin. Privasi dan martabat warga panti harus dijaga.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Tidak Memberikan Janji Berlebihan',
                'content' => 'Relawan dan donatur hanya boleh menjanjikan bantuan yang benar-benar dapat dipenuhi. Janji yang tidak ditepati dapat melukai kepercayaan.',
                'sort_order' => 3,
            ],
        ];

        foreach ($lessonsUmum as $lesson) {
            ModuleLesson::create(array_merge($lesson, [
                'module_id' => $moduleUmum->id,
            ]));
        }

        $quizzesUmum = [
            [
                'question' => 'Sebelum berkunjung ke panti, hal yang sebaiknya dilakukan adalah?',
                'option_a' => 'Langsung datang tanpa pemberitahuan',
                'option_b' => 'Menghubungi dan membuat janji dengan panti',
                'option_c' => 'Menunggu di depan panti',
                'option_d' => 'Mengajak banyak orang tanpa izin',
                'correct_option' => 'b',
            ],
            [
                'question' => 'Jika ingin mengambil foto di panti, apa yang harus dilakukan?',
                'option_a' => 'Langsung mengambil foto',
                'option_b' => 'Meminta izin terlebih dahulu',
                'option_c' => 'Memotret diam-diam',
                'option_d' => 'Mengunggah ke media sosial tanpa izin',
                'correct_option' => 'b',
            ],
            [
                'question' => 'Saat berinteraksi dengan warga panti, relawan sebaiknya?',
                'option_a' => 'Memberikan janji sebanyak mungkin',
                'option_b' => 'Menghormati aturan dan privasi panti',
                'option_c' => 'Memaksa warga panti bercerita',
                'option_d' => 'Mengabaikan arahan pengelola',
                'correct_option' => 'b',
            ],
        ];

        foreach ($quizzesUmum as $quiz) {
            ModuleQuiz::create(array_merge($quiz, [
                'module_id' => $moduleUmum->id,
            ]));
        }

        $lessonsAnak = [
            [
                'title' => 'Gunakan Bahasa yang Sederhana',
                'content' => 'Berbicaralah dengan bahasa yang mudah dipahami, ramah, dan tidak merendahkan. Hindari pertanyaan yang dapat memicu trauma.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Aktivitas Aman dan Inklusif',
                'content' => 'Pilih aktivitas yang aman, inklusif, dan sesuai usia. Pastikan semua anak merasa dilibatkan tanpa dipaksa.',
                'sort_order' => 2,
            ],
        ];

        foreach ($lessonsAnak as $lesson) {
            ModuleLesson::create(array_merge($lesson, [
                'module_id' => $moduleAnak->id,
            ]));
        }

        $quizzesAnak = [
            [
                'question' => 'Jika anak tidak ingin bercerita, relawan sebaiknya?',
                'option_a' => 'Memaksa anak bercerita',
                'option_b' => 'Menghormati batasannya',
                'option_c' => 'Menanyakan hal pribadi berulang kali',
                'option_d' => 'Meninggalkan anak sendirian',
                'correct_option' => 'b',
            ],
            [
                'question' => 'Aktivitas untuk anak sebaiknya?',
                'option_a' => 'Aman, inklusif, dan sesuai usia',
                'option_b' => 'Mengandung risiko tinggi',
                'option_c' => 'Hanya untuk anak tertentu',
                'option_d' => 'Memaksa anak bersaing',
                'correct_option' => 'a',
            ],
        ];

        foreach ($quizzesAnak as $quiz) {
            ModuleQuiz::create(array_merge($quiz, [
                'module_id' => $moduleAnak->id,
            ]));
        }

        $lessonsLansia = [
            [
                'title' => 'Berbicara dengan Hormat dan Sabar',
                'content' => 'Berbicaralah dengan tempo yang nyaman, jelas, dan penuh hormat. Jangan menganggap lansia seperti anak kecil.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Aktivitas Ringan yang Aman',
                'content' => 'Aktivitas untuk lansia harus aman, tidak melelahkan, dan dapat melatih kognisi atau motorik ringan, seperti musik, cerita kenangan, atau permainan sederhana.',
                'sort_order' => 2,
            ],
        ];

        foreach ($lessonsLansia as $lesson) {
            ModuleLesson::create(array_merge($lesson, [
                'module_id' => $moduleLansia->id,
            ]));
        }

        $quizzesLansia = [
            [
                'question' => 'Saat berbicara dengan lansia, relawan sebaiknya?',
                'option_a' => 'Berbicara cepat agar efisien',
                'option_b' => 'Berbicara dengan hormat dan sabar',
                'option_c' => 'Mengabaikan respons lansia',
                'option_d' => 'Memotong pembicaraan',
                'correct_option' => 'b',
            ],
            [
                'question' => 'Aktivitas untuk lansia sebaiknya?',
                'option_a' => 'Berat dan kompetitif',
                'option_b' => 'Aman, ringan, dan menyenangkan',
                'option_c' => 'Memaksa lansia bergerak berlebihan',
                'option_d' => 'Tidak melibatkan lansia',
                'correct_option' => 'b',
            ],
        ];

        foreach ($quizzesLansia as $quiz) {
            ModuleQuiz::create(array_merge($quiz, [
                'module_id' => $moduleLansia->id,
            ]));
        }

        // ==================================================
        // 6. HASIL MODUL RELAWAN
        // ==================================================

        ModuleAttempt::create([
            'user_id' => $relawanSiti->id,
            'module_id' => $moduleUmum->id,
            'score' => 100,
            'passed' => true,
            'completed_at' => now(),
        ]);

        ModuleAttempt::create([
            'user_id' => $relawanSiti->id,
            'module_id' => $moduleAnak->id,
            'score' => 80,
            'passed' => true,
            'completed_at' => now(),
        ]);

        ModuleAttempt::create([
            'user_id' => $relawanDina->id,
            'module_id' => $moduleUmum->id,
            'score' => 100,
            'passed' => true,
            'completed_at' => now(),
        ]);

        ModuleAttempt::create([
            'user_id' => $relawanDina->id,
            'module_id' => $moduleAnak->id,
            'score' => 100,
            'passed' => true,
            'completed_at' => now(),
        ]);

        ModuleAttempt::create([
            'user_id' => $relawanRaka->id,
            'module_id' => $moduleUmum->id,
            'score' => 80,
            'passed' => true,
            'completed_at' => now(),
        ]);

        // ==================================================
        // 7. YOUTH PROFILE ANONIM
        // ==================================================

        $youthA = YouthProfile::create([
            'panti_id' => $pantiCahaya->id,
            'initials' => 'A',
            'age' => 16,
            'interests' => 'Desain grafis dasar',
            'skill_goals' => 'Mampu membuat poster kegiatan panti',
            'training_needs' => 'Mentor Canva atau desain dasar',
            'mentor_needed' => true,
            'status' => YouthProfile::STATUS_BARU,
            'note' => 'Data anonim untuk demo.',
        ]);

        YouthProfile::create([
            'panti_id' => $pantiCahaya->id,
            'initials' => 'R',
            'age' => 17,
            'interests' => 'Literasi digital',
            'skill_goals' => 'Belajar menulis konten positif',
            'training_needs' => 'Mentor literasi digital',
            'mentor_needed' => true,
            'status' => YouthProfile::STATUS_BARU,
            'note' => 'Data anonim untuk demo.',
        ]);

        YouthProfile::create([
            'panti_id' => $pantiHarapan->id,
            'initials' => 'N',
            'age' => 15,
            'interests' => 'Matematika dasar',
            'skill_goals' => 'Meningkatkan kemampuan belajar',
            'training_needs' => 'Mentor belajar',
            'mentor_needed' => true,
            'status' => YouthProfile::STATUS_BARU,
            'note' => 'Data anonim untuk demo.',
        ]);

        // ==================================================
        // 8. DONASI
        // ==================================================

        Donation::create([
            'panti_id' => $pantiCahaya->id,
            'panti_need_id' => $needBerasCahaya->id,
            'user_id' => $donaturBudi->id,
            'donor_name' => $donaturBudi->name,
            'donor_email' => $donaturBudi->email,
            'donor_phone' => $donaturBudi->phone,
            'type' => Donation::TYPE_BARANG,
            'quantity' => 20,
            'message' => 'Saya ingin membantu beras untuk kebutuhan panti.',
            'status' => Donation::STATUS_DIAJUKAN,
            'proof_url' => null,
            'note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        Donation::create([
            'panti_id' => $pantiSenja->id,
            'panti_need_id' => $needPopokSenja->id,
            'user_id' => $donaturSari->id,
            'donor_name' => $donaturSari->name,
            'donor_email' => $donaturSari->email,
            'donor_phone' => $donaturSari->phone,
            'type' => Donation::TYPE_BARANG,
            'quantity' => 15,
            'message' => 'Semoga membantu kebutuhan lansia.',
            'status' => Donation::STATUS_DIKONFIRMASI,
            'proof_url' => null,
            'note' => 'Panti bersedia menerima bantuan.',
            'reviewed_by' => $pantiSenjaUser->id,
            'reviewed_at' => now(),
        ]);

        Donation::create([
            'panti_id' => $pantiBhakti->id,
            'panti_need_id' => null,
            'user_id' => $donaturAndi->id,
            'donor_name' => $donaturAndi->name,
            'donor_email' => $donaturAndi->email,
            'donor_phone' => $donaturAndi->phone,
            'type' => Donation::TYPE_TENAGA,
            'quantity' => null,
            'message' => 'Saya ingin membantu distribusi logistik ke panti.',
            'status' => Donation::STATUS_SELESAI,
            'proof_url' => null,
            'note' => 'Kegiatan bantuan tenaga telah dilakukan.',
            'reviewed_by' => $pantiBhaktiUser->id,
            'reviewed_at' => now(),
        ]);

        Donation::create([
            'panti_id' => $pantiHarapan->id,
            'panti_need_id' => $needLaukHarapan->id,
            'user_id' => $donaturSari->id,
            'donor_name' => $donaturSari->name,
            'donor_email' => $donaturSari->email,
            'donor_phone' => $donaturSari->phone,
            'type' => Donation::TYPE_BARANG,
            'quantity' => 10,
            'message' => 'Ingin membantu lauk pauk.',
            'status' => Donation::STATUS_DITOLAK,
            'proof_url' => null,
            'note' => 'Mohon maaf, kebutuhan saat ini sudah terpenuhi.',
            'reviewed_by' => $pantiHarapanUser->id,
            'reviewed_at' => now(),
        ]);

        // ==================================================
        // 9. PENGAJUAN RELAWAN
        // ==================================================

        $applicationSiti = VolunteerApplication::create([
            'panti_id' => $pantiCahaya->id,
            'user_id' => $relawanSiti->id,
            'youth_profile_id' => null,
            'activity_type' => VolunteerApplication::ACTIVITY_KUNJUNGAN,
            'motivation' => 'Saya ingin membantu kegiatan belajar dan bermain edukatif untuk anak-anak.',
            'skills' => 'Mengajar anak, mendongeng, permainan edukatif.',
            'organization' => 'Komunitas Relawan Kampus',
            'availability' => 'Sabtu sore',
            'status' => VolunteerApplication::STATUS_SELESAI,
            'scheduled_date' => now()->subDays(2),
            'note' => 'Kunjungan telah selesai dan laporan dibuat.',
            'approved_by' => $pantiCahayaUser->id,
            'approved_at' => now()->subDays(3),
        ]);

        VolunteerApplication::create([
            'panti_id' => $pantiCahaya->id,
            'user_id' => $relawanDina->id,
            'youth_profile_id' => $youthA->id,
            'activity_type' => VolunteerApplication::ACTIVITY_MENTOR,
            'motivation' => 'Saya mahasiswa desain dan ingin menjadi mentor desain grafis dasar.',
            'skills' => 'Canva, desain poster dasar.',
            'organization' => 'Komunitas Peduli Anak',
            'availability' => 'Minggu pagi',
            'status' => VolunteerApplication::STATUS_DIAJUKAN,
            'scheduled_date' => null,
            'note' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        VolunteerApplication::create([
            'panti_id' => $pantiPelita->id,
            'user_id' => $relawanRaka->id,
            'youth_profile_id' => null,
            'activity_type' => VolunteerApplication::ACTIVITY_BANTUAN_LOGISTIK,
            'motivation' => 'Saya ingin membantu distribusi barang kebutuhan panti.',
            'skills' => 'Manajemen logistik, angkut barang, koordinasi relawan.',
            'organization' => 'Relawan Logistik Bandung',
            'availability' => 'Akhir pekan',
            'status' => VolunteerApplication::STATUS_DIAJUKAN,
            'scheduled_date' => null,
            'note' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // ==================================================
        // 10. LAPORAN KUNJUNGAN
        // ==================================================

        VisitReport::create([
            'panti_id' => $pantiCahaya->id,
            'user_id' => $relawanSiti->id,
            'volunteer_application_id' => $applicationSiti->id,
            'activity_date' => now()->subDays(2),
            'summary' => 'Relawan mengajar membaca dan bermain permainan edukatif bersama anak-anak.',
            'follow_up_needed' => true,
            'note' => 'Anak-anak membutuhkan buku bacaan ringan.',
        ]);

        // ==================================================
        // 11. ACTIVITY LOGS
        // ==================================================

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'panti.verified',
            'model_type' => Panti::class,
            'model_id' => $pantiCahaya->id,
            'description' => 'Admin memverifikasi Panti Anak Cahaya Dago.',
        ]);

        ActivityLog::create([
            'user_id' => $donaturBudi->id,
            'action' => 'donation.submitted',
            'model_type' => Donation::class,
            'model_id' => 1,
            'description' => 'Donatur Budi Santoso mengajukan niat bantuan beras.',
        ]);

        ActivityLog::create([
            'user_id' => $pantiSenjaUser->id,
            'action' => 'donation.confirmed',
            'model_type' => Donation::class,
            'model_id' => 2,
            'description' => 'Panti Lansia Senja Damai mengonfirmasi bantuan popok dewasa.',
        ]);

        ActivityLog::create([
            'user_id' => $relawanSiti->id,
            'action' => 'module.completed',
            'model_type' => Module::class,
            'model_id' => $moduleAnak->id,
            'description' => 'Relawan Siti Rahma menyelesaikan modul Komunikasi dengan Anak.',
        ]);

        ActivityLog::create([
            'user_id' => $pantiCahayaUser->id,
            'action' => 'volunteer.completed',
            'model_type' => VolunteerApplication::class,
            'model_id' => $applicationSiti->id,
            'description' => 'Kunjungan relawan Siti Rahma telah selesai.',
        ]);
    }
}
