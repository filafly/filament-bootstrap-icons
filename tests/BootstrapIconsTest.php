<?php

namespace Filafly\Icons\Bootstrap\Tests;

use BladeUI\Icons\Factory;
use Filafly\Icons\Bootstrap\BootstrapIcons;
use Filafly\Icons\Bootstrap\Enums\Bootstrap;
use Filafly\Icons\Bootstrap\Enums\BootstrapStyle;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Enums\IconSize;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\IconManager;
use InvalidArgumentException;
use ReflectionClass;

class BootstrapIconsTest extends TestCase
{
    /**
     * Every alias Filament defines in its *IconAlias classes, read from the installed version.
     *
     * @return array<string, string> constant => alias
     */
    public static function filamentAliases(): array
    {
        $aliases = [];

        foreach (glob(dirname(__DIR__).'/vendor/filament/*/src/View/*IconAlias.php') as $file) {
            preg_match('/^namespace\s+([^;]+);/m', file_get_contents($file), $matches);
            $class = $matches[1].'\\'.basename($file, '.php');

            foreach ((new ReflectionClass($class))->getConstants() as $name => $alias) {
                $aliases[class_basename($class).'::'.$name] = $alias;
            }
        }

        return $aliases;
    }

    /**
     * Filament's own alias normalization, or the alias unchanged on 4.0 and 4.1,
     * which have no normalizeAlias() and no renamed keys.
     */
    private static function normalize(string $alias): string
    {
        return method_exists(IconManager::class, 'normalizeAlias')
            ? IconManager::normalizeAlias($alias)
            : $alias;
    }

    /** @return array<string, Bootstrap> */
    private function iconMap(): array
    {
        $property = (new ReflectionClass(BootstrapIcons::class))->getProperty('iconMap');

        return $property->getValue(new BootstrapIcons);
    }

    public function test_it_finds_filaments_alias_classes(): void
    {
        $this->assertGreaterThan(100, count(self::filamentAliases()), 'Filament 4.0 defines 141 aliases');
    }

    public function test_every_filament_alias_is_mapped(): void
    {
        $mapped = array_map(self::normalize(...), array_keys($this->iconMap()));

        $missing = array_filter(
            self::filamentAliases(),
            fn (string $alias): bool => ! in_array(self::normalize($alias), $mapped, true),
        );

        $this->assertSame([], $missing, 'Filament aliases with no Bootstrap icon: '.implode(', ', array_keys($missing)));
    }

    public function test_icons_are_mapped_to_their_regular_version(): void
    {
        foreach ($this->iconMap() as $alias => $icon) {
            $this->assertInstanceOf(Bootstrap::class, $icon, "{$alias} is not mapped to a Bootstrap case");
            $this->assertFalse(str_ends_with($icon->value, '-fill'), "{$alias} is mapped to a fill icon");
        }
    }

    public function test_every_enum_case_has_an_svg_in_blade_bootstrap_icons(): void
    {
        $factory = app(Factory::class);
        $missing = [];

        foreach (Bootstrap::cases() as $case) {
            try {
                $factory->svg($case->getIconForSize(IconSize::Medium));
            } catch (\Throwable) {
                $missing[] = $case->value;
            }
        }

        $this->assertSame([], $missing, 'Enum cases with no SVG: '.implode(', ', $missing));
    }

    public function test_enum_cases_resolve_to_the_bi_blade_prefix(): void
    {
        $this->assertSame('bi-trash', Bootstrap::Trash->getIconForSize(IconSize::Small));
        $this->assertSame('bi-0-circle-fill', Bootstrap::Icon0CircleFill->getIconForSize(IconSize::Large));
    }

    public function test_registering_replaces_filaments_icons_with_regular_icons(): void
    {
        BootstrapIcons::make()->registerIcons();

        $this->assertSame('bi-search', FilamentIcon::resolve('panels::global-search.field'));
        $this->assertSame('bi-trash', FilamentIcon::resolve('actions::delete-action'));
        $this->assertSame('bi-type-bold', FilamentIcon::resolve('forms:components.rich-editor.toolbar.bold'));
        $this->assertSame('bi-fonts', FilamentIcon::resolve('tables::filters.query-builder.constraints.text'));

        foreach (self::filamentAliases() as $constant => $alias) {
            $this->assertStringStartsWith('bi-', (string) FilamentIcon::resolve($alias), "{$constant} is not a Bootstrap icon");
        }
    }

    public function test_fill_switches_every_icon_that_has_a_fill_version(): void
    {
        BootstrapIcons::make()->fill()->registerIcons();

        $this->assertSame('bi-house-fill', FilamentIcon::resolve('panels::pages.dashboard.navigation-item'));
        $this->assertSame('bi-trash-fill', FilamentIcon::resolve('actions::delete-action'));
        $this->assertSame('bi-chevron-right', FilamentIcon::resolve('breadcrumbs.separator'));

        foreach ($this->iconMap() as $alias => $icon) {
            $expected = Bootstrap::tryFrom("{$icon->value}-fill") ?? $icon;

            $this->assertSame("bi-{$expected->value}", FilamentIcon::resolve($alias), "{$alias} has the wrong fill icon");
        }
    }

