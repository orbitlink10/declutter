<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class HomepageContent
{
    private const SETTINGS_PREFIX = 'homepage.';

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'hero_title' => 'Declutter your space. Sell to your community.',
            'hero_description' => 'Declutter Kenya helps you list household items quickly and connect with nearby buyers. Share what you no longer use and give it a second life.',
            'hero_image_path' => '',
            'why_choose_title' => 'Why Choose Declutter Kenya',
            'why_choose_description' => 'List your household items quickly, reach nearby buyers, and close deals safely in your community.',
            'products_section_title' => 'Hot-Selling Products.',
            'home_page_content' => '<h2>Declutter Kenya Marketplace</h2><p>Declutter Kenya connects local sellers and buyers to give pre-owned household items a second life. Publish listings in minutes and discover quality items near you.</p>',
            'primary_cta_label' => 'Browse Listings',
            'auth_secondary_cta_label' => 'Post an Item',
            'guest_secondary_cta_label' => 'Create Account',
            'search_label' => 'Search by keyword',
            'search_placeholder' => 'e.g. sofa, fridge, bike',
            'category_filter_label' => 'Category',
            'search_button_label' => 'Search Listings',
            'categories_section_title' => 'Browse Categories',
            'categories_section_link_label' => 'See all listings',
            'categories_empty_state' => 'Categories will appear here once added.',
            'latest_section_title' => 'Latest Active Listings',
            'latest_section_link_label' => 'View all',
            'latest_empty_state' => 'No active listings yet.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        $defaults = self::defaults();

        if (! Schema::hasTable('site_settings')) {
            return $defaults;
        }

        try {
            $stored = SiteSetting::query()
                ->whereIn('key', array_map(self::toStorageKey(...), array_keys($defaults)))
                ->pluck('value', 'key');
        } catch (QueryException) {
            return $defaults;
        }

        $content = [];

        foreach ($defaults as $key => $defaultValue) {
            $storedValue = $stored->get(self::toStorageKey($key));
            $content[$key] = filled($storedValue) ? $storedValue : $defaultValue;
        }

        return $content;
    }

    /**
     * @param  array<string, string>  $values
     */
    public static function update(array $values): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $allowedKeys = array_keys(self::defaults());
        $now = Carbon::now();
        $upserts = [];

        foreach ($allowedKeys as $key) {
            if (! array_key_exists($key, $values)) {
                continue;
            }

            $upserts[] = [
                'key' => self::toStorageKey($key),
                'value' => trim((string) $values[$key]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($upserts === []) {
            return;
        }

        SiteSetting::query()->upsert($upserts, ['key'], ['value', 'updated_at']);
    }

    private static function toStorageKey(string $key): string
    {
        return self::SETTINGS_PREFIX.$key;
    }
}
