<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cache dengan "versi per grup".
 *
 * - remember(): ambil dari cache, kalau belum ada jalankan query lalu simpan.
 * - flush():    "hapus" satu grup dengan menaikkan versinya. Semua key lama
 *               otomatis tidak terpakai lagi (tidak perlu Cache::tags yang
 *               tidak didukung driver file/database).
 *
 * Grup yang dipakai:
 *   karya          -> beranda, /karya, profil publik (/u/{id})
 *   artikel        -> /artikel, /artikel/{slug}
 *   stat           -> dashboard admin & super admin
 *   komentar.{id}  -> komentar di halaman detail karya {id}
 */
class MuseumCache
{
    public const TTL      = 600; // 10 menit
    public const TTL_STAT = 300; // 5 menit

    public static function remember(string $group, string $key, Closure $callback, int $ttl = self::TTL): mixed
    {
        return Cache::remember(self::key($group, $key), $ttl, $callback);
    }

    public static function flush(string ...$groups): void
    {
        foreach ($groups as $group) {
            Cache::forever(self::versionKey($group), bin2hex(random_bytes(4)));
        }
    }

    private static function key(string $group, string $key): string
    {
        $version = Cache::rememberForever(self::versionKey($group), fn () => bin2hex(random_bytes(4)));

        return "museum.{$group}.{$version}.{$key}";
    }

    private static function versionKey(string $group): string
    {
        return "museum.version.{$group}";
    }
}
