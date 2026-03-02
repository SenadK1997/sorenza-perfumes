<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Generate and return the public sitemap (no dashboard, settings, or transactional pages).
     */
    public function __invoke()
    {
        $sitemap = Sitemap::create()
            ->add(
                Url::create(route('home', [], true))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            )
            ->add(
                Url::create(route('shop', [], true))
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            )
            ->add(
                Url::create(route('track.orders', [], true))
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );

        Perfume::query()
            ->select('id', 'updated_at')
            ->cursor()
            ->each(function (Perfume $perfume) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('products.show', [$perfume], true))
                        ->setLastModificationDate($perfume->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        return $sitemap;
    }
}
