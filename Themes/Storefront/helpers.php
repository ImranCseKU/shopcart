<?php

use Modules\Menu\MegaMenu\Menu;

if (! function_exists('resolve_theme_color')) {
    /**
     * Resolve color code by the given theme name.
     *
     * @param string $name
     * @return string
     */
    function resolve_theme_color($name)
    {
        $colors = [
            'theme-blue' => '#0068e1',
            'theme-violet' => '#783392',
            'theme-red' => '#e30047',
            'theme-sky-blue' => '#2ba1c0',
            'theme-marrs-green' => '#0a6f75',
            'theme-navy-blue' => '#31629f',
            'theme-pink' => '#f15497',
            'theme-black' => '#333645',
        ];

        return $colors[$name] ?? '#31629f';
    }
}

if (! function_exists('mega_menu_classes')) {
    function mega_menu_classes(Menu $menu, $type = 'category_menu')
    {
        $classes = [];

        if ($type === 'primary_menu') {
            array_push($classes, 'nav-item');
        }

        if ($menu->isFluid()) {
            array_push($classes, 'fluid-menu');
        } elseif ($menu->hasSubMenus()) {
            array_push($classes, 'dropdown', 'multi-level');
        }

        return implode(' ', $classes);
    }
}

if (! function_exists('products_view_mode')) {
    /**
     * Get the products view mode.
     *
     * @return string
     */
    function products_view_mode()
    {
        return request('viewMode', 'grid');
    }
}

if (! function_exists('order_status_badge_class')) {
    /**
     * Get the products view mode.
     *
     * @param string $status
     * @return string
     */
    function order_status_badge_class($status)
    {
        $classes = [
            'canceled' => 'badge-danger',
            'completed' => 'badge-success',
            'on_hold' => 'badge-warning',
            'pending_payment' => 'badge-warning',
            'refunded' => 'badge-danger',
        ];

        return $classes[$status] ?? 'badge-info';
    }
}

if (! function_exists('social_links')) {
    /**
     * Get the social links.
     *
     * @param string $status
     * @return string
     */
    function social_links()
    {
        return collect([
            'lab la-facebook' => setting('storefront_facebook_link'),
            'lab la-twitter' => setting('storefront_twitter_link'),
            'lab la-instagram' => setting('storefront_instagram_link'),
            'lab la-youtube' => setting('storefront_youtube_link'),
        ])->reject(function ($link) {
            return is_null($link);
        });
    }
}
