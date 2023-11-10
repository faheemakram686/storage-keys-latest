@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    <div class="row">
        <div class="col-12">
            <img  src="{{ asset('sk-assets/assets/images/frontend/HeaderImage_1.jpg') }} " class="img-fluid" alt="Header Image">
        </div>
    </div>
    <div class="row booking-section booking-page">
        <div class="container">
            <div class="row">
                <div class="offset-lg-3 col-9 col-md-9 col-lg-9 col-sm-12">
                    <div class="row list-of-cities">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                            <div class="city-name-option" data-city-id="1" id="search_title"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-3 filter-section">
                    <div class="row locations-section">
                        <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-8 col-lg-7 locations-section-header">Locations</div>
                        <div class="col-11 locations-section-body">
                            <label class="lbl" >Country</label>
                            <div class="row">
                                <select class="selectpicker form-control " name="country_id" id="country_id">
                                    <option value=""  >Choose One</option>
                                    @isset($data)
                                        @foreach ($data['loc'] as $country)
                                            <option value="{{ $country->id }}" {{(($country->is_defult == 'Default')?  "selected" : "" )}}>{{$country->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <label  class="lbl" >City</label>
                            <div class="row">
                                <select name="city_id" class=" selectpicker form-control  citySection" id="citySection" >
                                    <option value="">Choose One</option>
                                </select>
                            </div>
                            <label  class="lbl" >Location</label>
                            <div class="row">
                                <select class="selectpicker form-control loc_id" name="loc_id" id="loc_id">
                                    <option value="">Choose One</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 row units-section">
                        <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-7 col-lg-7 units-section-header">
                            Unit Level</div>
                        <div class="col-11 units-section-body">
                            @isset($data)
                                @foreach ($data['sl'] as $sl)
                                    <div class="form-check">
                                        <input class="form-check-input storagelevelfilter" type="checkbox" value="{{ $sl->id }}" name="stlevelval" id="flexCheckDefault" checked />
                                        <label class="check-container" for="flexCheckDefault">{{$sl->name }}</label>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                    <div class="mt-3 row units-section">
                        <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-7 col-lg-7 units-section-header">
                            Storage Unit Type</div>
                        <div class="col-11 units-section-body">
                            @isset($data)
                                @foreach ($data['st'] as $st)
                                    <div class="form-check">
                                        <input class="form-check-input storagetypefilter" type="checkbox" value="{{ $st->id }}" name="stypeval" id="flexCheckDefault" checked />
                                        <label class="check-container" for="flexCheckDefault">{{$st->name }}</label>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                    <div class="mt-3 row size-sqft-section mb-10">
                        <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-7 col-lg-7 size-sqft-section-header">
                            Size (sqft)</div>
                        <div class="col-11 size-sqft-section-body">
                            @isset($data)
                                @foreach ($data['ss'] as $ss)
                                    <div class="size-container checked" data="{{ $ss->id }}">
                                        <span>{{$ss->unit_type_name }}</span>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-9 product-section" id="results">
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var slevel = [];
            var stype = [];
            var ssize = '';
            var country_id=$('select[name=country_id]').val();
            getCities(country_id);
            var city_id = $('.citySection').val();
            getLocations(city_id);
            getFilterResults(slevel,stype,ssize);

            $("#country_id").on('change', function() {

                var country_id = $(this).val();

                getCities(country_id);
                var countryName = $('#country_id').find(":selected").text();
                var html = '<span class="cursor-pointer" >'+ countryName +'</span>';
                //   $('#search_title').html(html);
                getFilterResults(slevel,stype,ssize);

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
                        var i;
                        var c = 0;
                        $('.citySection').html('<option value="">Select City</option>');
                        if (data.length > 0) {

                            for (i = 0; i < data.length; i++) {
                                html3 += '<option '+ ((data[i].is_defult == 'Default') ? 'selected' : '')+' value="' + data[i].id + '">' + data[i].city_name + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Cities Found</option>';

                        }

                        $('.citySection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            $(".citySection").on('change', function() {
                var city_id = $(this).val();
                var countryName = $('#country_id').find(":selected").text();
                var cityName = $('#citySection').find(":selected").text();
                var html = '<span class="cursor-pointer" >'+ countryName +'</span><span class="area-name">-'+  cityName +'</span>';
                //  $('#search_title').html(html);
                getLocations(city_id);
                getFilterResults(slevel,stype,ssize);
            });

            function getLocations(city_id) {
                // alert(city_id);
                $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { city_id: city_id },
                    success: function(data) {

                        $('.loc_id').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.loc_id').html('<option value="">Select Location</option>');
                        if (data.length > 0) {

                            for (i = 0; i < data.length; i++) {
                                c++;
                                html3 += ' <option '+ ((data[i].is_defult == 'Default' )? 'selected' : '')+'  value="'+data[i].id+'"> '+data[i].loc_name+'</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Location Found</option>';

                        }

                        $('.loc_id').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            $(".loc_id").on('change', function() {
                var loc_id = $(this).val();
                var countryName = $('#country_id').find(":selected").text();
                var cityName = $('#citySection').find(":selected").text();
                var locName = $('#loc_id').find(":selected").text();
                var html = '<span class="cursor-pointer" >'+ countryName +'</span><span class="area-name">-'+  cityName + locName +'</span>';
                $('#search_title').html(html);
                getFilterResults(slevel,stype,ssize);
            });

            $(".storagelevelfilter").click(function(){
                slevel=[];
                $(".storagelevelfilter").each(function(){
                    var item = $(this).val();
                    if($(this).is(":checked"))
                        if (slevel.indexOf(item) === -1) slevel.push(item);
                });
                getFilterResults(slevel,stype,ssize);
            });

            $(".storagetypefilter").click(function(){
                stype=[];
                $(".storagetypefilter").each(function(){
                    var item = $(this).val();
                    if($(this).is(":checked"))
                        if (stype.indexOf(item) === -1) stype.push(item);

                });
                getFilterResults(slevel,stype,ssize);
            });

            $(".size-container").click(function(){
                ssize = $(this).attr('data');
                getFilterResults(slevel,stype,ssize);
                $(this).toggleClass('checked');

            });

            function getFilterResults(slevel,stype,ssize) {

                var country_id=$('select[name=country_id]').val();
                var city_id=$('select[name=city_id]').val();
                var loc_id=$('select[name=loc_id]').val();



                $.ajax({
                    url: '{{ url('country-wise') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { country_id:country_id,city_id:city_id, id: loc_id,level:slevel,sType:stype,sSize:ssize},
                    success: function(data) {

                        console.log(data);
                        var html = '';
                        var i;
                        var c = 0;
                        var ie;
                        var co = 0;

                        if (data.su.length > 0) {
                            for (i = 0; i < data.su.length; i++) {
                                c++;

                                html += ' <div class="row apartment" style="width: inherit; border: 2px solid var(--border-color-1);" >' +
                                    '<div class="offset-3 offset-sm-3 offset-md-0 offset-lg-0 col-6 col-sm-6 col-lg-3 col-md-3 apartment-img">' +
                                    ' <img  src="{{ asset('sk-assets/assets/images/frontend/blog/Image_8.png') }}" class="img-fluid" alt="Image of Apartment">' +
                                    '<div class="image-container">'+
                                    ' <a href="javascript:void(0)" class=" hover-button btn-check-map  active show-unit-image" role="button" aria-pressed="true">View Storage <i class="fa fa-map-marker-alt"></i></a>' +
                                    
                                    '</div>' +
                                    '</div>' +
                                    ' <div class="col-12 col-sm-12 col-lg-6 col-md-6 apartment-details">' +
                                    ' <div class="col-12 city-name-option">' + data.su[i].warehouse.loc.city.city_name + ' <span class="area-name">' + data.su[i].warehouse.loc.loc_name + '</span></div>' +
                                    '<div class="col-12 apartment-size">' + data.su[i].storagesize.unit_type_name + ' (' + data.su[i].storagelevel.name + '/Unit No:' + data.su[i].storage_unit_name + ')</div>' +
                                    '  <p class="col-12 apartment-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel egestas dolor, nec dignissim metus. Donec augue elit, rhoncus ac sodales id, porttitor vitae est.</p>' +
                                    '   <div class="col-12 apartment-reserve">' +
                                    '   <a href="{{url('reservation')}}/' + data.su[i].id + '" class="btn btn-sm  btn-reserve active" role="button" aria-pressed="true">for enquiry</a>' +
                                    '</div>' +
                                    '</div>' +
                                    ' <div class="col-12 col-sm-12 col-lg-3 col-md-12 apartment-features">' ;
                                for (ie = 0; ie < data.addon.length; ie++) {
                                    co++;
                                    html += ' <div class="feature-name d-inline-flex d-lg-block">' + data.addon[ie].name + '</div>';
                                }
                                html +=  '</div>' +
                                    '</div>';


                            }
                        }else {
                            var html = '<div class="city-name-option" ><span>No Results Found</span></div>';

                        }
                        $('#results').html(html);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }
        });
    </script>
@endsection

