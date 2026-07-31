<?php

namespace App\Http\Controllers;

use App\Models\News;

class SitemapController extends Controller
{
    /**
     * Sitemap XML dinamis: halaman statis utama + berita yang dipublikasikan.
     * Terdaftar di robots.txt dan dapat di-submit ke Google Search Console.
     */
    public function index()
    {
        // [path, changefreq, priority]
        $staticUrls = [
            ['/',                                'daily',   '1.0'],
            ['/jadwal-penerbangan',              'hourly',  '0.9'],
            ['/fasilitas',                       'monthly', '0.8'],
            ['/informasi/berita',                'daily',   '0.8'],
            ['/informasi-publik',                'monthly', '0.7'],
            ['/informasi-publik/profil-bandara', 'monthly', '0.7'],
            ['/informasi-publik/standar-pelayanan', 'monthly', '0.7'],
            ['/tautan-terkait',                    'monthly', '0.6'],
            ['/faq',                               'monthly', '0.8'],
            ['/lalu-lintas-angkutan',            'monthly', '0.6'],
            ['/kebijakan-privasi',               'yearly',  '0.3'],
        ];

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticUrls as [$path, $freq, $priority]) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e(url($path)) . "</loc>\n";
            $xml .= "    <changefreq>{$freq}</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        News::where('is_published', true)
            ->whereNotNull('slug')
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->each(function ($news) use (&$xml) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . e(url('/informasi/berita/' . $news->slug)) . "</loc>\n";
                if ($news->updated_at) {
                    $xml .= "    <lastmod>" . $news->updated_at->toAtomString() . "</lastmod>\n";
                }
                $xml .= "    <changefreq>weekly</changefreq>\n";
                $xml .= "    <priority>0.6</priority>\n";
                $xml .= "  </url>\n";
            });

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
