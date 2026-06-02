function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

function updateBadge(count) {
    document.querySelectorAll("[data-notification-badge]").forEach((badge) => {
        if (count <= 0) {
            badge.classList.add("hidden");
            badge.textContent = "0";
            return;
        }

        badge.classList.remove("hidden");
        badge.textContent = count > 9 ? "9+" : String(count);
    });

    document.querySelectorAll("[data-mark-all-read]").forEach((button) => {
        if (count <= 0) {
            button.classList.add("invisible");
        } else {
            button.classList.remove("invisible");
        }
    });
}

async function requestJson(url, method = "POST") {
    const res = await fetch(url, {
        method,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken(),
        },
    });

    if (!res.ok) {
        throw new Error(`Request failed: ${res.status}`);
    }

    return res.json();
}

async function refreshDropdown(bell) {
    const url = bell.dataset.dropdownUrl;
    const target = bell.querySelector("[data-notification-dropdown-content]");
    if (!url || !target) return;

    const res = await fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken(),
        },
    });

    if (!res.ok) return;

    target.innerHTML = await res.text();
    bindNotificationItems(bell);
}

async function markAsRead(readUrl) {
    return requestJson(readUrl, "PATCH");
}

async function markAllAsRead(url) {
    return requestJson(url, "POST");
}

function bindNotificationItems(root = document) {
    root.querySelectorAll(".notification-item").forEach((item) => {
        if (item.dataset.bound === "true") return;
        item.dataset.bound = "true";

        item.addEventListener("click", async () => {
            const readUrl = item.dataset.notificationReadUrl;
            const redirectUrl = item.dataset.notificationUrl;

            try {
                const data = await markAsRead(readUrl);
                updateBadge(data.unread_count ?? 0);

                const bell = item.closest("[data-notification-bell]");
                if (bell) {
                    await refreshDropdown(bell);
                } else {
                    item.classList.remove(
                        "border-indigo-200",
                        "bg-indigo-50/60",
                        "dark:border-indigo-500/30",
                        "dark:bg-indigo-500/10",
                    );
                    const dot = item.querySelector(".rounded-full.bg-indigo-600");
                    if (dot) {
                        dot.classList.remove("bg-indigo-600", "dark:bg-indigo-400");
                        dot.classList.add("bg-transparent");
                    }
                }

                window.location.href = data.redirect_url ?? redirectUrl;
            } catch {
                window.location.href = redirectUrl;
            }
        });
    });
}

function bindMarkAllButtons(root = document) {
    root.querySelectorAll("[data-mark-all-read]").forEach((button) => {
        if (button.dataset.bound === "true") return;
        button.dataset.bound = "true";

        button.addEventListener("click", async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const url =
                button.dataset.markAllUrl ??
                button.closest("[data-notification-bell]")?.dataset.markAllUrl;

            if (!url) return;

            try {
                const data = await markAllAsRead(url);
                updateBadge(data.unread_count ?? 0);

                const bell = button.closest("[data-notification-bell]");
                if (bell) {
                    await refreshDropdown(bell);
                }

                if (document.querySelector("[data-notifications-page]")) {
                    window.location.reload();
                }
            } catch {
                // no-op
            }
        });
    });
}

function initNotificationBells() {
    document.querySelectorAll("[data-notification-bell]").forEach((bell) => {
        if (bell.dataset.bound === "true") return;
        bell.dataset.bound = "true";

        const trigger = bell.querySelector("[data-notification-trigger]");
        const panel = bell.querySelector("[data-notification-panel]");

        if (!trigger || !panel) return;

        trigger.addEventListener("click", (event) => {
            event.stopPropagation();
            const isOpen = !panel.classList.contains("hidden");
            closeAllPanels();
            if (!isOpen) {
                panel.classList.remove("hidden");
                trigger.setAttribute("aria-expanded", "true");
            }
        });

        panel.addEventListener("click", (event) => {
            event.stopPropagation();
        });
    });

    bindNotificationItems();
    bindMarkAllButtons();
}

function closeAllPanels() {
    document.querySelectorAll("[data-notification-panel]").forEach((panel) => {
        panel.classList.add("hidden");
    });
    document.querySelectorAll("[data-notification-trigger]").forEach((trigger) => {
        trigger.setAttribute("aria-expanded", "false");
    });
}

document.addEventListener("click", () => closeAllPanels());
document.addEventListener("DOMContentLoaded", initNotificationBells);

export { initNotificationBells, bindNotificationItems, bindMarkAllButtons };
