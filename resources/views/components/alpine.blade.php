<div
    x-data="{
        showSidebar: false,
        activeHeading: null,
        init() {
            const headingElements = document.querySelectorAll(
                'article h2, article h3',
            )

            // Create an Intersection Observer
            const observer = new IntersectionObserver(
                (entries) => {
                    const visibleHeadings = entries.filter(
                        (entry) => entry.isIntersecting,
                    )
                    if (visibleHeadings.length > 0) {
                        // Find the visible heading with the lowest top value
                        const topHeading = visibleHeadings.reduce(
                            (prev, current) =>
                                prev.boundingClientRect.top <
                                current.boundingClientRect.top
                                    ? prev
                                    : current,
                        )

                        this.activeHeading = topHeading.target.textContent
                    }
                },
                { rootMargin: '0px 0px -75% 0px', threshold: 1 },
            )

            // Observe each heading
            headingElements.forEach((heading) => {
                observer.observe(heading)
            })
        },

        scrollToHeading(headingId) {
            const heading = document.getElementById(headingId)
            if (heading) {
                heading.scrollIntoView({ behavior: 'smooth' })
            }
        },
    }"
>
    {{ $slot }}
</div>
