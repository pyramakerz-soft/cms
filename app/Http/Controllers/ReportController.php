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
    $filterType = $request->input('filter_type');
    $filterValue = $request->input($filterType . '_id');

    $query = StudentProgress::where('student_id', $studentId)
        ->where('program_id', $programId)
        ->where('is_done', 1);

    if ($filterType && $filterValue) {
        if ($filterType == 'game') {
            $query->whereHas('test', function ($q) use ($filterValue) {
                $q->where('game_id', $filterValue);
            });
        } elseif ($filterType == 'skill') {
            $query->whereHas('test.game.gameTypes.skills', function ($q) use ($filterValue) {
                $q->where('skill_id', $filterValue);
            });
        } else {
            $query->where($filterType . '_id', $filterValue);
        }
    }

    if ($request->filled(['from_date', 'to_date']) && $request->from_date != NULL && $request->to_date != NULL) {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date = Carbon::parse($request->to_date)->endOfDay();
        $query->whereBetween('created_at', [$from_date, $to_date]);
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

    if ($request->has('filter_type')) {
        switch ($request->filter_type) {
            case 'skill':
                $response = array_values($skillsMastery);
                break;
            case 'unit':
                $response = array_values($unitsMastery);
                break;
            case 'lesson':
                $response = array_values($lessonsMastery);
                break;
            case 'game':
                $response = array_values($gamesMastery);
                break;
            default:
                $response = [
                    'skills' => array_values($skillsMastery),
                    'units' => array_values($unitsMastery),
                    'lessons' => array_values($lessonsMastery),
                    'games' => array_values($gamesMastery),
                ];
                break;
        }
    } else {
        $response = [
            'skills' => array_values($skillsMastery),
            'units' => array_values($unitsMastery),
            'lessons' => array_values($lessonsMastery),
            'games' => array_values($gamesMastery),
        ];
    }

    return response()->json($response);
}

    
    
    

    

    public function numOfTrialsReport(Request $request)
    {
        $studentId = $request->input('student_id');
        $programId = $request->input('program_id');
        $filterType = $request->input('filter_type');
        $filterValue = $request->input($filterType . '_id');
    
        $query = StudentProgress::where('student_id', $studentId)
            ->where('program_id', $programId)
            ->where('is_done', 1);
    
        if ($filterType && $filterValue) {
            if ($filterType == 'game') {
                $query->whereHas('test', function ($q) use ($filterValue) {
                    $q->where('game_id', $filterValue);
                });
            } elseif ($filterType == 'skill') {
                $query->whereHas('test.game.gameTypes.skills', function ($q) use ($filterValue) {
                    $q->where('skill_id', $filterValue);
                });
            } else {
                $query->where($filterType . '_id', $filterValue);
            }
        }
    
        if ($request->filled(['from_date', 'to_date']) && $request->from_date != NULL && $request->to_date != NULL) {
            $from_date = Carbon::parse($request->from_date)->startOfDay();
            $to_date = Carbon::parse($request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }
    
        $studentProgress = $query->get();
        $response = [];
    
        foreach ($studentProgress as $progress) {
            $test = Test::find($progress->test_id);
            if (!$test) {
                continue;
            }
    
            $response[] = [
                'test_name' => $test->name,
                'completion_date' => $progress->created_at->format('Y-m-d'),
                'num_trials' => $progress->mistake_count + 1,
                'score' => $progress->score,
            ];
        }
    
        return response()->json($response);
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
