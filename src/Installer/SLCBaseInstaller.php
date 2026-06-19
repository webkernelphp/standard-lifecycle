<?php declare(strict_types=1);

namespace Webkernel\StdLifecycle\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Installers\Installer as ComposerInstaller;
use Composer\Package\PackageInterface;
use RuntimeException;

final class SLCBaseInstaller extends ComposerInstaller
{
    private SLCInstallerLocations $locations;

    public function __construct(IOInterface $io, Composer $composer)
    {
        parent::__construct($io, $composer);
        $this->locations = new SLCInstallerLocations($composer);
    }

    public function supports($packageType): bool
    {
        return in_array($packageType, $this->locations->types(), strict: true);
    }

    public function getInstallPath(PackageInterface $package): string
    {
        $template = $this->locations->destination($package);
        if ($template === null) {
            return parent::getInstallPath($package);
        }

        [$vendor, $name] = explode('/', $package->getName(), 2);
        $replacements = [
            '{$vendor}' => $vendor,
            '{$name}'   => $name,
        ];

        if ($package->getType() === SLCPackageType::ModulePlugin->value) {
            $parent = $this->locations->parentModule($package);
            if ($parent === null) {
                throw new RuntimeException(sprintf(
                    'Package "%s" is of type "%s" but does not declare its parent module via extra.webkernel.module.',
                    $package->getName(),
                    SLCPackageType::ModulePlugin->value,
                ));
            }
            $replacements['{$parentVendor}'] = $parent['vendor'];
            $replacements['{$parentName}']   = $parent['name'];
        }

        return strtr($template, $replacements);
    }
}
