<nav class="text-base lg:text-sm">
    <ul role="list" class="space-y-9">
        @foreach ($nav as $section)
            <li>
                <h2 class="font-display font-medium text-stone-900">
                    {{ $section['title'] }}
                </h2>
                <ul
                    role="list"
                    class="mt-2 space-y-2 border-l-2 border-stone-100 lg:mt-4 lg:space-y-4 lg:border-stone-200"
                >
                    @foreach ($section['links'] as $link)
                        <li class="relative">
                            <a
                                @class([
                                    'before:-transtone-y-1/2 block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:rounded-full',
                                    'font-semibold text-primary-500 before:bg-primary-500' =>
                                        url()->current() === route('prezet.show', ['slug' => $link['slug']]),
                                    'text-stone-500 before:hidden before:bg-stone-300 hover:text-stone-600 hover:before:block' =>
                                        url()->current() !== route('prezet.show', ['slug' => $link['slug']]),
                                ])
                                href="{{ route('prezet.show', ['slug' => $link['slug']]) }}"
                            >
                                {{ $link['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
