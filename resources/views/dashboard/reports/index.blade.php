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
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="program_id"><b>Program</b></label>
                                                                <select class="form-select js-select2" name="program_id"
                                                                    id="program_id">
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}">
                                                                            {{ $program->name . ' / ' . $program->course->name . ' / ' . $program->stage->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="from_date"><b>From Date</b></label>
                                                                <input type="date" class="form-control" name="from_date"
                                                                    id="from_date">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="to_date"><b>To Date</b></label>
                                                                <input type="date" class="form-control" name="to_date"
                                                                    id="to_date">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="status"><b>Status</b></label>
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
                                                <!-- Chart Placeholder -->
                                                {{-- <div class="chart-container mt-4">
                                                    <canvas id="progressChart"
                                                        style="max-width: 100%; height: 400px;"></canvas>
                                                </div> --}}


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
                                                                            {{ $program->name . ' / ' . $program->course->name . ' / ' . $program->stage->name }}
                                                                        </option>
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
                                                        <div class="row mt-3">
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
                                                <section class="mt-4">
                                                    <div class="containerchart">
                                                        <h2>Chart.js Responsive Bar Chart Demo</h2>
                                                        <div>
                                                            <canvas id="barChart"></canvas>
                                                        </div>
                                                        
                                                    </div>
                                                </section>
                                                <div class="report-container mt-4"></div>
                                            </div>

                                            <!-- Number of Trials Report Tab -->
                                            <div class="tab-pane fade" id="num-of-trials-report" role="tabpanel"
                                                aria-labelledby="num-of-trials-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.numOfTrialsReport') }}">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="program_id">Program</label>
                                                                <select class="form-select js-select2" name="program_id"
                                                                    id="program_id">
                                                                    <option value="" selected disabled>Choose a
                                                                        program</option>
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}">
                                                                            {{ $program->name . ' / ' . $program->course->name . ' / ' . $program->stage->name }}
                                                                        </option>
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
                                                <section class="mt-4">
                                                    <div class="containerchart">
                                                        <h2>Chart.js Responsive Bar Chart Demo</h2>
                                                        <div>
                                                            <canvas id="trialsChart"></canvas>
                                                        </div>
                                                        
                                                    </div>
                                                </section>
                                                <div class="report-container mt-4"></div>
                                            </div>

                                            <!-- Skill Report Tab -->
                                            <div class="tab-pane fade" id="skill-report" role="tabpanel"
                                                aria-labelledby="skill-report-tab">
                                                <div class="filter-form-container">
                                                    <form class="filter-form" method="GET"
                                                        action="{{ route('reports.skillReport') }}">
                                                        <div class="row">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
        integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
    // Initialize select2 for the filters
    $('.js-select2').select2();

    // Function to fetch and display reports
    function fetchReport(url, form, container) {
        $.ajax({
            url: url,
            method: 'GET',
            data: form.serialize(),
            beforeSend: function () {
                container.html(
                    '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'
                );
            },
            success: function (response) {
                container.html(renderReport(response));

                // Render the chart if data is available and it's the Mastery Report tab
                if (response.length > 0 && response[0].mastery_percentage !== undefined) {
                    renderChart(response);
                }
            },
            error: function (xhr, status, error) {
                container.html(
                    '<div class="alert alert-danger">Error fetching data. Please try again.</div>'
                );
            }
        });
    }

    // Function to render the chart
    function renderChart(data) {
        // const ctx = document.getElementById('barChart').getContext('2d');

        // Destroy previous chart instance if it exists
        if (window.myNewChartB) {
            window.myNewChartB.destroy();
        }

        // Extract labels and data points from the response
        const labels = data.map(item => item.name);
        const failedData = data.map(item => item.failed);
        const introducedData = data.map(item => item.introduced);
        const practicedData = data.map(item => item.practiced);
        const masteredData = data.map(item => item.mastered);

        // Create the new chart with the actual data
        window.myNewChartB = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Failed',
                        backgroundColor: color(chartColors.red).alpha(0.5).rgbString(),
                        borderColor: chartColors.red,
                        borderWidth: 1,
                        data: failedData
                    },
                    {
                        label: 'Introduced',
                        backgroundColor: color(chartColors.orange).alpha(0.5).rgbString(),
                        borderColor: chartColors.orange,
                        borderWidth: 1,
                        data: introducedData
                    },
                    {
                        label: 'Practiced',
                        backgroundColor: color(chartColors.yellow).alpha(0.5).rgbString(),
                        borderColor: chartColors.yellow,
                        borderWidth: 1,
                        data: practicedData
                    },
                    {
                        label: 'Mastered',
                        backgroundColor: color(chartColors.green).alpha(0.5).rgbString(),
                        borderColor: chartColors.green,
                        borderWidth: 1,
                        data: masteredData
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRation: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Mastery Report'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
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
        } else if (data[0] && data[0].unit_id !== undefined) {
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
        } else if (data[0] && data[0].lesson_id !== undefined) {
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
        } else if (data[0] && data[0].game_id !== undefined) {
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
        } else if (data[0] && data[0].skill_id !== undefined) {
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
    $('.filter-form').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let container = form.closest('.tab-pane').find('.report-container');
        fetchReport(url, form, container);
    });

    // Fetch reports when a student is selected
    $('#fetch-reports').click(function () {
        let studentId = $('#student_id').val();
        let forms = $('.filter-form');

        forms.each(function () {
            let form = $(this);
            let url = form.attr('action') + '?student_id=' + studentId;
            let container = form.closest('.tab-pane').find('.report-container');
            fetchReport(url, form, container);
        });
    });

    // Handle tab switch
    $('#reportTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    }).on('shown.bs.tab', function (e) {
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
    $('input[name="filter_type"]').change(function () {
        let selectedFilter = $(this).val();
        $('#filter_unit_container, #filter_lesson_container, #filter_game_container').addClass(
            'd-none');
        $(`#filter_${selectedFilter}_container`).removeClass('d-none');
    });

    // Trigger change event to show the correct filter on page load
    $('input[name="filter_type"]:checked').trigger('change');
});
</script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
    <script>
        var DEFAULT_DATASET_SIZE = 7,
            addedCount = 0,
            color = Chart.helpers.color;

        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
            "November", "December"
        ];

        var chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(231,233,237)'
        };

        function randomScalingFactor() {
            return Math.round(Math.random() * 100);
        }

        
        // var barData = {
        //     labels: ["unit 1", "unit 2", "unit 3", "unit 4", "unit 5", "unit 6", "unit 7"],
        //     datasets: [{
        //         label: 'Dataset 1',
        //         backgroundColor: color(chartColors.red).alpha(0.5).rgbString(),
        //         borderColor: chartColors.red,
        //         borderWidth: 1,
        //         data: [
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor()
        //         ]
        //     }, {
        //         label: 'Dataset 2',
        //         backgroundColor: color(chartColors.blue).alpha(0.5).rgbString(),
        //         borderColor: chartColors.blue,
        //         borderWidth: 1,
        //         data: [
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor(),
        //             randomScalingFactor()
        //         ]
        //     }]

        // };
        var index = 11;
        var ctx = document.getElementById("barChart").getContext("2d");
        var myNewChartB = new Chart(ctx, {
            type: 'bar',
            data: barData,
            options: {
                responsive: true,
                maintainAspectRation: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Bar Chart'
                }
            }
        });
    </script>
@endsection
