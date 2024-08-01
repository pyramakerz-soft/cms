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
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Schools</h3>
                                        </div>
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle"><a href="#"
                                                    class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                    data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="more-options">
                                                    <ul class="nk-block-tools g-3">

                                                        <li class="nk-block-tools-opt"><a
                                                                class="btn btn-icon btn-primary d-md-none"
                                                                data-bs-toggle="modal" href="#student-add"><em
                                                                    class="icon ni ni-plus"></em></a>
                                                            <a href="{{ route('programs.create') }}"
                                                                class="btn btn-primary d-none d-md-inline-flex">
                                                                <em class="icon ni ni-plus"></em>
                                                                <span>Add</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">School</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Stage</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($programs as $program)
                                            <tr>
                                                <th scope="row">{{ $program->id }}</th>
                                                <td>{{ $program->name }}</td>
                                                <td>{{ $program->school->name }}</td>
                                                <td>{{ $program->course->name }}</td>
                                                <td>{{ $program->stage->name }}</td>
                                                <td class="d-flex flex-row justify-content-end">
                                                    <a href="{{ route('programs.edit', $program->id) }}"
                                                        class="btn btn-warning me-1">Edit</a>



                                                    <form action="{{ route('programs.destroy', $program->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this program?')">Delete</button>

                                                        <div class="d-lg-flex d-none">

                                                        </div>

                                                    </form>
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
            @include('dashboard.layouts.footer')

        </div>
    </div>
    </div>
@endsection
