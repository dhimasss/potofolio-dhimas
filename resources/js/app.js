import './bootstrap';

/*
 | Tombol mode gelap / terang ([data-theme-toggle] di layout publik).
 | Tema awal sudah dipasang oleh script kecil di <head>; di sini hanya menangani klik.
 */
const root = document.documentElement;
const themeToggles = document.querySelectorAll('[data-theme-toggle]');

const syncToggleLabel = () => {
    const isDark = root.classList.contains('dark');
    themeToggles.forEach((btn) => {
        btn.setAttribute('aria-label', isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
    });
};

themeToggles.forEach((btn) => {
    btn.addEventListener('click', () => {
        const isDark = root.classList.toggle('dark');
        try {
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        } catch (e) {
            // localStorage bisa diblokir (mis. mode privat) — tema tetap berganti, hanya tidak diingat.
        }
        syncToggleLabel();
    });
});

// Bila pengunjung belum pernah memilih, ikuti perubahan pengaturan sistem secara langsung.
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    let saved = null;
    try {
        saved = localStorage.getItem('theme');
    } catch (err) {}
    if (!saved) {
        root.classList.toggle('dark', e.matches);
        syncToggleLabel();
    }
});

syncToggleLabel();

/*
 | Scroll reveal: elemen ber-atribut [data-reveal] muncul perlahan saat masuk layar.
 | Styling-nya ada di app.css (hanya aktif bila <html> punya class "js").
 */
const revealables = document.querySelectorAll('[data-reveal]');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 },
    );

    revealables.forEach((el) => observer.observe(el));
} else {
    revealables.forEach((el) => el.classList.add('is-visible'));
}
