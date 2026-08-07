(function () {
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.password-toggle');
        if (!btn) {
            return;
        }

        e.preventDefault();

        var wrap = btn.closest('.password-field-wrap');
        if (!wrap) {
            return;
        }

        var input = wrap.querySelector('input');
        var icon = btn.querySelector('i');
        if (!input) {
            return;
        }

        var showing = input.getAttribute('type') === 'password';
        input.setAttribute('type', showing ? 'text' : 'password');
        btn.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');

        if (icon) {
            icon.classList.toggle('fa-eye', !showing);
            icon.classList.toggle('fa-eye-slash', showing);
        }
    });
})();
