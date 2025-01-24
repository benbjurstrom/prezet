<!-- Command Palette -->
<!-- An Alpine.js and Tailwind CSS component by https://pinemix.com -->
<!-- Alpine.js focus plugin is required, for more info http://pinemix.com/docs/getting-started -->

<div
    x-data="{
  // Customize Command Palette
  open: false,
  resetOnOpen: true,
  closeOnSelection: true,
  loading: false,

    async performSearch(query) {
        this.loading = true;
        try {
            const response = await fetch(`{{route('prezet.search')}}?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Search failed');
            return await response.json();
        } catch (error) {
            console.error('Search error:', error);
            this.filterResults = [];
        } finally {
            this.loading = false;
        }
    },

  // Add your custom functionality or navigation when an option is selected
  optionSelected() {
    console.log(this.highlightedOption);
    window.location = this.highlightedOption.url;
  },

  // Available options (id and label are required)
options: [],


  // Helper variables
  modifierKey: '',
  filterTerm: '',
  filterResults: [],
  highlightedOption: null,
  highlightedIndex: -1,
  enableMouseHighlighting: true,

  // Initialization
  init() {
    if (this.open) {
      this.openCommandPalette();
    }

    // Initialize filter results array
    this.filterResults = this.options;

    // Set the modifier key based on platform
    this.modifierKey = /mac/i.test(navigator.userAgentData ? navigator.userAgentData.platform : navigator.platform) ? '⌘' : 'Ctrl';
  },

  // Open Command Palette
  openCommandPalette() {
    if (this.resetOnOpen) {
      this.filterTerm = '';
      this.highlightedOption = null;
      this.highlightedIndex = -1;
      this.filterResults = this.options;
    }

    this.open = true;

    $nextTick(() => {
      // Focus filter input
      $focus.focus($refs.elFilter);
    });
  },

  // Close Command Palette
  closeCommandPalette() {
    this.open = false;

    $nextTick(() => {
      // Focus toggle button
      $focus.focus($refs.elToggleButton);
    });
  },

  // Enable mouse interaction
  enableMouseInteraction() {
    this.enableMouseHighlighting = true;
  },

  // Filter functionality
  async filter() {
    if (this.filterTerm === '') {
      this.filterResults = this.options;
    } else {
      this.filterResults = await this.performSearch(this.filterTerm);
      this.options = this.filterResults;
    }

    // Refresh highlighted array index (the results have been updated)
    if (this.filterResults.length > 0 && this.highlightedOption) {
        this.highlightedIndex = this.filterResults.findIndex((option) => {
          return option.id === this.highlightedOption.id;
        });
      }
  },

  // Set an option as highlighted
  setHighlighted(id, mode) {
    if (id === null) {
      this.highlightedOption = null;
      this.highlightedIndex = -1;
    } else if (this.highlightedOption?.id != id && (mode === 'keyboard' || (mode === 'mouse' && this.enableMouseHighlighting))) {
      this.highlightedOption = this.options.find(options => options.id === id) || null;

      // Set highlighted index of filter results
      if (mode === 'mouse' && this.enableMouseHighlighting) {
          this.highlightedIndex = this.filterResults.findIndex((option) => {
            return option.id === id;
          });
      } else {
        // We are in keyboard mode, disable mouse navigation
        this.enableMouseHighlighting = false;

        // Scroll listbox to make the highlighted element visible
        $refs.elListbox.querySelector('li[data-id=\'' + id + '\']').scrollIntoView({ block: 'nearest' });
      }
    }
  },

  // Check if the given id is the highlighted one
  isHighlighted(id) {
    return id === this.highlightedOption?.id || false;
  },

  // Navigate results functionality
  navigateResults(mode) {
    if (this.filterResults.length > 0) {
      const maxIndex = this.filterResults.length - 1;

      if (mode === 'first') {
        this.highlightedIndex = 0;
      } else if (mode === 'last') {
        this.highlightedIndex = maxIndex;
      } else if (mode === 'previous') {
        if (this.highlightedIndex > 0 && this.highlightedIndex <= maxIndex) {
          this.highlightedIndex--;
        } else if (this.highlightedIndex === -1) {
          this.highlightedIndex = 0;
        }
      } else if (mode === 'next') {
        if (this.highlightedIndex >= 0 && this.highlightedIndex < maxIndex) {
          this.highlightedIndex++;
        } else if (this.highlightedIndex === -1) {
          this.highlightedIndex = 0;
        }
      }

      if (!this.filterResults[this.highlightedIndex]?.id) {
        this.highlightedIndex = 0;
      }

      this.setHighlighted(this.filterResults[this.highlightedIndex].id, 'keyboard');
    }
  },

  // On option selected
  onOptionSelected() {
    if (this.highlightedOption != null) {
      this.optionSelected();

      if (this.closeOnSelection) {
        this.closeCommandPalette();
      }
    }
  },
}"
    x-on:keydown.ctrl.k.prevent.document="openCommandPalette()"
    x-on:keydown.meta.k.prevent.document="openCommandPalette()"
>
    <!-- Toggle Button -->
    <button
        x-ref="elToggleButton"
        x-on:click="openCommandPalette()"
        type="button"
        class="group inline-flex items-center justify-center gap-2 rounded-lg border-zinc-200 bg-white p-1.5 text-sm/6 font-medium text-zinc-800 hover:border-zinc-300 hover:text-zinc-900 hover:shadow-xs focus:ring-zinc-300/25 active:border-zinc-200 active:shadow-none dark:border-zinc-700 dark:bg-transparent dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:text-zinc-200 dark:focus:ring-zinc-600/50 dark:active:border-zinc-700 lg:min-w-64 lg:border lg:px-3"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            data-slot="icon"
            class="hi-mini hi-magnifying-glass inline-block size-6 opacity-60 group-hover:text-zinc-600 group-hover:opacity-100 dark:group-hover:text-zinc-400 lg:size-5"
        >
            <path
                fill-rule="evenodd"
                d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                clip-rule="evenodd"
            />
        </svg>
        <span
            class="hidden grow text-start opacity-60 group-hover:opacity-100 lg:block"
        >
            Search..
        </span>
        <span
            class="hidden flex-none text-xs font-semibold opacity-75 lg:block"
        >
            <span x-text="modifierKey" class="opacity-75"></span>
            <span>K</span>
        </span>
    </button>
    <!-- END Toggle Button -->

    <!-- Backdrop -->
    <div
        x-cloak
        x-show="open"
        x-trap.inert.noscroll="open"
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-bind:aria-hidden="!open"
        x-on:keydown.esc.prevent.stop="closeCommandPalette()"
        class="z-90 fixed inset-0 overflow-y-auto overflow-x-hidden bg-zinc-900/75 p-4 backdrop-blur-xs will-change-auto md:py-8 lg:px-8 lg:py-16"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
    >
        <!-- Command Palette Container -->
        <div
            x-cloak
            x-show="open"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-32 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-32 opacity-0"
            x-on:click.outside="closeCommandPalette()"
            class="mx-auto flex w-full max-w-lg flex-col rounded-xl shadow-xl will-change-auto dark:text-zinc-100 dark:shadow-black/25"
            role="document"
        >
            <!-- Search Input -->
            <div
                class="relative rounded-t-lg bg-white px-2 pt-2 dark:bg-zinc-800"
            >
                <div
                    class="flex w-full items-center rounded-lg bg-zinc-100 px-3 dark:bg-zinc-700/75"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        data-slot="icon"
                        stroke-width="1.5"
                        class="hi-mini hi-magnifying-glass inline-block size-6 opacity-50"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    <input
                        x-ref="elFilter"
                        x-model="filterTerm"
                        x-on:input.debounce.300ms="filter($event)"
                        x-on:keydown.enter.prevent.stop="onOptionSelected()"
                        x-on:keydown.up.prevent.stop="navigateResults('previous')"
                        x-on:keydown.down.prevent.stop="navigateResults('next')"
                        x-on:keydown.home.prevent.stop="navigateResults('first')"
                        x-on:keydown.end.prevent.stop="navigateResults('last')"
                        x-on:keydown.page-up.prevent.stop="navigateResults('first')"
                        x-on:keydown.page-down.prevent.stop="navigateResults('last')"
                        type="text"
                        class="w-full border-none bg-transparent py-3 text-sm placeholder:text-zinc-500 focus:outline-hidden focus:ring-0 dark:placeholder:text-zinc-400"
                        placeholder="Search..."
                        tabindex="0"
                        role="combobox"
                        aria-expanded="true"
                        aria-autocomplete="list"
                    />
                    <svg
                        x-show="loading"
                        class="inline-block size-6 animate-spin opacity-50"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </div>
            </div>
            <!-- EMD Search Input -->

            <!-- Listbox -->
            <ul
                x-show="filterResults.length > 0"
                x-ref="elListbox"
                x-on:mousemove.throttle="enableMouseInteraction()"
                x-on:mouseleave="setHighlighted(null)"
                class="max-h-72 overflow-auto rounded-b-xl bg-white p-2 dark:bg-zinc-800"
                role="listbox"
            >
                <template x-for="option in filterResults" :key="option.id">
                    <li
                        x-on:click="onOptionSelected()"
                        x-on:mouseenter="setHighlighted(option.id, 'mouse')"
                        x-bind:class="{
                            'text-white bg-zinc-600 dark:text-white dark:bg-zinc-600': isHighlighted(
                                option.id,
                            ),
                            'text-zinc-600 dark:text-zinc-300': ! isHighlighted(option.id),
                        }"
                        x-bind:data-selected="isHighlighted(option.id)"
                        x-bind:data-id="option.id"
                        x-bind:data-label="option.text"
                        x-bind:aria-selected="isHighlighted(option.id)"
                        class="group flex cursor-pointer flex-col rounded-lg px-3 py-3 text-sm"
                        role="option"
                        tabindex="-1"
                    >
                        <div class="flex grow items-center">
                            <div
                                x-text="option.text"
                                class="font-medium"
                            ></div>
                        </div>
                        <div class="flex-none text-xs font-semibold opacity-75">
                            <span x-text="option.slug"></span>
                        </div>
                    </li>
                </template>
            </ul>
            <!-- END Listbox -->

            <!-- No Results Feedback -->
            <div
                x-show="filterResults.length === 0"
                class="rounded-b-xl bg-white p-3 dark:bg-zinc-800"
            >
                <div
                    class="space-y-3 py-1.5 text-center text-sm text-zinc-500 dark:text-zinc-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        data-slot="icon"
                        class="hi-outline hi-x-circle inline-block size-8 opacity-50"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                    <p>No search results</p>
                </div>
            </div>
            <!-- END No Results Feedback -->
        </div>
        <!-- END Command Palette Container -->
    </div>
    <!-- END Backdrop -->
</div>
<!-- END Command Palette -->
