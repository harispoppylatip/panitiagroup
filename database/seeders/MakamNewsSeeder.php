<?php

namespace Database\Seeders;

use App\Models\MakamNews;
use Illuminate\Database\Seeder;

class MakamNewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'title' => 'Peresmian Makam Baru di TPU Karet Bivak',
                'content' => "Pemerintah Kota Jakarta meresmikan blok makam baru di TPU Karet Bivak pada hari Senin, 15 Juni 2026. Blok baru ini seluas 2 hektar dan mampu menampung hingga 1.500 makam.\n\n**Fasilitas Baru:**\n- Area parkir yang lebih luas\n- Taman memorial dengan air mancur\n- Jalur pejalan kaki yang ramah disabilitas\n- Tempat ibadah yang representatif\n\nPeresmian dilakukan langsung oleh Walikota Jakarta Pusat dan dihadiri oleh tokoh masyarakat setempat.",
                'author' => 'Humas Pemkot Jakarta',
                'published_at' => '2026-06-15 09:00:00',
            ],
            [
                'title' => 'Tradisi Nyekar Menjelang Ramadhan: Makna dan Tata Cara',
                'content' => "Menjelang bulan suci Ramadhan, tradisi nyekar atau ziarah kubur menjadi kegiatan yang banyak dilakukan oleh masyarakat Indonesia. Tradisi ini memiliki makna spiritual yang mendalam.\n\n**Makna Nyekar:**\n> Nyekar bukan sekadar tradisi, tetapi bentuk penghormatan kepada leluhur dan pengingat akan kefanaan hidup.\n\n**Tata Cara:**\n1. Membersihkan area makam dari rumput liar\n2. Menaburkan bunga di atas pusara\n3. Membaca doa dan tahlil\n4. Bersedekah kepada yang membutuhkan\n\nPara ahli agama menekankan bahwa esensi dari ziarah kubur adalah mendoakan almarhum dan mengambil pelajaran hidup.",
                'author' => 'Tim Redaksi',
                'published_at' => '2026-06-10 14:30:00',
            ],
            [
                'title' => 'Inovasi Pemakaman Vertikal: Solusi Lahan Terbatas',
                'content' => "Keterbatasan lahan di perkotaan mendorong inovasi dalam sistem pemakaman. Konsep pemakaman vertikal atau _vertical cemetery_ mulai diterapkan di beberapa kota besar di Indonesia.\n\n**Keunggulan Pemakaman Vertikal:**\n- Menghemat lahan hingga 70%\n- Biaya perawatan lebih rendah\n- Desain arsitektur yang modern\n- Ramah lingkungan\n\nKota Surabaya menjadi pelopor dengan membangun gedung pemakaman vertikal setinggi 5 lantai yang dilengkapi dengan sistem kremasi dan ruang serbaguna.\n\nPemerintah berencana mengembangkan konsep serupa di 10 kota metropolitan lainnya dalam 5 tahun ke depan.",
                'author' => 'Kontributor',
                'published_at' => '2026-06-05 10:00:00',
            ],
            [
                'title' => 'Panduan Lengkap Mengurus Izin Pemakaman',
                'content' => "Mengurus izin pemakaman seringkali menjadi hal yang membingungkan bagi keluarga yang baru kehilangan anggota keluarganya. Berikut panduan lengkap yang perlu Anda ketahui.\n\n**Dokumen yang Diperlukan:**\n1. Surat Keterangan Kematian dari dokter/rumah sakit\n2. KTP dan KK almarhum\n3. KTP ahli waris\n4. Surat pengantar dari RT/RW setempat\n\n**Prosedur:**\n> Proses pengurusan izin pemakaman umumnya memakan waktu 1-2 jam jika semua dokumen lengkap.\n\n**Biaya:**\n- Warga setempat: Gratis (dengan syarat domisili)\n- Non-warga: Rp 500.000 - Rp 2.000.000 tergantung lokasi\n\nPastikan untuk menghubungi pihak TPU setempat terlebih dahulu untuk informasi terkini.",
                'author' => 'Admin Makam',
                'published_at' => '2026-05-28 08:00:00',
            ],
            [
                'title' => 'Sejarah TPU Menteng Pulo: Makam Para Pahlawan',
                'content' => "TPU Menteng Pulo memiliki sejarah panjang sebagai tempat peristirahatan terakhir para pahlawan dan tokoh nasional Indonesia. Berdiri sejak tahun 1800-an, makam ini menyimpan banyak cerita sejarah.\n\n**Tokoh yang Dimakamkan:**\n- Pahlawan nasional dari berbagai daerah\n- Budayawan dan seniman terkenal\n- Tokoh pendidikan dan agama\n\n**Arsitektur:**\nMakam-makam kuno di TPU Menteng Pulo memiliki arsitektur khas kolonial dan tradisional yang masih terawat hingga kini. Beberapa makam bahkan telah ditetapkan sebagai cagar budaya.\n\nPemerintah DKI Jakarta terus melakukan revitalisasi kawasan ini agar tetap terjaga sebagai destinasi wisata sejarah dan edukasi.",
                'author' => 'Sejarawan Muda',
                'published_at' => '2026-05-20 11:00:00',
            ],
            [
                'title' => 'Tips Merawat Makam Keluarga Agar Tetap Terjaga',
                'content' => "Merawat makam keluarga adalah bentuk bakti kepada orang tua dan leluhur yang telah mendahului kita. Berikut tips perawatan makam yang baik dan benar.\n\n**Perawatan Rutin:**\n- _Bersihkan makam minimal 2 minggu sekali_\n- Potong rumput liar di sekitar area makam\n- Cat ulang nisan jika mulai pudar\n- Periksa kondisi struktur makam\n\n**Perawatan Musiman:**\n- Sebelum Ramadhan: bersihkan secara menyeluruh\n- Menjelang Idul Fitri: tanam bunga baru\n- Saat musim hujan: pastikan drainase tidak tersumbat\n\n> Makam yang terawat adalah cerminan kasih sayang keluarga yang masih hidup.\n\n**Layanan Profesional:**\nSaat ini sudah banyak jasa perawatan makam profesional dengan biaya mulai dari Rp 150.000 per bulan, termasuk pembersihan, penyiraman tanaman, dan pengecekan rutin.",
                'author' => 'Tim Makam',
                'published_at' => '2026-05-15 07:30:00',
            ],
        ];

        foreach ($news as $item) {
            MakamNews::create($item);
        }

        $this->command->info('Berhasil menambahkan ' . count($news) . ' berita dummy Makam.');
    }
}
