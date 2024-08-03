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
                                            {{-- <li class="nav-item">
                                                <a class="nav-link" id="skill-report-tab" data-toggle="tab"
                                                    href="#skill-report" role="tab" aria-controls="skill-report"
                                                    aria-selected="false">Skill Report</a>
                                            </li> --}}
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
                                                        {{-- <div class="form-row mt-3">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </div>
                                                        </div> --}}
                                                    </form>
                                                </div>
                                                <div class="report-container mt-4"></div>
                                            </div>
                                            <!-- Mastery Report Tab -->
                                            <div class="tab-pane fade" id="mastery-report" role="tabpanel"
                                                aria-labelledby="mastery-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.masteryReport') }}">
                                                        <div class="form-row">
                                                            <div class="col-md-3">
                                                                <label for="program_id">Program</label>
                                                                <select class="form-select js-select2" name="program_id"
                                                                    id="program_id">
                                                                    <option value="" selected disabled>Choose a
                                                                        program</option>
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}">
                                                                            {{ $program->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Filter By</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="filter_type" id="filter_unit"
                                                                        value="unit" checked>
                                                                    <label class="form-check-label"
                                                                        for="filter_unit">Unit</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="filter_type" id="filter_lesson"
                                                                        value="lesson">
                                                                    <label class="form-check-label"
                                                                        for="filter_lesson">Lesson</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="filter_type" id="filter_game"
                                                                        value="game">
                                                                    <label class="form-check-label"
                                                                        for="filter_game">Game</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="filter_type" id="filter_skill"
                                                                        value="skill">
                                                                    <label class="form-check-label"
                                                                        for="filter_skill">Skill</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-3">
                                                            <div class="col-md-3">
                                                                <label for="from_date">From Date</label>
                                                                <input type="date" class="form-control"
                                                                    name="from_date" id="from_date">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="to_date">To Date</label>
                                                                <input type="date" class="form-control" name="to_date"
                                                                    id="to_date">
                                                            </div>
                                                            {{-- <div class="col-md-12 text-right mt-3">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </div> --}}
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="report-container mt-4"></div>
                                            </div>

                                            <!-- Number of Trials Report Tab -->
                                            <div class="tab-pane fade" id="num-of-trials-report" role="tabpanel"
                                                aria-labelledby="num-of-trials-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.numOfTrialsReport') }}">
                                                        <div class="form-row">
                                                            <div class="col-md-3">
                                                                <label for="program_id">Program</label>
                                                                <select class="form-select js-select2" name="program_id"
                                                                    id="program_id">
                                                                    <option value="" selected disabled>Choose a
                                                                        program</option>
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}">
                                                                            {{ $program->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="from_date">From Date</label>
                                                                <input type="date" class="form-control"
                                                                    name="from_date" id="from_date">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="to_date">To Date</label>
                                                                <input type="date" class="form-control" name="to_date"
                                                                    id="to_date">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-row mt-3">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </div>
                                                        </div> --}}
                                                    </form>
                                                </div>
                                                <div class="report-container mt-4"></div>
                                            </div>

                                            <!-- Skill Report Tab -->
                                            <div class="tab-pane fade" id="skill-report" role="tabpanel"
                                                aria-labelledby="skill-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.skillReport') }}">
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
                                                                <label for="skill_id">Skill</label>
                                                                <select class="form-select js-select2" name="skill_id"
                                                                    id="skill_id">
                                                                    @foreach ($skills as $skill)
                                                                        <option value="{{ $skill->id }}">
                                                                            {{ $skill->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="from_date">From Date</label>
                                                                <input type="date" class="form-control"
                                                                    name="from_date" id="from_date">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="to_date">To Date</label>
                                                                <input type="date" class="form-control" name="to_date"
                                                                    id="to_date">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-row mt-3">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </div>
                                                        </div> --}}
                                                    </form>
                                                </div>
                                                <div class="report-container mt-4"></div>
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

                // Check if data is for completion report
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
                } else if (data[0].unit_id !== undefined) {
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

                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.failed}</td>
                                <td>${item.introduced}</td>
                                <td>${item.practiced}</td>
                                <td>${item.mastered}</td>
                                <td>${item.mastery_percentage}%</td>
                            </tr>`;
                    });

                    html += `
                        </tbody>
                    </table>`;
                } else if (data[0].lesson_id !== undefined) {
                    html += `<h5>Lessons Mastery</h5>`;
                    html += `<table class="table table-striped mt-4">
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
                        <tbody>`;

                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.failed}</td>
                                <td>${item.introduced}</td>
                                <td>${item.practiced}</td>
                                <td>${item.mastered}</td>
                                <td>${item.mastery_percentage}%</td>
                            </tr>`;
                    });

                    html += `
                        </tbody>
                    </table>`;
                } else if (data[0].game_id !== undefined) {
                    html += `<h5>Games Mastery</h5>`;
                    html += `<table class="table table-striped mt-4">
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
                        <tbody>`;

                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.failed}</td>
                                <td>${item.introduced}</td>
                                <td>${item.practiced}</td>
                                <td>${item.mastered}</td>
                                <td>${item.mastery_percentage}%</td>
                            </tr>`;
                    });

                    html += `
                        </tbody>
                    </table>`;
                } else if (data[0].skill_id !== undefined) {
                    html += `<h5>Skills Mastery</h5>`;
                    html += `<table class="table table-striped mt-4">
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
                        <tbody>`;

                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.failed}</td>
                                <td>${item.introduced}</td>
                                <td>${item.practiced}</td>
                                <td>${item.mastered}</td>
                                <td>${item.mastery_percentage}%</td>
                            </tr>`;
                    });

                    html += `
                        </tbody>
                    </table>`;
                } else {
                    // Handle other report types (e.g., trials report)
                    html += `<h4>Number of Trials Report</h4>`;
                    html += `<table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th>Test</th>
                                <th>Completion Date</th>
                                <th>Number of Trials</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    data.forEach(trial => {
                        html += `
                            <tr>
                                <td>${trial.test_name}</td>
                                <td>${trial.completion_date}</td>
                                <td>${trial.num_trials}</td>
                                <td>${trial.score}</td>
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
            $('#reportTabs a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            }).on('shown.bs.tab', function(e) {
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

            // Handle filter type radio button change
            $('input[name="filter_type"]').change(function() {
                let selectedFilter = $(this).val();
                $('#filter_unit_container, #filter_lesson_container, #filter_game_container').addClass(
                    'd-none');
                $(`#filter_${selectedFilter}_container`).removeClass('d-none');
            });

            // Trigger change event to show the correct filter on page load
            $('input[name="filter_type"]:checked').trigger('change');
        });
    </script>
@endsection
