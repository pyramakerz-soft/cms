<!-- resources/views/dashboard/reports/class/select_group.blade.php -->

@extends('dashboard.layouts.layout')

@section('content')
    <div class="nk-app-root">
        <div class="nk-main">
            @include('dashboard.layouts.sidebar')
            <div class="nk-wrap">
                @include('dashboard.layouts.navbar')
                <div class="nk-content">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="title">Select Group for Reports</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('reports.classCompletionReportWeb') }}">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="group_id">Select Group</label>
                                                    <select class="form-select js-select2" name="group_id" id="group_id"
                                                        required>
                                                        <option value="" disabled selected>Choose a group</option>
                                                        @foreach ($groups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary mt-4">View
                                                        Reports</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('dashboard.layouts.footer')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>
@endsection
