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
                                        <h5 class="title">Student Progress Reports</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="student-selection-form">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="student_id">Select Student</label>
                                                    <select class="form-select js-select2" name="student_id"
                                                        id="student_id">
                                                        @foreach ($students as $student)
                                                            <option value="{{ $student->id }}">{{ $student->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-primary mt-4"
                                                        id="fetch-reports">View Reports</button>
                                                </div>
                                            </div>
                                        </form>
                                        <ul class="nav nav-tabs mt-4" id="reportTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="completion-report-tab" data-toggle="tab"
                                                    href="#completion-report" role="tab"
                                                    aria-controls="completion-report" aria-selected="true">Completion
                                                    Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="mastery-report-tab" data-toggle="tab"
                                                    href="#mastery-report" role="tab" aria-controls="mastery-report"
                                                    aria-selected="false">Mastery Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="num-of-trials-report-tab" data-toggle="tab"
                                                    href="#num-of-trials-report" role="tab"
                                                    aria-controls="num-of-trials-report" aria-selected="false">Number of
                                                    Trials Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="skill-report-tab" data-toggle="tab"
                                                    href="#skill-report" role="tab" aria-controls="skill-report"
                                                    aria-selected="false">Skill Report</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-4">
                                            <!-- Completion Report Tab -->
                                            <div class="tab-pane fade show active" id="completion-report" role="tabpanel"
                                                aria-labelledby="completion-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.completionReport') }}">
                                                        <div class="form-row">
                                                            <div class="col-md-3">
                                                                <label for="program_id">Program</label>
                                                                <select class="form-select js-select2" name="program_id"
                                                                    id="program_id">
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}">
                                                                            {{ $program->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="from_date">From Date</label>
                                                                <input type="date" class="form-control" name="from_date"
                                                                    id="from_date">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="to_date">To Date</label>
                                                                <input type="date" class="form-control" name="to_date"
                                                                    id="to_date">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="status">Status</label>
                                                                <select class="form-select js-select2" name="status"
                                                                    id="status">
                                                                    <option value="Completed">Completed</option>
                                                                    <option value="Overdue">Overdue</option>
                                                                    <option value="Pending">Pending</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-3">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="report-container mt-4"></div>
                                            </div>
                                            <!-- Mastery Report Tab -->
                                            <div class="tab-pane fade" id="mastery-report" role="tabpanel"
                                                aria-labelledby="mastery-report-tab">
                                                @include('dashboard.reports.mastery_report')
                                            </div>
                                            <!-- Number of Trials Report Tab -->
                                            <div class="tab-pane fade" id="num-of-trials-report" role="tabpanel"
                                                aria-labelledby="num-of-trials-report-tab">
                                                @include('dashboard.reports.num_of_trials_report')
                                            </div>
                                            <!-- Skill Report Tab -->
                                            <div class="tab-pane fade" id="skill-report" role="tabpanel"
                                                aria-labelledby="skill-report-tab">
                                                @include('dashboard.reports.skill_report')
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
        </div>
    </div>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
            // Initialize select2 for the filters
            $('.js-select2').select2();

            // Function to fetch and display reports
            function fetchReport(url, form, container) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: form.serialize(),
                    beforeSend: function() {
                        container.html(
                            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        container.html(renderReport(response));
                    },
                    error: function(xhr, status, error) {
                        container.html(
                            '<div class="alert alert-danger">Error fetching data. Please try again.</div>'
                        );
                    }
                });
            }

            // Function to render report HTML
            function renderReport(data) {
                let html = '';

                if (data.counts) {
                    html += `<h4>Latest Progress: ${data.student_latest}</h4>`;
                    html += `
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Completed</div>
                                <div class="card-body">
                                    <h5 class="card-title">${data.counts.completed}</h5>
                                    <p class="card-text">${data.assignments_percentages.completed}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-header">Overdue</div>
                                <div class="card-body">
                                    <h5 class="card-title">${data.counts.overdue}</h5>
                                    <p class="card-text">${data.assignments_percentages.overdue}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">Pending</div>
                                <div class="card-body">
                                    <h5 class="card-title">${data.counts.pending}</h5>
                                    <p class="card-text">${data.assignments_percentages.pending}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th>Test</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    data.tests.forEach(test => {
                        html += `
                            <tr>
                                <td>${test.tests.name}</td>
                                <td>${test.start_date}</td>
                                <td>${test.due_date}</td>
                                <td>${test.status == 1 ? 'Completed' : (new Date(test.due_date) < new Date() ? 'Overdue' : 'Pending')}</td>
                            </tr>`;
                    });

                    html += `
                            </tbody>
                        </table>`;
                } else {
                    // Handle other report types (e.g., mastery report)
                    html += `<h4>Mastery Report</h4>`;
                    html += `<h5>Units Mastery</h5>`;
                    html += `<table class="table table-striped mt-4">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Failed</th>
                                        <th>Introduced</th>
                                        <th>Practiced</th>
                                        <th>Mastered</th>
                                        <th>Mastery Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    data.units.forEach(unit => {
                        html += `
                            <tr>
                                <td>${unit.name}</td>
                                <td>${unit.failed}</td>
                                <td>${unit.introduced}</td>
                                <td>${unit.practiced}</td>
                                <td>${unit.mastered}</td>
                                <td>${unit.mastery_percentage}%</td>
                            </tr>`;
                    });

                    html += `
                                </tbody>
                            </table>`;
                }

                return html;
            }

            // Event listeners for the filter forms
            $('.filter-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let container = form.closest('.tab-pane').find('.report-container');
                fetchReport(url, form, container);
            });

            // Fetch reports when a student is selected
            $('#fetch-reports').click(function() {
                let studentId = $('#student_id').val();
                let forms = $('.filter-form');

                forms.each(function() {
                    let form = $(this);
                    let url = form.attr('action') + '?student_id=' + studentId;
                    let container = form.closest('.tab-pane').find('.report-container');
                    fetchReport(url, form, container);
                });
            });

            // Handle tab switch
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let target = $(e.target).attr("href"); // activated tab
                let form = $(target).find('.filter-form');
                if (form.length > 0) {
                    form.trigger('submit'); // Submit the form to fetch data for the active tab
                }

                // Remove 'show active' from all tab panes and add to the current one
                $('.tab-pane').removeClass('show active');
                $(target).addClass('show active');

                // Update the active tab link
                $('a[data-toggle="tab"]').removeClass('active');
                $(e.target).addClass('active');
            });

            // Ensure the correct tab pane is shown on page load
            let activeTab = $('.nav-link.active').attr("href");
            $(activeTab).addClass('show active');
        });
    </script>
