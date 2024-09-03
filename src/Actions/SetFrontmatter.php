<?php

namespace BenBjurstrom\Prezet\Actions;

use Carbon\Carbon;

class SetFrontmatter
{
    /**
     * @param  array<string, string>  $fm
     */
    public static function update(string $md, array $fm): string
    {
        // Remove existing frontmatter if present
        $pattern = '/^---\s*\n.*?\n---\s*\n/s';
        $cleanMarkdown = preg_replace($pattern, '', $md);

        // Add new frontmatter
        return self::addFrontmatter($fm).$cleanMarkdown;
    }

    /**
     * @param  array<string, string>  $fm
     */
    private static function addFrontmatter(array $fm): string
    {
        $yaml = "---\n";
        foreach ($fm as $key => $value) {
            $yaml .= $key.': '.self::formatValue($value)."\n";
        }
        $yaml .= "---\n\n";

        return $yaml;
    }

    private static function formatValue(mixed $value): string
    {
        if (is_int($value) && $value > 946713600) {
            return (new Carbon($value))->toDateString();
        } elseif (is_array($value)) {
            return '['.implode(', ', array_map([self::class, 'formatValue'], $value)).']';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            return '';
        } elseif (is_string($value) && (strpos($value, ':') !== false || strpos($value, '#') !== false)) {
            return '"'.str_replace('"', '\\"', $value).'"';
        }

        return $value;
    }
}
