<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetDocumentModelFromSlug
{
    public function handle(string $slug): Document
    {
        $this->validateSlug($slug);

        return Document::query()
            ->where('slug', $slug)
            ->when(config('app.env') !== 'local', function ($query) {
                return $query->where('draft', false);
            })
            ->firstOrFail();
    }

    protected function validateSlug(string $input): void
    {
        // first check if the slug is valid
        $validSlug = Document::query()
            ->where('slug', $input)
            ->exists();

        // if valid return early
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
        throw new NotFoundHttpException;
    }
}
