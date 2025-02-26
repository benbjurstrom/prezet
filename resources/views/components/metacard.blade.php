<div
    class="rounded-2xl border p-4"
    x-data="{ ogimage: '' }"
    x-init="ogimage = document.querySelector('meta[property=\'og:image\']')?.content"
>
    <img x-bind:src="ogimage" alt="" />
    <h2 x-text="document.title" class="mt-2 font-semibold"></h2>
    <div
        x-text="document.querySelector('meta[name=\'description\']')?.content"
    ></div>
</div>
