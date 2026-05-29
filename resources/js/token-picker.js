function normalize(value) {
    return value.trim().replace(/\s+/g, " ");
}

function splitValues(value) {
    return value.split(",").map(normalize).filter(Boolean);
}

function uniquePush(items, value) {
    const clean = normalize(value);

    if (!clean) {
        return;
    }

    if (!items.some((item) => item.toLowerCase() === clean.toLowerCase())) {
        items.push(clean);
    }
}

function createChip(label, onRemove) {
    const chip = document.createElement("button");

    chip.type = "button";
    chip.className =
        "inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 transition-colors duration-150 hover:border-gray-300 hover:bg-gray-200 dark:border-white/10 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20";
    chip.innerHTML = `<span></span><span aria-hidden="true" class="text-gray-400 dark:text-gray-500">&times;</span>`;
    chip.querySelector("span").textContent = label;
    chip.addEventListener("click", onRemove);

    return chip;
}

function initTokenPickers() {
    document.querySelectorAll("[data-token-picker]").forEach(function (picker) {
        if (picker.dataset.tokenReady) {
            return;
        }

        picker.dataset.tokenReady = "true";

        const hidden = picker.querySelector("[data-token-hidden]");
        const input = picker.querySelector("[data-token-input]");
        const chips = picker.querySelector("[data-token-chips]");
        const suggestions = picker.querySelector("[data-token-suggestions]");
        const options = JSON.parse(picker.dataset.tokenOptions || "[]")
            .map(normalize)
            .filter(Boolean);
        let selected = [];

        splitValues(picker.dataset.tokenInitial || "").forEach(function (value) {
            uniquePush(selected, value);
        });

        function syncSelected() {
            hidden.value = selected.join(", ");
            chips.innerHTML = "";

            selected.forEach(function (value) {
                chips.appendChild(
                    createChip(value, function () {
                        selected = selected.filter(
                            (item) =>
                                item.toLowerCase() !== value.toLowerCase(),
                        );
                        syncSelected();
                        renderSuggestions();
                        input.focus();
                    }),
                );
            });
        }

        function renderSuggestions() {
            const query = normalize(input.value).toLowerCase();
            suggestions.innerHTML = "";

            const matches = options
                .filter(
                    (option) =>
                        !selected.some(
                            (item) =>
                                item.toLowerCase() === option.toLowerCase(),
                        ),
                )
                .filter(
                    (option) => !query || option.toLowerCase().includes(query),
                )
                .slice(0, 8);

            matches.forEach(function (option) {
                const button = document.createElement("button");

                button.type = "button";
                button.className =
                    "inline-flex cursor-pointer items-center rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-600 transition-colors duration-150 hover:border-blue-300 hover:text-blue-700 dark:border-white/10 dark:bg-black dark:text-gray-300 dark:hover:border-blue-700 dark:hover:text-blue-300";
                button.textContent = option;
                button.addEventListener("click", function () {
                    uniquePush(selected, option);
                    syncSelected();
                    renderSuggestions();
                    input.focus();
                });

                suggestions.appendChild(button);
            });
        }

        input.addEventListener("input", renderSuggestions);

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();

                splitValues(input.value).forEach(function (value) {
                    uniquePush(selected, value);
                });

                input.value = "";
                syncSelected();
                renderSuggestions();
            }

            if (
                event.key === "Backspace" &&
                input.value === "" &&
                selected.length > 0
            ) {
                selected.pop();
                syncSelected();
                renderSuggestions();
            }
        });

        syncSelected();
        renderSuggestions();
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initTokenPickers);
} else {
    initTokenPickers();
}
