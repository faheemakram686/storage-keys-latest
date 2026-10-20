@extends('ui.layouts.frontend')
@section('title', '| Booking')
@section('metaTitle', 'Book a Storage Unit | Storage Keys')
@section('metaDescription', 'Search available storage units in Sharjah and Dubai. Filter by location, unit level, storage type and size, then enquire to reserve your unit.')

@section('css')
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking.css') }}">
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking-layout.css') }}">
@endsection

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero bk-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Booking</span></div>
            <h1>Book your unit</h1>
            <p class="lead">Search available units by location, type and size. Choose a unit that fits your needs and send an enquiry to reserve it.</p>
        </div>
    </section>

    <!-- ============ VISUALIZER ============ -->
    <section class="bk-block" id="bk-visual">
        <div class="sk-container">
            <div class="bk-visual-card size-sqft-section">
                <h2>Visualizer</h2>
                <p>Compare the sizes of our units.</p>
                <div class="bk-fan" id="bk-fan"></div>
                <p class="bk-size-readout"><span id="bk-size-readout">50 sq. ft.</span><span id="bk-size-hint">Garage size</span></p>
                <div class="bk-size-bar" aria-hidden="true"><span id="bk-size-fill"></span></div>
                <div class="size-sqft-section-body">
                    @isset($data)
                        @foreach (collect($data['ss'])->sortBy(function ($ss) { preg_match('/\d+/', (string) $ss->unit_type_name, $m); return (int) ($m[0] ?? 0); }) as $ss)
                            <div class="size-container" data="{{ $ss->id }}">
                                <span>{{ $ss->unit_type_name }}</span>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </section>

    <!-- ============ LOCATION + FILTERS ============ -->
    <section class="bk-block booking-section booking-page" id="bk-search">
        <div class="sk-container">
            <h2 class="bk-block-title">Location and Duration</h2>
            <p class="bk-note">Pricing and availability are subject to your preferred unit.</p>
            <div class="bk-pair">
                <div class="bk-panel locations-section">
                    <h3 class="locations-section-header">Location</h3>
                    <div class="locations-section-body">
                        <label class="lbl" for="country_id">Country</label>
                        <select class="form-control" name="country_id" id="country_id">
                            <option value="">Choose One</option>
                            @isset($data)
                                @foreach ($data['loc'] as $country)
                                    <option value="{{ $country->id }}" {{ $country->is_default == 'Default' ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            @endisset
                        </select>

                        <label class="lbl" for="citySection">City</label>
                        <select name="city_id" class="form-control citySection" id="citySection">
                            <option value="">Choose One</option>
                        </select>

                        <label class="lbl" for="loc_id">Area</label>
                        <select class="form-control loc_id" name="loc_id" id="loc_id">
                            <option value="">Choose One</option>
                        </select>
                    </div>
                </div>

                <div class="bk-panel bk-duration">
                    <h3 class="units-section-header">Duration</h3>
                    <div class="units-section-body">
                        <p class="bk-duration-lead">Let us know approximately how long you want to have your unit</p>
                        <input type="range" id="bk-duration" min="0" max="4" value="2" step="1" aria-label="Storage duration">
                        <div class="bk-duration-marks">
                            <span>1 month</span>
                            <span>3 months</span>
                            <span>6 months</span>
                            <span>8 months</span>
                            <span>+</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bk-inquire">
                <a href="#bk-results" class="sk-btn sk-btn-primary">Inquire now</a>
            </div>

            <div class="bk-pair bk-extra">
                <div class="bk-panel units-section">
                    <h3 class="units-section-header">Unit Level</h3>
                    <div class="units-section-body">
                        @isset($data)
                            @foreach ($data['sl'] as $sl)
                                <div class="form-check">
                                    <input class="form-check-input storagelevelfilter" type="checkbox" value="{{ $sl->id }}" name="stlevelval" id="level-{{ $sl->id }}" checked>
                                    <label class="check-container" for="level-{{ $sl->id }}">{{ $sl->name }}</label>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>

                <div class="bk-panel units-section">
                    <h3 class="units-section-header">Storage Type</h3>
                    <div class="units-section-body">
                        @isset($data)
                            @foreach ($data['st'] as $st)
                                <div class="form-check">
                                    <input class="form-check-input storagetypefilter" type="checkbox" value="{{ $st->id }}" name="stypeval" id="type-{{ $st->id }}" checked>
                                    <label class="check-container" for="type-{{ $st->id }}">{{ $st->name }}</label>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>

            <div class="bk-results" id="bk-results">
                    <div class="bk-results-head">
                        <div>
                            <h2>Available Units</h2>
                            <p id="search_title">Choose a location and filters to view units.</p>
                        </div>
                        <div class="bk-head-actions">
                            <label class="bk-perpage" for="bk-per-page">
                                Show
                                <select id="bk-per-page">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="all">All</option>
                                </select>
                            </label>
                            <span class="bk-count" id="bk-count"></span>
                        </div>
                    </div>
                    <div class="product-section" id="results">
                        <div class="bk-empty">Searching available units…</div>
                    </div>
                    <div class="bk-pager" id="bk-pagination" hidden>
                        <p class="bk-range" id="bk-pager-range"></p>
                        <nav class="bk-pages" id="bk-pages"></nav>
                    </div>
                </div>
        </div>
    </section>

    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#bk-search"><i class="fas fa-search"></i> Search</a>
    </div>
</div>
@endsection

@section('javascriptWork')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var slevel = [];
            var stype = [];
            var ssize = '';
            var allUnits = [];
            var fanUnits = [];
            var fanIndex = 0;
            var addonList = [];
            var currentPage = 1;
            var perPage = 5;
            var filterReq = 0;
            var unitImg = @json(asset('sk-assets/assets/images/frontend/blog/Image_8.png'));
            var reserveBase = @json(url('reservation'));
            var durationMarks = ['1 month', '3 months', '6 months', '8 months', '8 months+'];

            function isChosen(row) {
                return row.is_defult == '1' || row.is_default === true || row.is_default === 1 || row.is_default === '1' || row.is_default === 'Default';
            }

            function sizeHint(label) {
                var n = parseInt(String(label).replace(/[^0-9]/g, ''), 10);
                if (!n) return 'Compare a size';
                if (n <= 10) return 'Locker size';
                if (n <= 30) return 'Closet size';
                if (n <= 50) return 'Garage size';
                if (n <= 100) return 'Room size';
                if (n <= 200) return 'Apartment size';
                return 'Warehouse size';
            }

            var country_id = $('select[name=country_id]').val();
            getCities(country_id);
            var city_id = $('.citySection').val();
            getLocations(city_id);
            var $fifty = $('.size-container').filter(function () {
                return /50/.test($(this).text());
            }).first();
            if ($fifty.length) {
                $fifty.addClass('checked');
                ssize = $fifty.attr('data');
                syncSizeVisual();
            }
            setTimeout(function () {
                getFilterResults();
            }, 0);

            $("#country_id").on('change', function() {
                getCities($(this).val());
                getFilterResults(slevel, stype, ssize);
            });

            function getCities(country_id) {
                $.ajax({
                    url: '{{ url('get-cities') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { country_id: country_id },
                    success: function(data) {
                        $('.citySection').empty();
                        getLocations();
                        var html3 = '';
                        $('.citySection').html('<option value="">Select City</option>');
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html3 += '<option ' + (isChosen(data[i]) ? 'selected' : '') + ' value="' + data[i].id + '">' + data[i].city_name + '</option>';
                            }
                        } else {
                            html3 = '<option value="">No Cities Found</option>';
                        }
                        $('.citySection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".citySection").on('change', function() {
                getLocations($(this).val());
                getFilterResults(slevel, stype, ssize);
            });

            function getLocations(city_id) {
                $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { city_id: city_id },
                    success: function(data) {
                        $('.loc_id').empty();
                        var html3 = '';
                        $('.loc_id').html('<option value="">Select Location</option>');
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html3 += '<option ' + (isChosen(data[i]) ? 'selected' : '') + ' value="' + data[i].id + '">' + data[i].loc_name + '</option>';
                            }
                        } else {
                            html3 = '<option value="">No Location Found</option>';
                        }
                        $('.loc_id').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".loc_id").on('change', function() {
                var countryName = $('#country_id').find(':selected').text();
                var cityName = $('#citySection').find(':selected').text();
                var locName = $('#loc_id').find(':selected').text();
                $('#search_title').html(countryName + ' · ' + cityName + ' · ' + locName);
                getFilterResults(slevel, stype, ssize);
            });

            $(".storagelevelfilter").click(function() {
                slevel = [];
                $(".storagelevelfilter").each(function() {
                    var item = $(this).val();
                    if ($(this).is(':checked') && slevel.indexOf(item) === -1) slevel.push(item);
                });
                getFilterResults(slevel, stype, ssize);
            });

            $(".storagetypefilter").click(function() {
                stype = [];
                $(".storagetypefilter").each(function() {
                    var item = $(this).val();
                    if ($(this).is(':checked') && stype.indexOf(item) === -1) stype.push(item);
                });
                getFilterResults(slevel, stype, ssize);
            });

            $(".size-container").on('click', function() {
                if ($(this).hasClass('checked')) return;
                $('.size-container').removeClass('checked');
                $(this).addClass('checked');
                ssize = $(this).attr('data');
                fanIndex = 0;
                applySizeView();
            });

            $('#bk-duration').on('input change', function() {
                renderUnits();
            });

            function currentDuration() {
                var index = parseInt($('#bk-duration').val(), 10) || 0;
                return durationMarks[index] || durationMarks[0];
            }

            function syncSizeVisual() {
                var $all = $('.size-container');
                var $on = $all.filter('.checked');
                var total = $all.length || 1;
                var index = $on.length ? $all.index($on) : -1;
                var pct = index < 0 ? 0 : Math.round(((index + 0.5) / total) * 100);
                var $fill = $('#bk-size-fill');
                $fill.css('width', pct + '%');
                $fill.toggleClass('is-set', index >= 0);
                var label = $on.length ? $.trim($on.find('span').text()) : 'All sizes';
                $('#bk-size-readout').text(label);
                $('#bk-size-hint').text($on.length ? sizeHint(label) : 'Select a size');
            }

            function applySizeView() {
                syncSizeVisual();
                var sizeLabel = $.trim($('.size-container.checked span').text());
                if (ssize) {
                    allUnits.sort(function(a, b) {
                        return (sizeMatches(a, ssize) ? 0 : 1) - (sizeMatches(b, ssize) ? 0 : 1);
                    });
                }
                fanUnits = [];
                for (var u = 0; u < allUnits.length; u++) {
                    if (!ssize || sizeMatches(allUnits[u], ssize)) fanUnits.push(allUnits[u]);
                }
                if (fanIndex >= fanUnits.length) fanIndex = 0;
                if (ssize && sizeLabel && !fanUnits.length && allUnits.length) {
                    $('#search_title').text('No ' + sizeLabel + ' in this area. Showing the other available units.');
                }
                currentPage = 1;
                renderUnits();
            }

            $('#bk-per-page').on('change', function() {
                var val = $(this).val();
                perPage = (val === 'all') ? 0 : (parseInt(val, 10) || 5);
                currentPage = 1;
                renderUnits();
            });

            $(document).on('click', '#bk-pagination a[data-page]', function(e) {
                e.preventDefault();
                var page = parseInt($(this).attr('data-page'), 10);
                if (!page || page === currentPage) return;
                currentPage = page;
                renderUnits();
                var results = document.getElementById('bk-results');
                if (results) results.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            function sizeMatches(unit, sizeId) {
                if (!sizeId || !unit) return false;
                if (String(unit.ssize_id) === String(sizeId)) return true;
                var sizeName = unit.storagesize ? unit.storagesize.unit_type_name : '';
                var selected = $.trim($('.size-container.checked span').text());
                return selected !== '' && sizeName === selected;
            }

            function unitCardHtml(unit) {
                var city = (unit.warehouse && unit.warehouse.loc && unit.warehouse.loc.city) ? unit.warehouse.loc.city.city_name : '';
                var loc = (unit.warehouse && unit.warehouse.loc) ? unit.warehouse.loc.loc_name : '';
                var size = unit.storagesize ? unit.storagesize.unit_type_name : '';
                var level = unit.storagelevel ? unit.storagelevel.name : '';
                var name = unit.storage_unit_name || '';
                var tags = '';
                for (var ie = 0; ie < addonList.length; ie++) {
                    tags += '<span class="feature-name">' + addonList[ie].name + '</span>';
                }
                var picked = sizeMatches(unit, ssize) ? ' is-picked' : '';
                var meta = [];
                if (level) meta.push(level);
                if (name) meta.push('Unit ' + name);
                return '<article class="bk-unit apartment' + picked + '">' +
                    '<div class="bk-unit-img apartment-img" style="background-image:url(\'' + unitImg + '\')"></div>' +
                    '<div class="bk-unit-body apartment-details">' +
                    '<p class="bk-unit-loc city-name-option">' + city + (loc ? ' <span class="area-name">' + loc + '</span>' : '') + '</p>' +
                    '<h3 class="apartment-size">' + size + '</h3>' +
                    (meta.length ? '<p class="bk-unit-meta">' + meta.join(' · ') + '</p>' : '') +
                    (tags ? '<div class="bk-unit-tags apartment-features">' + tags + '</div>' : '') +
                    '</div>' +
                    '<a href="' + reserveBase + '/' + unit.id + '?duration=' + encodeURIComponent(currentDuration()) + '" class="sk-btn sk-btn-primary btn-reserve">Enquire about this unit</a>' +
                    '</article>';
            }

            function pageItem(page, label) {
                if (!page) {
                    return '<span class="bk-page-btn is-gap">' + (label || '…') + '</span>';
                }
                if (page === currentPage) {
                    return '<span class="bk-page-btn is-active">' + (label || page) + '</span>';
                }
                return '<a href="#" data-page="' + page + '" class="bk-page-btn">' + (label || page) + '</a>';
            }

            function visiblePages(current, total) {
                var marks = {};
                var list = [];
                function add(n) {
                    n = parseInt(n, 10);
                    if (n >= 1 && n <= total && !marks[n]) {
                        marks[n] = true;
                        list.push(n);
                    }
                }
                add(1);
                add(total);
                add(current - 1);
                add(current);
                add(current + 1);
                list.sort(function (a, b) { return a - b; });
                var out = [];
                for (var i = 0; i < list.length; i++) {
                    if (i > 0 && list[i] - list[i - 1] > 1) out.push(0);
                    out.push(list[i]);
                }
                return out;
            }

            function renderPagination(totalPages) {
                var $pages = $('#bk-pages');
                if (totalPages <= 1) {
                    $pages.empty();
                    return;
                }
                var html = '';
                html += currentPage > 1
                    ? pageItem(currentPage - 1, '‹')
                    : '<span class="bk-page-btn is-disabled">‹</span>';
                var pages = visiblePages(currentPage, totalPages);
                for (var i = 0; i < pages.length; i++) {
                    html += pages[i] ? pageItem(pages[i]) : pageItem(0, '…');
                }
                html += currentPage < totalPages
                    ? pageItem(currentPage + 1, '›')
                    : '<span class="bk-page-btn is-disabled">›</span>';
                $pages.html(html);
            }

            function sizeIcons(label) {
                var n = parseInt(String(label).replace(/[^0-9]/g, ''), 10);
                if (n <= 10) return ['fa-box', 'fa-suitcase', 'fa-file-alt'];
                if (n <= 30) return ['fa-archive', 'fa-box', 'fa-tshirt'];
                if (n <= 50) return ['fa-couch', 'fa-box', 'fa-dolly'];
                if (n <= 100) return ['fa-bed', 'fa-couch', 'fa-box'];
                if (n <= 200) return ['fa-warehouse', 'fa-boxes', 'fa-couch'];
                return ['fa-warehouse', 'fa-truck', 'fa-boxes'];
            }

            function unitBits(unit) {
                var city = (unit.warehouse && unit.warehouse.loc && unit.warehouse.loc.city) ? unit.warehouse.loc.city.city_name : '';
                var loc = (unit.warehouse && unit.warehouse.loc) ? unit.warehouse.loc.loc_name : '';
                return {
                    size: unit.storagesize ? unit.storagesize.unit_type_name : '',
                    name: unit.storage_unit_name || 'Unit',
                    place: [city, loc].filter(Boolean).join(' · ')
                };
            }

            function renderFan() {
                var $fan = $('#bk-fan');
                var label = $.trim($('.size-container.checked span').text()) || '50 sq. ft.';
                var icons = sizeIcons(label);
                if (!fanUnits.length) {
                    $fan.html('<div class="bk-fan-card is-ghost"></div><div class="bk-fan-card is-ghost"></div><div class="bk-fan-card is-front is-empty"><span class="bk-fan-icons"><i class="fas ' + icons[0] + '"></i></span><span class="bk-fan-meta"><strong>No unit</strong><em>' + label + '</em></span></div><div class="bk-fan-card is-ghost"></div><div class="bk-fan-card is-ghost"></div>');
                    return;
                }
                if (fanIndex >= fanUnits.length) fanIndex = 0;
                var front = fanUnits[fanIndex];
                var bits = unitBits(front);
                var iconHtml = '';
                for (var ic = 0; ic < icons.length; ic++) {
                    iconHtml += '<i class="fas ' + icons[ic] + '"></i>';
                }
                var href = reserveBase + '/' + front.id + '?duration=' + encodeURIComponent(currentDuration());
                var sides = [];
                for (var s = 0; s < fanUnits.length; s++) {
                    if (s !== fanIndex) sides.push(fanUnits[s]);
                }
                while (sides.length < 4) sides.push(null);
                var slots = [sides[0], sides[1], front, sides[2], sides[3]];
                var html = '';
                for (var i = 0; i < slots.length; i++) {
                    if (i === 2) {
                        html += '<a class="bk-fan-card is-front" href="' + href + '"><span class="bk-fan-icons">' + iconHtml + '</span><span class="bk-fan-meta"><strong>' + bits.name + '</strong><em>' + bits.size + '</em><small>' + bits.place + '</small></span></a>';
                    } else if (!slots[i]) {
                        html += '<div class="bk-fan-card is-ghost"><i class="fas ' + icons[i % icons.length] + '"></i></div>';
                    } else {
                        var side = unitBits(slots[i]);
                        var idx = fanUnits.indexOf(slots[i]);
                        html += '<button type="button" class="bk-fan-card" data-idx="' + idx + '" style="background-image:url(\'' + unitImg + '\')"><span class="bk-fan-meta"><strong>' + side.name + '</strong><em>' + side.size + '</em></span></button>';
                    }
                }
                $fan.html(html);
            }

            $(document).on('click', '#bk-fan .bk-fan-card[data-idx]', function() {
                fanIndex = parseInt($(this).attr('data-idx'), 10) || 0;
                renderFan();
            });

            function renderUnits() {
                var total = allUnits.length;
                renderFan();
                if (!total) {
                    $('#bk-count').text('0 units');
                    $('#bk-pager-range').text('');
                    $('#bk-pagination').attr('hidden', true);
                    $('#results').html('<div class="bk-empty"><i class="fas fa-box-open"></i><h3>No units found</h3><p>Try another location, type or size to see available storage units.</p></div>');
                    renderPagination(0);
                    return;
                }

                var size = (perPage > 0) ? perPage : total;
                var totalPages = Math.ceil(total / size) || 1;
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                var start = (currentPage - 1) * size;
                var end = Math.min(start + size, total);
                var html = '';
                for (var i = start; i < end; i++) {
                    html += unitCardHtml(allUnits[i]);
                }

                $('#bk-count').text(total + (total === 1 ? ' unit' : ' units'));
                $('#bk-pager-range').text('Showing ' + (start + 1) + ' to ' + end + ' of ' + total);
                $('#results').html(html);
                $('#bk-pagination').removeAttr('hidden');
                renderPagination(totalPages);
            }

            function checkedValues(selector) {
                var values = [];
                $(selector).each(function() {
                    if ($(this).is(':checked')) values.push($(this).val());
                });
                return values;
            }

            function getFilterResults() {
                slevel = checkedValues('.storagelevelfilter');
                stype = checkedValues('.storagetypefilter');
                var country_id = $('select[name=country_id]').val();
                var city_id = $('select[name=city_id]').val();
                var loc_id = $('select[name=loc_id]').val();
                var req = ++filterReq;

                $.ajax({
                    url: '{{ url('country-wise') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { country_id: country_id, city_id: city_id, id: loc_id, level: slevel, sType: stype },
                    success: function(data) {
                        if (req !== filterReq) return;
                        allUnits = (data.su && data.su.length) ? data.su : [];
                        addonList = data.addon || [];
                        var countryName = $.trim($('#country_id').find(':selected').text());
                        var cityName = $.trim($('#citySection').find(':selected').text());
                        var locName = $.trim($('#loc_id').find(':selected').text());
                        var title = [countryName, cityName, locName].filter(function(part) {
                            return part && part !== 'Choose One' && part !== 'Select City' && part !== 'Select Location';
                        }).join(' · ');
                        if (title) $('#search_title').text(title);
                        fanIndex = 0;
                        applySizeView();
                    },
                    error: function() {
                        if (window.toastr) toastr.error('any technical error');
                    }
                });
            }
        });
    </script>
@endsection
