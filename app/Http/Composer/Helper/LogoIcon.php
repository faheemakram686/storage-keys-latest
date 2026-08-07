<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class LogoIcon
{
    use InstanceCreator;

    public function logoIcon()
    {
        return [
            'logo' => $this->resolve(settings('tenant_logo', 'app_logo'), '/images/logo.png'),
            'icon' => $this->resolve(settings('tenant_icon', 'app_icon'), '/images/icon.png')
        ];
    }

    /**
     * A stored path can outlive the file it points at, so fall back to the
     * bundled default rather than rendering a broken image.
     */
    protected function resolve($path, $default)
    {
        if (empty($path) || !is_file(public_path(ltrim($path, '/')))) {
            return url($default);
        }

        return url($path);
    }
}
