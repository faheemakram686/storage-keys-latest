(function () {
    try {
        var typesWrap = document.getElementById('vhTypes');
        if (typesWrap) {
            var envWrap = document.getElementById('vhEnv');
            var durWrap = document.getElementById('vhDur');
            var vehIc = document.getElementById('vhVehIc');
            var foot = document.getElementById('vhFoot');
            var bay = document.getElementById('vhBay');
            var cap = document.getElementById('vhCap');
            var out = document.getElementById('vhOut');
            var btn = document.getElementById('vhCfgBtn');
            var TYPES = {
                car: {
                    icon: 'fa-car',
                    foot: 52,
                    size: 'standard',
                    cap: 'A standard car space away from everyday parking.',
                    g: 'A designated place to keep an unused or additional car away from residential and commercial parking.'
                },
                moto: {
                    icon: 'fa-motorcycle',
                    foot: 26,
                    size: 'compact',
                    cap: 'Compact footprint — sized to the motorcycle.',
                    g: 'Less space than larger vehicles, sized to the motorcycle’s dimensions and period.'
                },
                van: {
                    icon: 'fa-shuttle-van',
                    foot: 70,
                    size: 'high-clearance',
                    cap: 'More room and clearance for a van.',
                    g: 'Vans need more room and clearance — ideal for delivery, service or additional company vehicles.'
                },
                commercial: {
                    icon: 'fa-truck',
                    foot: 82,
                    size: 'fleet-sized',
                    cap: 'Fleet-sized space for commercial vehicles.',
                    g: 'Separate seasonal or temporarily inactive fleet vehicles from everyday operations and free up commercial space.'
                },
                larger: {
                    icon: 'fa-truck-moving',
                    foot: 94,
                    size: 'extra-large',
                    cap: 'Extra room for larger dimensions & movement.',
                    g: 'Larger vehicles need sufficient space for their dimensions and movement — accurate details help us match it.'
                },
                multiple: {
                    icon: 'fa-layer-group',
                    foot: 100,
                    size: 'multi-vehicle',
                    cap: 'A multi-vehicle arrangement.',
                    g: 'Several vehicles may need a different arrangement, based on the number and dimensions involved.'
                }
            };
            var state = { type: 'car', env: 'indoor', dur: 'short' };

            function render() {
                var t = TYPES[state.type] || TYPES.car;
                if (vehIc) vehIc.className = 'fas ' + t.icon + ' veh-ic';
                if (foot) foot.style.width = t.foot + '%';
                if (cap) {
                    var capLead = t.cap.split('—')[0].trim();
                    cap.innerHTML = '<b>' + capLead + '</b>';
                }
                if (bay) bay.classList.toggle('enclosed', state.env === 'indoor');
                var envTxt = state.env === 'indoor' ? 'enclosed (indoor)' : 'open (outdoor)';
                var durTxt = state.dur === 'short' ? 'short-term' : 'long-term';
                if (out) {
                    out.innerHTML = 'A <b>' + t.size + '</b> vehicle storage space, <b>' + envTxt + '</b>, for <b>' + durTxt + '</b> storage. ' + t.g + ' Share your vehicle details for an exact match.';
                }
                if (btn) btn.href = '#vh-quote';
            }

            typesWrap.addEventListener('click', function (e) {
                var b = e.target.closest('.vh-tp');
                if (!b) return;
                state.type = b.getAttribute('data-k') || 'car';
                [].slice.call(typesWrap.querySelectorAll('.vh-tp')).forEach(function (x) {
                    x.classList.toggle('active', x === b);
                });
                render();
            });

            [['vhEnv', 'env'], ['vhDur', 'dur']].forEach(function (pair) {
                var wrap = document.getElementById(pair[0]);
                if (!wrap) return;
                wrap.addEventListener('click', function (e) {
                    var b = e.target.closest('button');
                    if (!b) return;
                    state[pair[1]] = b.getAttribute('data-k');
                    [].slice.call(wrap.querySelectorAll('button')).forEach(function (x) {
                        x.classList.toggle('active', x === b);
                    });
                    render();
                });
            });

            render();
        }

        var range = document.getElementById('vhCmpRange');
        if (range) {
            var wrap = document.getElementById('vhInWrap');
            var handle = document.getElementById('vhHandle');
            function upd() {
                var v = parseInt(range.value, 10);
                if (wrap) wrap.style.clipPath = 'inset(0 ' + (100 - v) + '% 0 0)';
                if (handle) handle.style.left = v + '%';
            }
            range.addEventListener('input', upd);
            upd();
        }
    } catch (e) {
        /* no-op */
    }
})();
