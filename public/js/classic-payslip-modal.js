(function () {
    function baseUrl() {
        try {
            return (window.localStorage.getItem('base_url') || window.location.origin || '').replace(/\/$/, '');
        } catch (e) {
            return (window.location.origin || '').replace(/\/$/, '');
        }
    }

    function ensureModal() {
        var existing = document.getElementById('classic-payslip-modal');
        if (existing) return existing;

        var wrap = document.createElement('div');
        wrap.id = 'classic-payslip-modal';
        wrap.innerHTML = [
            '<div class="classic-payslip-backdrop" data-close="1"></div>',
            '<div class="classic-payslip-dialog" role="dialog" aria-modal="true">',
            '  <div class="classic-payslip-dialog-head">',
            '    <strong>Payslip</strong>',
            '    <button type="button" class="classic-payslip-close" data-close="1" aria-label="Close">&times;</button>',
            '  </div>',
            '  <iframe class="classic-payslip-frame" title="Payslip preview"></iframe>',
            '</div>'
        ].join('');

        var style = document.createElement('style');
        style.textContent = [
            '#classic-payslip-modal{display:none;position:fixed;inset:0;z-index:2000;}',
            '#classic-payslip-modal.is-open{display:block;}',
            '.classic-payslip-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45);}',
            '.classic-payslip-dialog{position:relative;z-index:1;width:min(980px,94vw);height:min(92vh,900px);margin:3vh auto;background:#fff;border-radius:6px;box-shadow:0 12px 40px rgba(0,0,0,.25);display:flex;flex-direction:column;overflow:hidden;}',
            '.classic-payslip-dialog-head{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ddd;font-size:16px;}',
            '.classic-payslip-close{border:0;background:transparent;font-size:28px;line-height:1;cursor:pointer;color:#333;}',
            '.classic-payslip-frame{border:0;width:100%;flex:1;background:#fff;}'
        ].join('');
        document.head.appendChild(style);
        document.body.appendChild(wrap);

        wrap.addEventListener('click', function (e) {
            if (e.target && e.target.getAttribute('data-close') === '1') {
                window.__closeClassicPayslip();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') window.__closeClassicPayslip();
        });

        return wrap;
    }

    window.__openClassicPayslip = function (payslipId) {
        if (!payslipId) return;
        var url = baseUrl() + '/app/payslip/' + payslipId + '/html';
        var modal = ensureModal();
        var frame = modal.querySelector('.classic-payslip-frame');
        frame.src = url;
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    window.__closeClassicPayslip = function () {
        var modal = document.getElementById('classic-payslip-modal');
        if (!modal) return;
        modal.classList.remove('is-open');
        var frame = modal.querySelector('.classic-payslip-frame');
        if (frame) frame.src = 'about:blank';
        document.body.style.overflow = '';
    };
})();
