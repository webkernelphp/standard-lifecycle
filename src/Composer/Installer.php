<?php declare(strict_types=1);
namespace Webkernel\StdInstaller\Composer;
use Composer\Installers\Installer as InstallerBase;
use Composer\Package\PackageInterface;
class Installer extends InstallerBase
{
    private const VENDOR = 'webkernel';
    /** @var list<string> Derived from WebkernelInstaller::$locations keys. */
    private const TYPES = ['webkernel-module' /*, 'webkernel-lib' */];

    public function getInstallPath(PackageInterface $package): string {
        return (new WebkernelInstaller($package, $this->composer, $this->io))->getInstallPath($package, self::VENDOR);
    }

    public function supports($packageType): bool {
        return in_array($packageType, self::TYPES, strict: true);
    }
}