    public function test_style_can_be_overridden_per_alias_and_per_icon_without_a_global_style(): void
    {
        BootstrapIcons::make()
            ->overrideStyleForAlias('actions::delete-action', BootstrapStyle::Fill)
            ->overrideStyleForIcon(Bootstrap::Search, 'fill')
            ->registerIcons();

        $this->assertSame('bi-trash-fill', FilamentIcon::resolve('actions::delete-action'));
        $this->assertSame('bi-trash', FilamentIcon::resolve('actions::force-delete-action'));
        $this->assertSame('bi-search', FilamentIcon::resolve('tables::search-field'), 'search has no fill version');
        $this->assertSame('bi-house', FilamentIcon::resolve('panels::pages.dashboard.navigation-item'));
    }

    public function test_regular_can_be_kept_for_some_icons_when_the_rest_are_fill(): void
    {
        BootstrapIcons::make()
            ->fill()
            ->overrideStyleForAlias(['actions::delete-action', 'actions::edit-action'], 'regular')
            ->registerIcons();

        $this->assertSame('bi-trash', FilamentIcon::resolve('actions::delete-action'));
        $this->assertSame('bi-pencil-square', FilamentIcon::resolve('actions::edit-action'));
        $this->assertSame('bi-trash-fill', FilamentIcon::resolve('actions::force-delete-action'));
    }

    public function test_aliases_and_icons_can_be_overridden(): void
    {
        BootstrapIcons::make()
            ->overrideAlias('actions::delete-action', Bootstrap::Eraser)
            ->overrideIcons([Bootstrap::Search->value => Bootstrap::BinocularsFill])
            ->registerIcons();

        $this->assertSame('bi-eraser', FilamentIcon::resolve('actions::delete-action'));
        $this->assertSame('bi-trash', FilamentIcon::resolve('actions::force-delete-action'));
        $this->assertSame('bi-binoculars-fill', FilamentIcon::resolve('tables::search-field'));
    }

    public function test_legacy_query_builder_keys_do_not_overwrite_an_override(): void
    {
        BootstrapIcons::make()
            ->overrideAlias('query-builder::constraints.text', Bootstrap::Star)
            ->registerIcons();

        $this->assertSame('bi-star', FilamentIcon::resolve('query-builder::constraints.text'));
        $this->assertSame('bi-hash', FilamentIcon::resolve('query-builder::constraints.number'));
    }

    public function test_legacy_query_builder_keys_are_kept_only_where_filament_uses_them(): void
    {
        $map = $this->iconMap();
        $legacy = 'tables::filters.query-builder.constraints.text';

        if (self::normalize($legacy) === $legacy) {
            $this->assertArrayHasKey($legacy, $map, 'Filament without the renamed keys still needs the legacy one');
        } else {
            $this->assertArrayNotHasKey($legacy, $map, 'The legacy key would overwrite the current one');
            $this->assertArrayHasKey(self::normalize($legacy), $map);
        }
    }

    public function test_rich_editor_toolbar_aliases_are_mapped(): void
    {
        // Filament's own toolbar constants (only in later 4.x releases), so a renamed
        // prefix shows up here instead of silently falling back to Heroicons.
        $toolbar = array_filter(
            self::filamentAliases(),
            fn (string $constant): bool => str_contains($constant, 'RICH_EDITOR_TOOLBAR_'),
            ARRAY_FILTER_USE_KEY,
        );

        if ($toolbar === []) {
            $this->markTestSkipped('This Filament version has no rich editor toolbar aliases.');
        }

        BootstrapIcons::make()->registerIcons();

        foreach ($toolbar as $constant => $alias) {
            $this->assertArrayHasKey($alias, $this->iconMap(), "{$constant} is not in the icon map");
            $this->assertStringStartsWith('bi-', FilamentIcon::resolve($alias), "{$constant} is not a Bootstrap icon");
        }
    }

    public function test_unknown_styles_are_rejected(): void
    {
        $this->assertSame(['regular', 'fill'], BootstrapIcons::make()->getAvailableStyleNames());

        $this->expectException(InvalidArgumentException::class);

        BootstrapIcons::make()->style('duotone');
    }

    public function test_the_plugin_registers_icons_when_a_panel_boots(): void
    {
        $panel = Panel::make()->id('admin')->default()->plugin(BootstrapIcons::make()->fill());
        Filament::registerPanel($panel);

        $panel->getPlugin('filafly-filament-bootstrap-icons')->boot($panel);

        $this->assertSame('bi-house-fill', FilamentIcon::resolve('panels::pages.dashboard.navigation-item'));
    }
}
