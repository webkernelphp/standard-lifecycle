<?php declare(strict_types=1);
namespace Webkernel\StdLifecycle\Installer;
use Composer\Composer;
use Composer\Package\PackageInterface;

final class SLCInstallerLocations
{
    /** @var array<string, list<SLCPackageType>> */
    private array $templates;

    public function __construct(private readonly Composer $composer)
    {
        $vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/') . '/{$vendor}/{$name}/';

        $this->templates = [
            'modules/{$vendor}/{$name}/'                                       => [SLCPackageType::Module],
            'modules/{$parentVendor}/{$parentName}/plugins/{$vendor}-{$name}/' => [SLCPackageType::ModulePlugin],
            $vendorDir                                                         => [SLCPackageType::Stdlib],
            'agents/{$vendor}/{$name}/'                                        => [SLCPackageType::Agent],
            'ffi/{$vendor}/{$name}/'                                           => [SLCPackageType::Ffi],
            'extensions/{$vendor}/{$name}/'                                    => [SLCPackageType::Extension],
        ];
    }

    /** @return list<string> */
    public function types(): array
    {
        return array_values(array_unique(
            array_map(static fn (SLCPackageType $t) => $t->value, array_merge(...array_values($this->templates)))
        ));
    }

    public function destination(PackageInterface $package): ?string
    {
        $type = SLCPackageType::tryFrom($package->getType());
        if ($type === null) {
            return null;
        }

        foreach ($this->templates as $template => $packageTypes) {
            if (in_array($type, $packageTypes, strict: true)) {
                return $template;
            }
        }

        return null;
    }

    /**
     * Resolve the parent module's vendor/name for a plugin package.
     *
     * A plugin declares its parent through:
     *   "extra": { "webkernel": { "module": "vendor/name" } }
     */
    public function parentModule(PackageInterface $package): ?array
    {
        $module = $package->getExtra()['webkernel']['module'] ?? null;
        if (!is_string($module) || !str_contains($module, '/')) {
            return null;
        }

        [$vendor, $name] = explode('/', $module, 2);

        return ['vendor' => $vendor, 'name' => $name];
    }
}
