<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class LogoIcon
{
    use InstanceCreator;

    public function logoIcon()
    {
        return [
            'logo' => $this->resolve(settings('tenant_logo', 'app_logo'), '/sk-assets/assets/images/frontend/front-logo.png'),
            'icon' => $this->resolve(settings('tenant_icon', 'app_icon'), '/sk-assets/assets/images/frontend/favicon.png')
        ];
    }

    /**
     * A stored path can outlive the file it points at, so fall back to the
     * bundled default rather than rendering a broken image.
     */
    protected function resolve($path, $default)
    {
        // Legacy PayDay defaults → StorageKeys website branding
        $legacyDefaults = [
            '/images/logo.png',
            'images/logo.png',
            '/images/icon.png',
            'images/icon.png',
            '/images/core.png',
            'images/core.png',
            '/images/logo/default-logo.png',
            'images/logo/default-logo.png',
        ];

        if (!empty($path) && in_array(ltrim((string) $path, '/'), array_map(static fn ($p) => ltrim($p, '/'), $legacyDefaults), true)) {
            $path = null;
        }

        if (empty($path) || !is_file(public_path(ltrim($path, '/')))) {
            return url($default);
        }

        return url($path);
    }
}
