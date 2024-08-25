@extends('dashboard.layouts.layout')
@section('content')
    <div class="nk-app-root">
        <div class="nk-main ">
            @include('dashboard.layouts.sidebar')

            <div class="nk-wrap ">
                @include('dashboard.layouts.navbar')

                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Classes</h3>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle"><a href="#"
                                                    class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                    data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="more-options">
                                                    <ul class="nk-block-tools g-3">

                                                        <li class="nk-block-tools-opt"><a
                                                                class="btn btn-icon btn-warning d-md-none"
                                                                data-bs-toggle="modal" href="#student-add"><em
                                                                    class="icon ni ni-plus"></em></a>
                                                            @can('class-create')
                                                                <a href="{{ route('classes.create') }}"
                                                                    class="btn btn-primary d-none d-md-inline-flex">
                                                                    <em class="icon ni ni-plus"></em>
                                                                    <span>Add</span>
                                                                </a>
                                                            @endcan
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Sec Name</th>
                                            <th scope="col">Program</th>
                                            <th scope="col">Stage</th>
                                            <th scope="col">Teacher</th>
                                            <th scope="col">School</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $class)
                                            <tr>
                                                <th scope="row">{{ $class->id }}</th>
                                                <td>{{ $class->name }}</td>
                                                <td>{{ $class->sec_name }}</td>
                                                <td>{{ optional($class->program)->name }} {{ $class->program ? \App\Models\Program::join('courses','programs.course_id','courses.id')->where('programs.id',$class->program->id ?? '0')->select('courses.name as name')->first()->name : '-' }}</td>
                                                <td>{{ optional($class->stage)->name }}</td>
                                                <td>{{ optional($class->teacher)->name ?? 'Na' }}</td>
                                                <td>{{ optional($class->school)->name }}</td>

                                                <td class="d-flex flex-row justify-content-center">
                                                    @can('class-edit')
                                                        <a href="{{ route('classes.edit', $class->id) }}"
                                                            class="btn btn-warning me-1">Edit</a>
                                                    @endcan
                                                    @can('class-delete')
                                                        <form id="delete-form-{{ $class->id }}"
                                                            action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="d-lg-flex d-none"></div>
                                                        </form>
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="confirmDelete({{ $class->id }})">Delete</button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="card-inner">
                                    <div class="nk-block-between-md g-3">
                                        {!! $classes->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('dashboard.layouts.footer')
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        function confirmDelete(classId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + classId).submit();
                }
            })
        }
    </script>
@endsection
