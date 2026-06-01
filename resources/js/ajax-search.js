/**
 * ajaxSearch — Alpine.js data function
 *
 * Usage:
 *   <div x-data="ajaxSearch({ url: '/jobs', resultsSelector: '#results' })">
 *
 * Options:
 *   url              — base URL to fetch (required)
 *   resultsSelector  — CSS selector of the container to replace (default: '#results')
 *   debounceMs       — debounce delay for text inputs in ms (default: 300)
 */
function ajaxSearch({
    url,
    resultsSelector = "#results",
    debounceMs = 300,
} = {}) {
    return {
        _url: url,
        _selector: resultsSelector,
        _timer: null,
        loading: false,
        fetchError: false,
        searchAttempted: false,

        init() {
            const form = this._form();
            if (!form) return;

            // Text / search / date inputs → debounced
            form.querySelectorAll(
                'input[type="search"], input[type="text"], input[type="date"]',
            ).forEach((el) =>
                el.addEventListener("input", () => this._debounce()),
            );

            // Prevent normal submit → go through AJAX
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                this._run();
            });

            // Multi-select Alpine component emits this after toggling a value
            form.addEventListener("multi-select-change", () =>
                this._debounce(),
            );
        },

        // Called by the Search button (type="submit" on the form triggers the
        // submit listener above, but keep this for explicit x-on:click bindings)
        submitSearch() {
            clearTimeout(this._timer);
            this._run();
        },

        _debounce() {
            clearTimeout(this._timer);
            this._timer = setTimeout(() => this._run(), debounceMs);
        },

        _form() {
            return (
                this.$el.querySelector("form[data-search-form]") ??
                this.$el.querySelector("form")
            );
        },

        _params() {
            const form = this._form();
            return form
                ? new URLSearchParams(new FormData(form))
                : new URLSearchParams();
        },

        async _run() {
            const params = this._params();
            const fullUrl = `${this._url}?${params.toString()}`;

            // Keep URL in sync so refresh / share works
            history.pushState({}, "", fullUrl);

            this.searchAttempted = true;
            this.loading = true;
            this.fetchError = false;

            try {
                const res = await fetch(fullUrl, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content ?? "",
                    },
                });

                if (!res.ok) throw new Error(res.statusText);

                const html = await res.text();
                const target = document.querySelector(this._selector);
                if (target) target.innerHTML = html;

                // Re-init Alpine on newly injected HTML (e.g. pagination links, dropdowns)
                Alpine.initTree(target);
            } catch (_) {
                this.fetchError = true;
            } finally {
                this.loading = false;
            }
        },
    };
}

window.ajaxSearch = ajaxSearch;
