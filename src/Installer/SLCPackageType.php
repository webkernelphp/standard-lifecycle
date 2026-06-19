<?php declare(strict_types=1);

namespace Webkernel\StdLifecycle\Installer;

enum SLCPackageType: string
{
    case Module       = 'webkernel-module';
    case ModulePlugin = 'webkernel-module-plugin';
    case Stdlib       = 'webkernel-stdlib';
    case Agent        = 'webkernel-agent';
    case Ffi          = 'webkernel-ffi';
    case Extension    = 'webkernel-extension';

    /**
     * Short, programmatically retrievable description.
     * Kept as a single line so it can be displayed in CLI output,
     * documentation generators, or IDE tooling without parsing docblocks.
     */
    public function description(): string
    {
        return match ($this) {
            self::Module       => 'Application module installed under modules/.',
            self::ModulePlugin => 'Plugin attached to a parent module, installed under modules/{vendor}/{module}/plugins/.',
            self::Stdlib       => 'Standard library package installed in vendor/ as a regular Composer dependency.',
            self::Agent        => 'Agentic worker package installed under agents/.',
            self::Ffi          => 'Native extension bridged through the Webkernel ABI, installed under ffi/.',
            self::Extension    => 'Pure-PHP extension hooking into kernel internals, installed under extensions/.',
        };
    }
}
