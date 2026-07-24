@php
$pageConfigs = ['layout' => 'front'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Admin Dashboard')

@section('content')
<section class="container py-5">
  <div class="row">
    <div class="col-12">
      <h2 class="mb-4">Admin Dashboard</h2>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card p-4 shadow-sm">
            <h5>Total Ekspresi</h5>
            <p class="display-6">{{ $stats['total'] }}</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 shadow-sm">
            <h5>Menunggu Moderasi</h5>
            <p class="display-6">{{ $stats['pending'] }}</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 shadow-sm">
            <h5>Flagged</h5>
            <p class="display-6">{{ $stats['flagged'] }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
