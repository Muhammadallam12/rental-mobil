@extends('layouts.main')

@section('title')
    User
@endsection

@section('content')
    <div class="mx-1">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <div class="">
                        <h5 class="m-0 font-weight-bold">Tabel User</h5>
                    </div>
                    <div class="d-flex flex-row">
                        <form action="{{ route('user.index') }}" method="get" class="mr-3">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="search" placeholder="Pencarian" name="search" class="form-control"
                                    aria-label="Search" aria-describedby="basic-addon1" value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="table-responsive mt-4">
                    {{-- <table class="table table-bordered table-hover">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Nomor Telephone</th>
                            <th>Nomor SIM</th>
                        </tr>
                        @forelse ($user as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email}}</td>
                                <td>{{ $user->alamat }}</td>
                                <td>{{ $user->no_telp }}</td>
                                <td>{{ $user->no_sim}}</td>
                            </tr>
                        @empty
                            <td colspan="10" class="text-center">Data tidak ada</td>
                        @endforelse
                    </table> --}}
                    {{-- {{ $user->links() }} --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this mobil?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="post" action="">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('page_js')
        <script>
            $(document).ready(function() {
                $('.btn-danger').on('click', function() {
                    var userId = $(this).data('user-id');
                    $('#deleteModal').data('user-id', userId);
                });

                $('#deleteModal').on('show.bs.modal', function() {
                    var userId = $(this).data('user-id');
                    var deleteUrl = "{{ route('user.destroy', ['user' => 'id']) }}";
                    deleteUrl = deleteUrl.replace('id', userId);
                    $('#deleteForm').attr('action', deleteUrl);
                });
            });
        </script>
    @endpush
@endsection
