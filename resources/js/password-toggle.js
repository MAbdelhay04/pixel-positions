document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-password-toggle]');

    if (!button) {
        return;
    }

    const wrapper = button.closest('[data-password-field]');
    const input = wrapper?.querySelector('input');

    if (!input) {
        return;
    }

    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    button.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');

    wrapper.querySelector('[data-password-icon="show"]')?.classList.toggle('hidden', visible);
    wrapper.querySelector('[data-password-icon="hide"]')?.classList.toggle('hidden', !visible);
});
