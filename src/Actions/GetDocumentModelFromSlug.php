<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Config;
use Prezet\Prezet\Models\Document;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetDocumentModelFromSlug
{
    public function handle(string $slug): Document
    {
        $this->validateSlug($slug);

        return app(Document::class)::query()
            ->where('slug', $slug)
            ->when(app()->isProduction(), function ($query) {
                return $query->where('draft', false);
            })
            ->firstOrFail();
    }

    protected function validateSlug(string $input): void
    {
        // first check if the given slug exists
        if (app(Document::class)::query()
            ->where('slug', $input)
            ->exists()) {
            return;
        }

        // if not check whether there's a matching key
        $this->redirectUsingKey($input);

        // if not check whether there's a matching filepath
        $this->redirectUsingFilepath($input);

        // if none of the above, throw a 404
        throw new NotFoundHttpException;
    }

    protected function redirectUsingKey(string $input): void
    {
        // return early if not using keyed slugs
        if (! Config::boolean('prezet.slug.keyed')) {
            return;
        }

        $key = last(explode('-', $input));
        $doc = app(Document::class)::query()
            ->where('key', $key)
            ->first();

        // found a match so redirect to the correct slug
        if ($doc) {
            throw new HttpResponseException(
                redirect()->route('prezet.show', $doc->slug, app()->isProduction() ? 301 : 302)
            );
        }
    }

    protected function redirectUsingFilepath(string $input): void
    {
        // return early if using filepath as source for slugs (no need to redirect)
        if (Config::string('prezet.slug.source') === 'filepath') {
            return;
        }

        $filepath = 'content/'.$input.'.md';
        $doc = app(Document::class)::query()
            ->where('filepath', $filepath)
            ->first();

        // found a match so redirect to the correct slug
        if ($doc) {
            throw new HttpResponseException(
                redirect()->route('prezet.show', $doc->slug, app()->isProduction() ? 301 : 302)
            );
        }
    }
}
