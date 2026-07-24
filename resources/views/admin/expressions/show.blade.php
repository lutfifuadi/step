@php
$pageConfigs = ['layout' => 'front'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Detail Ekspresi')

@section('content')
<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm p-4">
        <h3 class="mb-3">Detail Ekspresi #{{ $expression->id }}</h3>
        <dl class="row">
          <dt class="col-sm-4">Kategori</dt>
          <dd class="col-sm-8">{{ $expression->category?->name ?? '-' }}</dd>
          <dt class="col-sm-4">Nama Tampil</dt>
          <dd class="col-sm-8">{{ $expression->display_name }}</dd>
          <dt class="col-sm-4">Asal</dt>
          <dd class="col-sm-8">{{ $expression->origin ?? '-' }}</dd>
          <dt class="col-sm-4">Status</dt>
          <dd class="col-sm-8">{{ ucfirst($expression->status) }}</dd>
          <dt class="col-sm-4">Isi</dt>
          <dd class="col-sm-8">{{ $expression->content }}</dd>
          @if($expression->is_risky)
          <dt class="col-sm-4">Flag Risiko</dt>
          <dd class="col-sm-8 text-danger">Ya</dd>
          <dt class="col-sm-4">Kata Kunci Risiko</dt>
          <dd class="col-sm-8">{{ implode(', ', $expression->risk_keywords ?? []) }}</dd>
          @endif
        </dl>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="d-flex gap-2 align-items-start mt-3">
          <form action="{{ route('admin.expressions.approve', $expression) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Setujui</button>
          </form>
          <form action="{{ route('admin.expressions.flag', $expression) }}" method="POST" class="w-100">
            @csrf
            <div class="mb-3">
              <label for="note" class="form-label">Alasan Flag (Minimal 10 Karakter)</label>
              <textarea name="note" id="note" class="form-control" rows="3" required placeholder="Masukkan alasan flag...">{{ old('note') }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning">Flag</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
