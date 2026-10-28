{{-- Every location page (city overview and city service pages). Content: App\Support\LocationPages. --}}
@extends('ui.layouts.frontend')
@section('title', '| ' . $loc['crumb'])
@section('metaTitle', $loc['metaTitle'])
@section('metaDescription', $loc['metaDescription'])

@section('headExtra')
    @include('ui.partials.location-schema', ['loc' => $loc])
@endsection

@section('content')
    @include('ui.partials.location-page', ['loc' => $loc])
@endsection
