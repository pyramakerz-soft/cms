<?php

namespace App\Http\Controllers;

use App\Models\GameType;
use App\Models\Group;
use App\Models\Program;
use App\Models\School;
use App\Models\Skills;
use App\Models\Stage;
use Illuminate\Http\Request;
use App\Models\StudentTest;
use App\Models\StudentDegree;
use App\Models\Game;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\Test;
use App\Models\StudentProgress;
use App\Models\TestTypes;
use App\Models\User;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['details.stage', 'userCourses.program', 'groups'])
            ->where('role', '2');

        if ($request->filled('school')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('school_id', $request->input('school'));
            });
        }

        if ($request->filled('program')) {
            $query->whereHas('userCourses', function ($q) use ($request) {
                $q->where('program_id', $request->input('program'));
            });
        }

        if ($request->filled('grade')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('stage_id', $request->input('grade'));
            });
        }

        if ($request->filled('group')) {
            $query->whereHas('groups', function ($q) use ($request) {
                $q->where('group_id', $request->input('group'));
            });
        }

        $students = $query->simplePaginate(10);

        $schools = School::all();
        $programs = Program::with('course' , 'stage')->get();
        $grades = Stage::all();
        $classes = Group::all();
        $units = Unit::all();
        $lessons = Lesson::all();
        $games = Game::all();
        $skills = Skills::all();
        $testTypes = TestTypes::all();

        // Initialize $response and $data with default values
        $response = [
            'skills' => [],
            'units' => [],
            'lessons' => [],
            'games' => [],
            'tprogress' => [],
            'trials' => [],
            'skillsData' => [],
        ];

        $data = [
            'student_latest' => '',
            'counts' => [
                'completed' => 0,
                'overdue' => 0,
                'pending' => 0,
            ],
            'assignments_percentages' => [
                'completed' => 0,
                'overdue' => 0,
                'pending' => 0,
            ],
            'tests' => [],
            'test_types' => [],
            'skillsData' => [],
            'tprogress' => [],

        ];

        return view('dashboard.reports.index', compact(
            'students',
            'schools',
            'programs',
            'games',
            'response',
            'lessons',
            'units',
            'grades',
            'classes',
            'testTypes',
            'data',
            'skills'
        ));
    }

    public function completionReport(Request $request)
    {
        $studentId = $request->input('student_id');
        $programId = $request->input('program_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $status = $request->input('status');

        $query = StudentTest::with('tests')->where('student_id', $studentId);

        if ($fromDate && $toDate) {
            $query->whereBetween('start_date', [Carbon::parse($fromDate), Carbon::parse($toDate)]);
        }

        if ($status) {
            switch ($status) {
                case 'Completed':
                    $query->where('status', 1);
                    break;
                case 'Overdue':
                    $query->where('due_date', '<', now())->where('status', '!=', 1);
                    break;
                case 'Pending':
                    $query->where('status', 0)->where('due_date', '>=', now());
                    break;
            }
        }

        if ($programId) {
            $query->where('program_id', $programId);
        }

        $tests = $query->get();
        $totalTests = $tests->count();
        $completedTests = $tests->where('status', 1)->count();
        $overdueTests = $tests->where('due_date', '<', now())->where('status', '!=', 1)->count();
        $pendingTests = $totalTests - $completedTests - $overdueTests;

        $data = [
            'student_latest' => 'Some latest progress data', // Replace with actual latest progress data
            'counts' => [
                'completed' => $completedTests,
                'overdue' => $overdueTests,
                'pending' => $pendingTests,
            ],
            'assignments_percentages' => [
                'completed' => $totalTests > 0 ? round(($completedTests / $totalTests) * 100, 2) : 0,
                'overdue' => $totalTests > 0 ? round(($overdueTests / $totalTests) * 100, 2) : 0,
                'pending' => $totalTests > 0 ? round(($pendingTests / $totalTests) * 100, 2) : 0,
            ],
            'tests' => $tests,
        ];

        return response()->json($data);
    }

    public function masteryReport(Request $request)
    {
        $studentId = $request->input('student_id');
        $programId = $request->input('program_id');
        $unitId = $request->input('unit_id');
        $lessonId = $request->input('lesson_id');
        $gameId = $request->input('game_id');

        $query = StudentProgress::where('student_id', $studentId)
            ->where('program_id', $programId)
            ->where('is_done', 1);

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }
        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }
        if ($gameId) {
            $query->whereHas('test', function ($q) use ($gameId) {
                $q->where('game_id', $gameId);
            });
        }

        $studentProgress = $query->get();

        $unitsMastery = [];
        $lessonsMastery = [];
        $gamesMastery = [];
        $skillsMastery = [];

        foreach ($studentProgress as $progress) {
            $test = Test::with(['game.gameTypes.skills.skill'])->where('lesson_id', $progress->lesson_id)->find($progress->test_id);

            if (!$test || !$test->game || !$test->game->gameTypes) {
                continue;
            }

            $gameType = $test->game->gameTypes;

            if (!isset($unitsMastery[$progress->unit_id])) {
                $unitsMastery[$progress->unit_id] = [
                    'unit_id' => $progress->unit_id,
                    'name' => Unit::find($progress->unit_id)->name,
                    'failed' => 0,
                    'introduced' => 0,
                    'practiced' => 0,
                    'mastered' => 0,
                    'total_attempts' => 0,
                    'total_score' => 0,
                    'mastery_percentage' => 0,
                    'lessons' => [],
                ];
            }

            if (!isset($lessonsMastery[$progress->lesson_id])) {
                $lessonsMastery[$progress->lesson_id] = [
                    'lesson_id' => $progress->lesson_id,
                    'name' => Lesson::find($progress->lesson_id)->name,
                    'failed' => 0,
                    'introduced' => 0,
                    'practiced' => 0,
                    'mastered' => 0,
                    'total_attempts' => 0,
                    'total_score' => 0,
                    'mastery_percentage' => 0,
                    'games' => [],
                ];
            }

            if (!isset($gamesMastery[$test->game_id])) {
                $gamesMastery[$test->game_id] = [
                    'game_id' => $test->game_id,
                    'name' => Game::find($test->game_id)->name,
                    'failed' => 0,
                    'introduced' => 0,
                    'practiced' => 0,
                    'mastered' => 0,
                    'total_attempts' => 0,
                    'total_score' => 0,
                    'mastery_percentage' => 0,
                ];
            }

            if (!isset($skillsMastery[$gameType->id])) {
                $skillsMastery[$gameType->id] = [
                    'skill_id' => $gameType->id,
                    'name' => GameType::find($gameType->id)->name,
                    'failed' => 0,
                    'introduced' => 0,
                    'practiced' => 0,
                    'mastered' => 0,
                    'total_attempts' => 0,
                    'total_score' => 0,
                    'mastery_percentage' => 0,
                ];
            }

            foreach ($gameType->skills->unique() as $gameSkill) {
                $skill = $gameSkill->skill;

                if (!isset($skillsMastery[$skill->id])) {
                    $skillsMastery[$skill->id] = [
                        'skill_id' => $skill->id,
                        'name' => $skill->skill,
                        'failed' => 0,
                        'introduced' => 0,
                        'practiced' => 0,
                        'mastered' => 0,
                        'total_attempts' => 0,
                        'total_score' => 0,
                        'mastery_percentage' => 0,
                    ];
                }

                $skillsMastery[$skill->id]['total_attempts']++;
                if ($progress->is_done) {
                    if ($progress->score >= 80) {
                        $skillsMastery[$skill->id]['mastered']++;
                    } elseif ($progress->score >= 60) {
                        $skillsMastery[$skill->id]['practiced']++;
                    } elseif ($progress->score >= 30) {
                        $skillsMastery[$skill->id]['introduced']++;
                    } else {
                        $skillsMastery[$skill->id]['failed']++;
                    }
                } else {
                    $skillsMastery[$skill->id]['failed']++;
                }
                $skillsMastery[$skill->id]['total_score'] += $progress->score;
            }

            $unitsMastery[$progress->unit_id]['total_attempts']++;
            $lessonsMastery[$progress->lesson_id]['total_attempts']++;
            $gamesMastery[$test->game_id]['total_attempts']++;

            if ($progress->is_done) {
                if ($progress->score >= 80) {
                    $unitsMastery[$progress->unit_id]['mastered']++;
                    $lessonsMastery[$progress->lesson_id]['mastered']++;
                    $gamesMastery[$test->game_id]['mastered']++;
                } elseif ($progress->score >= 60) {
                    $unitsMastery[$progress->unit_id]['practiced']++;
                    $lessonsMastery[$progress->lesson_id]['practiced']++;
                    $gamesMastery[$test->game_id]['practiced']++;
                } elseif ($progress->score >= 30) {
                    $unitsMastery[$progress->unit_id]['introduced']++;
                    $lessonsMastery[$progress->lesson_id]['introduced']++;
                    $gamesMastery[$test->game_id]['introduced']++;
                } else {
                    $unitsMastery[$progress->unit_id]['failed']++;
                    $lessonsMastery[$progress->lesson_id]['failed']++;
                    $gamesMastery[$test->game_id]['failed']++;
                }
            } else {
                $unitsMastery[$progress->unit_id]['failed']++;
                $lessonsMastery[$progress->lesson_id]['failed']++;
                $gamesMastery[$test->game_id]['failed']++;
            }

            $unitsMastery[$progress->unit_id]['total_score'] += $progress->score;
            $lessonsMastery[$progress->lesson_id]['total_score'] += $progress->score;
            $gamesMastery[$test->game_id]['total_score'] += $progress->score;
        }

        foreach ($unitsMastery as &$unit) {
            $unit['mastery_percentage'] = $unit['total_attempts'] > 0 ? ($unit['total_score'] / $unit['total_attempts']) : 0;
        }

        foreach ($lessonsMastery as &$lesson) {
            $lesson['mastery_percentage'] = $lesson['total_attempts'] > 0 ? ($lesson['total_score'] / $lesson['total_attempts']) : 0;
        }

        foreach ($gamesMastery as &$game) {
            $game['mastery_percentage'] = $game['total_attempts'] > 0 ? ($game['total_score'] / $game['total_attempts']) : 0;
        }

        foreach ($skillsMastery as &$skill) {
            $skill['mastery_percentage'] = $skill['total_attempts'] > 0 ? ($skill['total_score'] / $skill['total_attempts']) : 0;
        }

        $response = [
            'units' => array_values($unitsMastery),
            'lessons' => array_values($lessonsMastery),
            'games' => array_values($gamesMastery),
            'skills' => array_values($skillsMastery),
        ];

        return response()->json($response);
    }



    public function numOfTrialsReport(Request $request)
    {
        $request->validate([
            'program_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);

        if (!StudentDegree::where('student_id', $request->student_id)->orderBy('id', 'desc')->first()) {
            $data = [
                'student_latest' => 'N/A',
                'tprogress' => [],
            ];
            return view('dashboard.reports.num_of_trials_report', compact('data'));
        }

        $latest_game = StudentDegree::where('student_id', $request->student_id)->orderBy('id', 'desc')->first()->game_id;
        $latest = Game::find($latest_game)->lesson_id;
        $latest_lesson = Lesson::find($latest)->name;
        $latest_unit = Unit::find(Lesson::find($latest)->unit_id)->name;
        $data['student_latest'] = $latest_unit . " " . $latest_lesson;

        $studentId = $request->student_id;
        $progressQuery = StudentProgress::where('student_id', $studentId)
            ->where('program_id', $request->program_id)->where('is_done', 1);

        if ($progressQuery->get()->isEmpty()) {
            $data['tprogress'] = [];
            return view('dashboard.reports.num_of_trials_report', compact('data'));
        }

        if ($request->filled(['from_date', 'to_date']) && $request->from_date != NULL && $request->to_date != NULL) {
            $from_date = Carbon::parse($request->from_date)->startOfDay();
            $to_date = Carbon::parse($request->to_date)->endOfDay();
            $progressQuery->whereBetween('created_at', [$from_date, $to_date]);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $progressQuery->whereMonth('student_progress.created_at', Carbon::parse($month)->month);
        }

        if ($request->filled('type')) {
            $type = $request->type;
            $progressQuery->join('tests', 'student_progress.test_id', '=', 'tests.id')
                ->where('tests.type', $type);
        }

        if ($request->filled('stars')) {
            $stars = (array)$request->stars;
            if ($request->stars == 2) {
                $progressQuery->whereIn('mistake_count', range(2, 1000));
            } else {
                $progressQuery->whereIn('mistake_count', $stars);
            }
        }

        $progress = $progressQuery->orderBy('created_at', 'ASC')
            ->select('student_progress.*')
            ->get();

        $monthlyScores = [];
        $starCounts = [];

        foreach ($progress as $course) {
            $createdDate = Carbon::parse($course->created_at);
            $monthYear = $createdDate->format('Y-m');

            $numTrials = $course->mistake_count + 1;

            $testScore = [
                'name' => $course->test_name,
                'test_id' => $course->test_id,
                'score' => $course->score,
                'star' => $course->stars,
                'num_trials' => $numTrials
            ];

            if (!isset($monthlyScores[$monthYear])) {
                $monthlyScores[$monthYear] = [
                    'month' => $createdDate->format('M'),
                    'total_score' => 0,
                    'star' => $course->stars,
                    'tests' => [],
                ];
            }

            $monthlyScores[$monthYear]['tests'][] = $testScore;
            $monthlyScores[$monthYear]['total_score'] += $course->score;

            $star = $course->stars;
            if (isset($starCounts[$star])) {
                $starCounts[$star]++;
            } else {
                $starCounts[$star] = 1;
            }
        }

        $totalDisplayedStars = array_sum($starCounts);
        $oneStarDisplayedCount = isset($starCounts[1]) ? $starCounts[1] : 0;
        $twoStarDisplayedCount = isset($starCounts[2]) ? $starCounts[2] : 0;
        $threeStarDisplayedCount = isset($starCounts[3]) ? $starCounts[3] : 0;

        if ($request->filled('stars')) {
            $stars = (array)$request->stars;
            $data['tprogress'] = array_filter($monthlyScores, function ($monthlyScore) use ($stars) {
                foreach ($monthlyScore['tests'] as $test) {
                    if (in_array($test['star'], $stars)) {
                        return true;
                    }
                }
                return false;
            });
        } else {
            $data['tprogress'] = array_values($monthlyScores);
        }

        $oneStarDisplayedPercentage = $totalDisplayedStars > 0 ? round(($oneStarDisplayedCount / $totalDisplayedStars) * 100, 2) : 0;
        $twoStarDisplayedPercentage = $totalDisplayedStars > 0 ? round(($twoStarDisplayedCount / $totalDisplayedStars) * 100, 2) : 0;
        $threeStarDisplayedPercentage = $totalDisplayedStars > 0 ? round(($threeStarDisplayedCount / $totalDisplayedStars) * 100, 2) : 0;

        $data['progress'] = $progress;

        if ($request->filled('stars')) {
            $data['counts'] = StudentProgress::where('stars', $request->stars)->count();
        } else {
            $data['counts'] = StudentProgress::where('student_id', $studentId)
                ->where('program_id', $request->program_id)
                ->count();
        }

        $division = StudentProgress::where('student_id', $studentId)->where('is_done', 1)->count();
        if ($division == 0) {
            $division = 1;
        }

        if (!$request->filled('from_date') && !$request->filled('to_date')) {
            $data['reports_percentages'] = [
                'first_trial' => round((StudentProgress::where('mistake_count', 0)->where('is_done', 1)->where('student_id', $studentId)
                    ->where('program_id', $request->program_id)->count() / $division) * 100, 2) ?? 0,
                'second_trial' => round((StudentProgress::where('mistake_count', 1)->where('is_done', 1)->where('student_id', $studentId)
                    ->where('program_id', $request->program_id)->count() / $division) * 100, 2) ?? 0,
                'third_trial' => round((StudentProgress::whereIn('mistake_count', [2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->where('is_done', 1)->where('student_id', $studentId)
                    ->where('program_id', $request->program_id)->count() / $division) * 100, 2) ?? 0,
            ];
        } else {
            $threestars = StudentProgress::where('mistake_count', 0)->where('student_id', $studentId)->whereBetween('student_progress.created_at', [$from_date, $to_date])
                ->where('program_id', $request->program_id)->count();
            $twostars = StudentProgress::where('mistake_count', 1)->where('student_id', $studentId)->whereBetween('student_progress.created_at', [$from_date, $to_date])
                ->where('program_id', $request->program_id)->count();
            $onestar = StudentProgress::whereIn('mistake_count', [2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->where('student_id', $studentId)->whereBetween('student_progress.created_at', [$from_date, $to_date])
                ->where('program_id', $request->program_id)->count();

            $division = StudentProgress::where('student_id', $studentId)
                ->whereBetween('student_progress.created_at', [$from_date, $to_date])->count();

            if ($division == 0) {
                $division = 1;
            }

            $data['reports_percentages'] = [
                'first_trial' => round(($threestars / $division) * 100, 2),
                'second_trial' => round(($twostars / $division) * 100, 2),
                'third_trial' => round(($onestar / $division) * 100, 2),
            ];
        }

        $test_types = TestTypes::all();
        $data['test_types'] = $test_types;

        return view('dashboard.reports.num_of_trials_report', compact('data'));
    }

    public function skillReport(Request $request)
    {
        $query = StudentProgress::with(['tests', 'tests.game', 'tests.game.gameTypes.skills'])
            ->where('student_id', $request->student_id)
            ->where('program_id', $request->program_id)
            ->where('is_done', 1);

        if ($request->has('skill_id')) {
            $query->whereHas('tests.game.gameTypes.skills', function ($q) use ($request) {
                $q->where('skill_id', $request->skill_id);
            });
        }

        if ($request->filled(['from_date', 'to_date']) && $request->from_date != null && $request->to_date != null) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $studentProgress = $query->get();
        $skillsData = [];

        foreach ($studentProgress as $progress) {
            $test = $progress->test;

            if ($test && $test->game) {
                $game = $test->game;

                if ($game->gameTypes) {
                    foreach ($game->gameTypes->skills->unique('skill') as $gameSkill) {
                        if (!$gameSkill->skill) continue;

                        $skill = $gameSkill->skill;
                        $skillName = $skill->skill;
                        $date = $progress->created_at->format('Y-m-d');

                        $currentLevel = 'Introduced';
                        if ($progress->score >= 80) {
                            $currentLevel = 'Mastered';
                        } elseif ($progress->score >= 60) {
                            $currentLevel = 'Practiced';
                        }

                        if (!isset($skillsData[$skillName])) {
                            $skillsData[$skillName] = [
                                'skill_name' => $skillName,
                                'total_score' => 0,
                                'count' => 0,
                                'average_score' => 0,
                                'current_level' => $currentLevel,
                                'date' => $date,
                            ];
                        }

                        $skillsData[$skillName]['count']++;
                        $skillsData[$skillName]['total_score'] += $progress->score;
                        $skillsData[$skillName]['average_score'] = $skillsData[$skillName]['total_score'] / $skillsData[$skillName]['count'];
                        if ($skillsData[$skillName]['average_score'] >= 80) {
                            $skillsData[$skillName]['current_level'] = 'Mastered';
                        } elseif ($skillsData[$skillName]['average_score'] >= 60) {
                            $skillsData[$skillName]['current_level'] = 'Practiced';
                        } else {
                            $skillsData[$skillName]['current_level'] = 'Introduced';
                        }
                    }
                }
            }
        }

        $data = [
            'student_latest' => 'Some latest progress data', // Example data
            'skillsData' => $skillsData,
        ];

        return response()->json(['data' => $data]);
    }
}
