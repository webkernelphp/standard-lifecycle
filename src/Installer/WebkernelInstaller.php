<?php declare(strict_types=1);
namespace Webkernel\StdLifecycle\Installer;
use Composer\Installers\BaseInstaller;
class WebkernelInstaller extends BaseInstaller
{
    /**
     * Install path templates keyed by sub-type.
     * @var array<string, string>
     */
    protected $locations = [
        'module' => 'modules/{$vendor}/{$name}/',
  //      'lib'    => 'bootstrap/lib/{$vendor}/{$name}/',
    ];
}
