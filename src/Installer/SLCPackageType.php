<?php declare(strict_types=1);
namespace Webkernel\StdLifecycle\Installer;
enum SLCPackageType: string
{
    case Assets          = 'webkernel-assets';
    case Component       = 'webkernel-component';
    case DevTool         = 'webkernel-devtool';
    case Module          = 'webkernel-module';
    case ModuleFeature   = 'webkernel-module-feature';
    case Stdlib          = 'webkernel-stdlib';
    case Element         = 'webkernel-element';
    case Agent           = 'webkernel-agent';
    case Ffi             = 'webkernel-ffi';

    public function description(): string
    {
        return match ($this) {
            self::Assets         => 'Asset bundle.',
            self::DevTool        => 'Development tool package.',
            self::Module         => 'Autonomous business module.',
            self::ModuleFeature  => 'Feature attached to a parent module. Requires extra.webkernel.module.',
            self::Stdlib         => 'Standard library package.',
            self::Component         => 'Components foundational capability.',
            self::Element        => 'Reusable UI component or Filament element.',
            self::Agent          => 'Agentic worker package.',
            self::Ffi            => 'Native binary bridged through the Webkernel ABI.',
        };
    }

    /**
     * Package types that must declare a parent module via
     * extra.webkernel.module in their composer.json.
     */
    public function requiresParentModule(): bool
    {
        return $this === self::ModuleFeature;
    }
}
