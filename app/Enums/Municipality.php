<?php

namespace App\Enums;

/**
 * The 11 municipalities of Ifugao province. Canonical stored/display value
 * is the Title Case label (e.g. "Alfonso Lista") — this is what dances,
 * attires, and interactive_guides all store in their `municipality` column.
 *
 * Legacy slugs (e.g. "alista") were used only by the dances table before
 * migration 2026_07_29_000000_normalize_dances_municipality_to_title_case;
 * fromLegacySlug() exists solely to support that one-time data migration.
 */
enum Municipality: string
{
    case AlfonsoLista = 'Alfonso Lista';
    case Aguinaldo = 'Aguinaldo';
    case Asipulo = 'Asipulo';
    case Banaue = 'Banaue';
    case Hingyon = 'Hingyon';
    case Hungduan = 'Hungduan';
    case Kiangan = 'Kiangan';
    case Lagawe = 'Lagawe';
    case Lamut = 'Lamut';
    case Mayoyao = 'Mayoyao';
    case Tinoc = 'Tinoc';

    /** @return list<string> Title Case labels, for validation `in:` rules and <select> options. */
    public static function labels(): array
    {
        return array_map(fn (self $c) => $c->value, self::cases());
    }

    /** @return list<array{id: string, name: string}> Shape expected by existing Blade <select> loops. */
    public static function options(): array
    {
        return array_map(fn (self $c) => ['id' => $c->value, 'name' => $c->value], self::cases());
    }

    /** Comma-separated Title Case labels, for `in:...` validation rule strings. */
    public static function validationList(): string
    {
        return implode(',', self::labels());
    }

    private static function legacySlugs(): array
    {
        return [
            'aguinaldo' => self::Aguinaldo,
            'alista' => self::AlfonsoLista,
            'asipulo' => self::Asipulo,
            'banaue' => self::Banaue,
            'hingyon' => self::Hingyon,
            'hungduan' => self::Hungduan,
            'kiangan' => self::Kiangan,
            'lagawe' => self::Lagawe,
            'lamut' => self::Lamut,
            'mayoyao' => self::Mayoyao,
            'tinoc' => self::Tinoc,
        ];
    }

    /** Used only by the one-time data migration that normalized dances.municipality. */
    public static function fromLegacySlug(string $slug): ?self
    {
        return self::legacySlugs()[$slug] ?? null;
    }
}
