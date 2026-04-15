@extends('layouts.app')
@section('title', 'Data User')
@section('page_title', 'Kelola User')
@section('content')

<div class="container-fluid">

    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Kelola User</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Daftar User</h4>
                        <p class="text-muted mb-0 small">Kelola data user anda</p>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus me-2"></i>Tambah User
                    </a>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success solid alert-end-icon alert-dismissible fade show">
                            <span><i class="mdi mdi-check"></i></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            Success! {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="example" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 'owner')
                                                <span class="badge badge-primary">Owner</span>
                                            @else
                                                <span class="badge badge-info">Kasir</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-success btn-sm btn-rounded">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                @if ($user->id !== auth()->id())
                                                    <button type="button" class="btn btn-danger btn-sm btn-rounded"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-url="{{ route('user.destroy', $user->id) }}">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

    <!-- Modal Konfirmasi Hapus -->
    @include('delete')
@endsection
