@extends('layouts.admin-layout')

@section('title', 'Daftar Ekspresi')

@section('content')
  <div class="row">
    <div class="col-12">
      <h2 class="mb-4">Daftar Ekspresi</h2>
      @if($expressions->count())
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Kategori</th>
              <th>Nama</th>
              <th>Status</th>
              <th>Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($expressions as $expression)
            <tr>
              <td>{{ $expression->id }}</td>
              <td>{{ $expression->category?->name ?? '-' }}</td>
              <td>{{ $expression->display_name }}</td>
              <td>{{ ucfirst($expression->status) }}</td>
              <td><a href="{{ route('admin.expressions.show', $expression) }}" class="btn btn-sm btn-primary">Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $expressions->links() }}
      @else
      <div class="alert alert-secondary">Belum ada ekspresi untuk ditampilkan.</div>
      @endif
    </div>
  </div>
@endsection
