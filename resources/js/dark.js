(function () {
    const html = document.documentElement;
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    function setTheme(dark) {
        html.classList.toggle('dark', dark);
        html.style.backgroundColor = dark ? '#000000' : '#f9fafb';
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    }

    setTheme(saved === 'dark' || (!saved && prefersDark));

    window.toggleDarkMode = function () {
        setTheme(!html.classList.contains('dark'));
    };
})();
