<?php

namespace BenBjurstrom\Prezet\Actions;

class SetFrontmatter
{
    public static function update(string $md, array $fm): string
    {
        // Remove existing frontmatter if present
        $pattern = '/^---\s*\n.*?\n---\s*\n/s';
        $cleanMarkdown = preg_replace($pattern, '', $md);

        // Add new frontmatter
        return self::addFrontmatter($fm) . $cleanMarkdown;
    }

    private static function addFrontmatter(array $fm): string
    {
        $yaml = "---\n";
        foreach ($fm as $key => $value) {
            $yaml .= $key . ': ' . self::formatValue($value) . "\n";
        }
        $yaml .= "---\n\n";
        return $yaml;
    }

    private static function formatValue($value): string
    {
        if (is_array($value)) {
            return '[' . implode(', ', array_map([self::class, 'formatValue'], $value)) . ']';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_string($value) && (strpos($value, ':') !== false || strpos($value, '#') !== false)) {
            return '"' . str_replace('"', '\\"', $value) . '"';
        }
        return $value;
    }
}
