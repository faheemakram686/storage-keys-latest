<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirming payment</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f5f6fa; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 12px; padding: 2rem; max-width: 420px; width: 90%; box-shadow: 0 8px 24px rgba(0,0,0,.08); text-align: center; }
        .spinner { width: 40px; height: 40px; border: 3px solid #e5e9f2; border-top-color: #6576ff; border-radius: 50%; margin: 0 auto 1rem; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { font-size: 1.25rem; margin: 0 0 0.5rem; }
        p { color: #526484; margin: 0 0 1rem; }
        a { color: #6576ff; }
    </style>
</head>
<body>
<div class="card">
    <div class="spinner" id="spinner"></div>
    <h1 id="title">Confirming your payment</h1>
    <p id="message">Please wait while we confirm the payment with the bank. Do not close this page.</p>
    <p><a id="invoice-link" href="{{ $invoiceUrl }}">Return to invoice</a></p>
</div>
<script>
(function () {
    var statusUrl = @json($statusUrl);
    var invoiceUrl = @json($invoiceUrl);
    var tries = 0;
    var maxTries = 12;
    var title = document.getElementById('title');
    var message = document.getElementById('message');
    var spinner = document.getElementById('spinner');

    function finish(ok, text, redirect) {
        spinner.style.display = 'none';
        title.textContent = ok ? 'Payment successful' : 'Payment status';
        message.textContent = text;
        if (redirect) {
            window.setTimeout(function () { window.location.href = redirect; }, 1200);
        }
    }

    function poll() {
        tries += 1;
        fetch(statusUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'paid') {
                    finish(true, data.message || 'Payment successful!', data.invoice_url || invoiceUrl);
                    return;
                }
                if (data.status === 'failed') {
                    finish(false, data.message || 'Payment failed or cancelled.', data.invoice_url || invoiceUrl);
                    return;
                }
                if (tries >= maxTries) {
                    finish(false, 'Payment is still processing. You can return to the invoice; we will update it when the bank confirms.', invoiceUrl);
                    return;
                }
                window.setTimeout(poll, 2500);
            })
            .catch(function () {
                if (tries >= maxTries) {
                    finish(false, 'We could not confirm the payment yet. Please return to the invoice shortly.', invoiceUrl);
                    return;
                }
                window.setTimeout(poll, 2500);
            });
    }

    poll();
})();
</script>
</body>
</html>
