<?php

namespace App\Support;

/**
 * Deterministic gradient color pairs used as card backgrounds when a Dance,
 * Attire, or showcase item has no uploaded image. Same input (a record's id,
 * or a list index) always resolves to the same pair, so a given record's
 * placeholder color stays stable across renders.
 *
 * Three distinct sets exist because they were designed for different visual
 * contexts (visitor-facing cards, admin table swatches, homepage showcase)
 * — consolidating them here removes duplication without forcing one set to
 * serve a context it wasn't designed for.
 */
class PlaceholderPalette
{
    /** Visitor-facing dance/attire cards and modals. */
    private const VISITOR = [
        ['#7B3A10', '#C4854A'], ['#5C1F1F', '#C85A17'], ['#1A3A10', '#3A7A24'], ['#3A2A10', '#A0824D'],
        ['#1A2A4A', '#3A6A95'], ['#4A1A2A', '#A84060'], ['#2A3A10', '#7A9A3A'], ['#3A1A10', '#B07040'],
    ];

    /** Admin table row swatches (Dances, Attires) — brighter, higher-contrast on white. */
    private const ADMIN = [
        ['#7B3A10', '#D4A574'], ['#5C1F1F', '#C85A17'], ['#1A3A10', '#4A8A2C'], ['#3A2A10', '#B8925D'],
        ['#1A2A4A', '#4A7AB5'], ['#4A1A2A', '#C86090'], ['#2A3A10', '#8AB54A'], ['#3A1A10', '#C89060'],
    ];

    /** Homepage showcase gallery — a smaller, distinct set. */
    private const SHOWCASE = [
        ['#7B3A10', '#C4854A'], ['#1A3A10', '#3A7A24'], ['#5C1F1F', '#C85A17'],
        ['#3A2A10', '#A0824D'], ['#1A2A4A', '#3A6A95'],
    ];

    /** Municipality selection cards — extends VISITOR with 3 extra colors to cover all 11 municipalities distinctly. */
    private const MUNICIPALITY = [
        ['#7B3A10', '#C4854A'], ['#5C1F1F', '#C85A17'], ['#1A3A10', '#3A7A24'], ['#3A2A10', '#A0824D'],
        ['#1A2A4A', '#3A6A95'], ['#4A1A2A', '#A84060'], ['#2A3A10', '#7A9A3A'], ['#3A1A10', '#B07040'],
        ['#2A1A3A', '#8A4AB0'], ['#1A3A3A', '#3A8A8A'], ['#3A3A10', '#9A9A3A'],
    ];

    /** @return array{0: string, 1: string} */
    public static function visitor(int $index): array
    {
        return self::VISITOR[abs($index) % count(self::VISITOR)];
    }

    /** @return array{0: string, 1: string} */
    public static function admin(int $index): array
    {
        return self::ADMIN[abs($index) % count(self::ADMIN)];
    }

    /** @return array{0: string, 1: string} */
    public static function showcase(int $index): array
    {
        return self::SHOWCASE[abs($index) % count(self::SHOWCASE)];
    }

    /** @return array{0: string, 1: string} */
    public static function municipality(int $index): array
    {
        return self::MUNICIPALITY[abs($index) % count(self::MUNICIPALITY)];
    }
}
