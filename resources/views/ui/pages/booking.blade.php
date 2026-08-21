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
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Booking</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Book a Unit</span>
            <h1>Find and book your <span>storage unit</span></h1>
            <p class="lead">Search available units by location, type and size. Choose a unit that fits your needs and send an enquiry to reserve it.</p>
            <div class="ps-hero-cta">
                <a href="#bk-search" class="sk-btn sk-btn-primary"><i class="fas fa-search"></i> Search Units</a>
                <a href="{{ url('/contact-us') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-file-invoice-dollar"></i> Get A Quote</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-map-marker-alt"></i> Sharjah &amp; Dubai</span>
                <span class="ps-hbadge"><i class="fas fa-ruler-combined"></i> Multiple sizes</span>
                <span class="ps-hbadge"><i class="fas fa-temperature-low"></i> Climate-controlled</span>
                <span class="ps-hbadge"><i class="fas fa-clock"></i> 24/7 access</span>
            </div>
        </div>
    </section>

    <div class="ps-trust ct-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-search"></i> Filter by location</div>
                <div class="ps-trust-i"><i class="fas fa-layer-group"></i> Choose unit level</div>
                <div class="ps-trust-i"><i class="fas fa-th-large"></i> Pick a size</div>
                <div class="ps-trust-i"><i class="fas fa-paper-plane"></i> Enquire to reserve</div>
            </div>
        </div>
    </div>

    <!-- ============ SEARCH ============ -->
    <section class="sk-section booking-section booking-page" id="bk-search">
        <div class="sk-container">
            <div class="bk-layout">
                <aside class="bk-filters filter-section">
                    <div class="bk-filter-card locations-section">
                        <h3 class="locations-section-header">Location</h3>
                        <div class="locations-section-body">
                            <label class="lbl" for="country_id">Country</label>
                            <select class="form-control" name="country_id" id="country_id">
                                <option value="">Choose One</option>
                                @isset($data)
                                    @foreach ($data['loc'] as $country)
                                        <option value="{{ $country->id }}" {{ $country->is_defult == '1' ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                @endisset
                            </select>

                            <label class="lbl" for="citySection">City</label>
                            <select name="city_id" class="form-control citySection" id="citySection">
                                <option value="">Choose One</option>
                            </select>

                            <label class="lbl" for="loc_id">Location</label>
                            <select class="form-control loc_id" name="loc_id" id="loc_id">
                                <option value="">Choose One</option>
                            </select>
                        </div>
                    </div>

                    <div class="bk-filter-card units-section">
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

                    <div class="bk-filter-card units-section">
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

                    <div class="bk-filter-card size-sqft-section">
                        <h3 class="size-sqft-section-header">Size (sqft)</h3>
                        <div class="size-sqft-section-body">
                            @isset($data)
                                @foreach ($data['ss'] as $ss)
                                    <div class="size-container" data="{{ $ss->id }}">
                                        <span>{{ $ss->unit_type_name }}</span>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </aside>

                <div class="bk-results">
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
        </div>
    </section>

    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
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
            var addonList = [];
            var currentPage = 1;
            var perPage = 5;
            var unitImg = @json(asset('sk-assets/assets/images/frontend/blog/Image_8.png'));
            var reserveBase = @json(url('reservation'));

            var country_id = $('select[name=country_id]').val();
            getCities(country_id);
            var city_id = $('.citySection').val();
            getLocations(city_id);
            getFilterResults(slevel, stype, ssize);

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
                                html3 += '<option ' + ((data[i].is_defult == '1') ? 'selected' : '') + ' value="' + data[i].id + '">' + data[i].city_name + '</option>';
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
                                html3 += '<option ' + ((data[i].is_defult == '1') ? 'selected' : '') + ' value="' + data[i].id + '">' + data[i].loc_name + '</option>';
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

            $(".size-container").click(function() {
                if ($(this).hasClass('checked')) {
                    $(this).removeClass('checked');
                    ssize = '';
                } else {
                    $('.size-container').removeClass('checked');
                    $(this).addClass('checked');
                    ssize = $(this).attr('data');
                }
                getFilterResults(slevel, stype, ssize);
            });

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
                var search = document.getElementById('bk-search');
                if (search) search.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

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
                return '<article class="bk-unit apartment">' +
                    '<div class="bk-unit-img apartment-img" style="background-image:url(\'' + unitImg + '\')"></div>' +
                    '<div class="bk-unit-body apartment-details">' +
                    '<div class="city-name-option">' + city + ' <span class="area-name">' + loc + '</span></div>' +
                    '<h3 class="apartment-size">' + size + ' <span>(' + level + ' / Unit ' + name + ')</span></h3>' +
                    '<div class="bk-unit-tags apartment-features">' + tags + '</div>' +
                    '<div class="apartment-reserve"><a href="' + reserveBase + '/' + unit.id + '" class="sk-btn sk-btn-primary btn-reserve">Enquire about this unit</a></div>' +
                    '</div></article>';
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

            function renderUnits() {
                var total = allUnits.length;
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

            function getFilterResults(slevel, stype, ssize) {
                var country_id = $('select[name=country_id]').val();
                var city_id = $('select[name=city_id]').val();
                var loc_id = $('select[name=loc_id]').val();

                $.ajax({
                    url: '{{ url('country-wise') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { country_id: country_id, city_id: city_id, id: loc_id, level: slevel, sType: stype, sSize: ssize },
                    success: function(data) {
                        allUnits = (data.su && data.su.length) ? data.su : [];
                        addonList = data.addon || [];
                        currentPage = 1;
                        renderUnits();
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }
        });
    </script>
@endsection
