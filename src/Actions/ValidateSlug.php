<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidateSlug
{
    public function handle(string $input): void
    {
        // first check if the valid slug exists
        $validSlug = Document::query()
            ->where('slug', $input)
            ->exists();

        // if it does, return it
        if ($validSlug) {
            return;
        }

        // if not check whether the key exists
        $key = last(explode('-', $input));
        $matchesKey = Document::query()
            ->where('key', $key)
            ->first();

        // and redirect to the correct slug
        if ($matchesKey) {
            throw new HttpResponseException(
                redirect()->route('prezet.show', $matchesKey->slug, 301)
            );
        }

        // if it doesn't match the key, check whether the slug is a filepath
        $filepath = 'content/'.$input.'.md';
        $matchesFilepath = Document::query()
            ->where('filepath', $filepath)
            ->first();

        // and redirect to the correct slug
        if ($matchesFilepath) {
            throw new HttpResponseException(
                redirect()->route('prezet.show', $matchesFilepath->slug, 301)
            );
        }

        // if none of the above, throw a 404
        abort(404);
    }
} 