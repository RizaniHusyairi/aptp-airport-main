<?php

use Carbon\Carbon;
use GuzzleHttp\Psr7\Uri;

if (!function_exists("singular")) {
    //make title singuler
    function singular(string $title)
    {
        return substr($title, 0, -1);
    }
}

if (!function_exists("airportName")) {
    //make airport name bold
    function airportName(string $airport)
    {
        $preFix = substr($airport, 0, -7);
        $postFix = substr($airport, -7);
        return '<span class="text-info">' . $preFix . '</span>' . $postFix;
    }
}

if (!function_exists("getFile")) {
    function getFile($model)
    {
        Log::info($model->getFirstMedia());
        return $model->getFirstMedia() ? asset($model->getFirstMedia()->getUrl()) : URL::asset('assets/images/placeholder.png');
    }
}

if (!function_exists("getAvatar")) {
    function getAvatar($user)
    {
        return $user->getFirstMedia() ? asset($user->getFirstMedia()->getUrl()) : 'https://ui-avatars.com/api/?background=random&name=' . $user->name;
    }
}

if (!function_exists("getStatusColor")) {
    function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'warning';
                break;

            case 'approved':
                return 'success';
                break;

            case 'cancelled':
                return 'danger';
                break;
            default:
                return 'dark';
                break;
        }
    }
}

//return error message with file name and line number
if (!function_exists("showErrorMessage")) {
    function showErrorMessage($e)
    {
        // check env if its not in production, then show full message
        if (config('app.env') != 'production') {
            return $th->getMessage() . " in " . $e->getFile() . " at line " . $e->getLine();
        } else {
            return $th->getMessage();
        }
    }
}

if (!function_exists("getAvatar")) {
    function getAvatar($user)
    {
        return $user->getFirstMedia() ? asset($user->getFirstMedia()->getUrl()) : 'https://ui-avatars.com/api/?background=random&name=' . $user->name;
    }
}

if (!function_exists("getDayDiff")) {
    function getDayDiff($data)
    {
        $expire_at = Carbon::parse($data);
        $now = Carbon::now();
        $diff = $now->diffInDays($expire_at, false);
        return $diff;
    }
}

if (!function_exists("formatPrice")) {
    function formatPrice($price)
    {
        return number_format($price, 0, '') . ' $';
    }
}

if (!function_exists("formatDate")) {
    function formatDate($date)
    {
        return Carbon::parse($date)->format("M d, Y");
    }
}

if (!function_exists("formatDateWithTimezone")) {
    function formatDateWithTimezone($date)
    {
        return Carbon::parse($date)->format("M d, Y - h:i a");
    }
}

if (!function_exists("skmSetting")) {
    /**
     * Pengaturan tautan Survei Kepuasan Masyarakat (SKM).
     * Di-cache karena dipakai di footer yang dirender pada setiap halaman publik.
     * Cache dibersihkan saat admin menyimpan lewat Admin\SkmSettingController.
     */
    function skmSetting()
    {
        return Illuminate\Support\Facades\Cache::rememberForever('skm_setting', function () {
            $settings = App\Models\Setting::whereIn('key', ['skm_url', 'skm_label', 'skm_is_active'])
                ->pluck('value', 'key');

            return [
                'url' => $settings['skm_url'] ?? null,
                'label' => $settings['skm_label'] ?? 'Isi Survei Kepuasan Masyarakat',
                // URL kosong ikut dianggap tidak aktif agar tidak pernah muncul href=""
                'active' => ($settings['skm_is_active'] ?? '0') === '1' && !empty($settings['skm_url']),
            ];
        });
    }
}

if (!function_exists("externalLinks")) {
    /**
     * Tautan terkait aktif, dikelompokkan per kolom `group`.
     * Di-cache karena dipakai di footer dan menu navbar yang dirender pada
     * setiap halaman. Cache dibersihkan oleh Admin\ExternalLinkController
     * pada store(), update(), dan destroy().
     *
     * Sengaja mengembalikan array biasa (bukan model Eloquent) agar tidak ada
     * masalah serialisasi cache dan blade-nya tetap sederhana.
     */
    function externalLinks()
    {
        return Illuminate\Support\Facades\Cache::rememberForever('external_links', function () {
            return App\Models\ExternalLink::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($link) {
                    return [
                        'name' => $link->name,
                        'url' => $link->url,
                        'description' => $link->description,
                        'icon' => $link->icon ?: 'bi-box-arrow-up-right',
                        'logo' => $link->logo_url,
                        'group' => $link->group,
                    ];
                })
                // Urutan kelompok mengikuti kemunculan pertama pada hasil yang
                // sudah diurutkan berdasarkan sort_order.
                ->groupBy('group')
                ->toArray();
        });
    }
}

if (!function_exists("orderClass")) {
    function orderClass($status)
    {
        switch ($status) {
            case 'ordered':
                $class = "primary";
                break;
            case 'accepted':
                $class = "success";
                break;
            case 'canceled':
                $class = "danger";
                break;

            default:
                $class = "primary";
                break;
        }

        return $class;
    }
}
