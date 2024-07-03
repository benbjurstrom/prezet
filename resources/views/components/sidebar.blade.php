{{-- Mobile Sidebar --}}
<div
    x-show="showSidebar"
    x-trap.inert.noscroll="showSidebar"
    class="fixed inset-0 z-40 flex h-full items-start overflow-y-auto bg-stone-900/50 pr-10 backdrop-blur lg:hidden"
>
    <div
        class="min-h-full w-full max-w-xs bg-white px-4 pb-12 pt-24 sm:px-6"
        x-on:click.outside="showSidebar = false"
    >
        <x-prezet::nav :nav="$nav" />
    </div>
</div>

{{-- Desktop Sidebar --}}
<div class="hidden lg:relative lg:block lg:flex-none">
    <div class="absolute inset-y-0 right-0 w-[50vw] bg-stone-50"></div>
    <div
        class="absolute bottom-0 right-0 top-16 hidden h-12 w-px bg-gradient-to-t from-stone-800"
    ></div>
    <div
        class="absolute bottom-0 right-0 top-28 hidden w-px bg-stone-800"
    ></div>
    <div
        class="sticky top-[4.75rem] -ml-0.5 flex h-[calc(100vh-4.75rem)] w-64 flex-col justify-between overflow-y-auto overflow-x-hidden pb-4 pl-0.5 pr-8 pt-16 xl:w-72 xl:pr-16"
    >
        <x-prezet::nav :nav="$nav" />
        <div class="text-xs text-stone-400">
            <a target="_blank" href="https://prezet.com">Powered by Prezet</a>
        </div>
    </div>
</div>
