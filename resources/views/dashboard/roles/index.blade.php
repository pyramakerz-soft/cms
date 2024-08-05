@extends('dashboard.layouts.layout')

@section('content')
    <div class="nk-app-root">
        <div class="nk-main">
            @include('dashboard.layouts.sidebar')
            <div class="nk-wrap ">
                @include('dashboard.layouts.navbar')

                <div class="nk-content ">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-lg-12 margin-tb mb-4">
                                <div class="pull-left">
                                    <h2>Role Management
                                        <div class="float-end">
                                            @can('role-create')
                                                <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New
                                                    Role</a>
                                            @endcan
                                        </div>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Name</th>
                                <th width="280px">Action</th>
                            </tr>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                            <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a>
                                            @can('role-edit')
                                                <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                            @endcan


                                            @csrf
                                            @method('DELETE')
                                            @can('product-delete')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {!! $roles->render() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
