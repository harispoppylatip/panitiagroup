<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $Nim
 * @property string $access_token
 * @property string $refresh_token
 * @property string|null $status_onoff
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereStatusOnoff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Datasikadmodel whereUpdatedAt($value)
 */
	class Datasikadmodel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $position Main photo atau side photo
 * @property string|null $image_url
 * @property string|null $alt_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroImage whereUpdatedAt($value)
 */
	class HeroImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $image_url
 * @property string|null $author
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamNews whereUpdatedAt($value)
 */
	class MakamNews extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\MakamType|null $makamType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamOrder query()
 */
	class MakamOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MakamOrder> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MakamType query()
 */
	class MakamType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $Status_id
 * @property string $Status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusPembayaranModel whereUpdatedAt($value)
 */
	class StatusPembayaranModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string|null $image_url
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereUpdatedAt($value)
 */
	class TeamMember extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $judul
 * @property string $deskripsi
 * @property string $mata_kuliah
 * @property string $deadline
 * @property string $status
 * @property string $prioritas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereMataKuliah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas wherePrioritas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereUpdatedAt($value)
 */
	class Tugas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $detail
 * @property string $gambar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|gambaran whereUpdatedAt($value)
 */
	class gambaran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $Nim_key
 * @property int $Saldo_Lebih
 * @property int $Utang_Anggota
 * @property string|null $Keterangan
 * @property string|null $Bukti_Pembayaran
 * @property int $Nominal_Bayar
 * @property string|null $Tanggal_Pembayaran
 * @property int $Status_Pembayaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StatusPembayaranModel $Status
 * @property-read \App\Models\Datasikadmodel $datasikad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereBuktiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereNimKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereNominalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereSaldoLebih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereStatusPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereTanggalPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|grubkas whereUtangAnggota($value)
 */
	class grubkas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $postingan
 * @property string $tanggal_upload
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost wherePostingan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|modelpost whereTanggalUpload($value)
 */
	class modelpost extends \Eloquent {}
}

namespace App\Models\payment{
/**
 * @property int $id
 * @property int $Iuran_Perminggu
 * @property int $Total_Saldo
 * @property int $Total_Masuk
 * @property int $Total_Keluar
 * @property int $Jumlah_belum_bayar
 * @property int $Jumlah_Sudah_bayar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereIuranPerminggu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereJumlahBelumBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereJumlahSudahBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereTotalKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereTotalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereTotalSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrubkasDashboard whereUpdatedAt($value)
 */
	class GrubkasDashboard extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $Nim
 * @property string $refresh_token
 * @property string $access_token
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|sikaddata whereRefreshToken($value)
 */
	class sikaddata extends \Eloquent {}
}