@endsection

// @include('dashboard.reports.mastery_report') Blade Template
@if (isset($response))
    <div class="filter-form-container">
        <form class="filter-form" method="GET" action="{{ route('reports.masteryReport') }}">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="program_id">Program</label>
                    <select class="form-select js-select2" name="program_id" id="program_id">
                        <option value="">Choose</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="unit_id">Unit</label>
                    <select class="form-select js-select2" name="unit_id" id="unit_id">
                        <option value="">Choose</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="lesson_id">Lesson</label>
                    <select class="form-select js-select2" name="lesson_id" id="lesson_id">
                        <option value="">Choose</option>
                        @foreach ($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="game_id">Game</label>
                    <select class="form-select js-select2" name="game_id" id="game_id">
                        <option value="">Choose</option>
                        @foreach ($games as $game)
                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="report-container mt-4">
        <!-- Units Mastery -->
        <h4>Units Mastery</h4>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Failed</th>
                    <th>Introduced</th>
                    <th>Practiced</th>
                    <th>Mastered</th>
                    <th>Mastery Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($response['units'] as $unit)
                    <tr>
                        <td>{{ $unit['name'] }}</td>
                        <td>{{ $unit['failed'] }}</td>
                        <td>{{ $unit['introduced'] }}</td>
                        <td>{{ $unit['practiced'] }}</td>
                        <td>{{ $unit['mastered'] }}</td>
                        <td>{{ round($unit['mastery_percentage'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Lessons Mastery -->
        <h4>Lessons Mastery</h4>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Lesson</th>
                    <th>Failed</th>
                    <th>Introduced</th>
                    <th>Practiced</th>
                    <th>Mastered</th>
                    <th>Mastery Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($response['lessons'] as $lesson)
                    <tr>
                        <td>{{ $lesson['name'] }}</td>
                        <td>{{ $lesson['failed'] }}</td>
                        <td>{{ $lesson['introduced'] }}</td>
                        <td>{{ $lesson['practiced'] }}</td>
                        <td>{{ $lesson['mastered'] }}</td>
                        <td>{{ round($lesson['mastery_percentage'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Games Mastery -->
        <h4>Games Mastery</h4>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Failed</th>
                    <th>Introduced</th>
                    <th>Practiced</th>
                    <th>Mastered</th>
                    <th>Mastery Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($response['games'] as $game)
                    <tr>
                        <td>{{ $game['name'] }}</td>
                        <td>{{ $game['failed'] }}</td>
                        <td>{{ $game['introduced'] }}</td>
                        <td>{{ $game['practiced'] }}</td>
                        <td>{{ $game['mastered'] }}</td>
                        <td>{{ round($game['mastery_percentage'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Skills Mastery -->
        <h4>Skills Mastery</h4>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Skill</th>
                    <th>Failed</th>
                    <th>Introduced</th>
                    <th>Practiced</th>
                    <th>Mastered</th>
                    <th>Mastery Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($response['skills'] as $skill)
                    <tr>
                        <td>{{ $skill['name'] }}</td>
                        <td>{{ $skill['failed'] }}</td>
                        <td>{{ $skill['introduced'] }}</td>
                        <td>{{ $skill['practiced'] }}</td>
                        <td>{{ $skill['mastered'] }}</td>
                        <td>{{ round($skill['mastery_percentage'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-warning">No data available</div>
@endif
