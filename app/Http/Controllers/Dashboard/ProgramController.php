<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Course;
use App\Models\Game;
use App\Models\GameImage;
use App\Models\GameLetter;
use App\Models\GameSkills;
use App\Models\Lesson;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if (Auth::user()->hasRole('school')) {
            $schoolId = $user->school->id;

            $programs = Program::where('school_id', $schoolId)->with('course', 'stage', 'school')->get()->groupBy('name');

        } else {
            $programs = Program::with('course', 'stage', 'school')->get()->groupBy('name');

        }
        // dd($programs);
        return view('dashboard.program.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        $courses = Course::all();
        $stages = Stage::all();
        return view('dashboard.program.create', compact(['schools', 'courses', 'stages']));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'course_id' => 'required|array',
            'stage_id' => 'required|exists:stages,id',
        ]);

        $school_id = $request->school_id;
        $stage_id = $request->stage_id;

        foreach ($request->course_id as $course_id) {
            $existingProgram = Program::where('school_id', $school_id)
                ->where('course_id', $course_id)
                ->where('stage_id', $stage_id)
                ->first();

            if ($existingProgram) {
                return redirect()->back()->withErrors(['course_id' => 'The course is already assigned to this school and stage.']);
            }

            Program::create([
                'name' => $request->name,
                'school_id' => $school_id,
                'course_id' => $course_id,
                'stage_id' => $stage_id,
            ]);
        }

        return redirect()->route('programs.create')->with('success', 'Cluster created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schools = School::all();
        $courses = Course::all();
        $stages = Stage::all();
        $program = Program::findOrFail($id);
        return view('dashboard.program.edit', compact(['program', 'schools', 'courses', 'stages']));
    }

    public function addcurriculum($id, Request $request)
    {
        $program = Program::findOrFail($id);
        $programs_c = Program::where('school_id', $program->school_id)->pluck('course_id');
        $programs_s = Program::where('school_id', $program->school_id)->pluck('stage_id');
        $programs = Program::where('name', 'Mindbuzz')->whereIn('course_id', $programs_c)->whereIn('stage_id', $programs_s)->get();
        $selectedCourseId = $program->course_id;
        $selectedStageId = $program->stage_id;

        $getProgramIds = Program::whereHas('course', function ($query) use ($selectedCourseId) {
            $query->where('course_id', $selectedCourseId);
        })->whereHas('stage', function ($query) use ($selectedStageId) {
            $query->where('id', $selectedStageId);
        })->where('name', 'Mindbuzz')->pluck('id');

        $units = Unit::where('program_id', $getProgramIds[0])->get();
        if (isset($request->unit_id)) {
            foreach ($request->unit_id as $unit_id) {
                $new_unit = Unit::find($unit_id)->replicate();
                $new_unit->program_id = $getProgramIds[0];
                $new_unit->save();

                foreach ($request->lesson_id as $lesson_id) {
                    if (Lesson::find($lesson_id)->unit_id == Unit::find($unit_id)->id) {
                        $new_lesson = Lesson::find($lesson_id)->replicate();
                        $new_lesson->unit_id = $new_unit->id;
                        $new_lesson->save();

                        $games = Game::where('lesson_id', $lesson_id)->get();
                        $oldToNewGameIds = [];
                        foreach ($games as $game) {
                            $newGame = $game->replicate();
                            $newGame->lesson_id = $new_lesson->id;
                            $newGame->save();
                            $oldToNewGameIds[$game->id] = $newGame->id;
                        }
                        foreach ($games as $game) {
                            $dest_game_id = $oldToNewGameIds[$game->id];
                            $oldToNewLetterIds = [];
                            $oldToNewImageIds = [];

                            $gameLetters = GameLetter::where('game_id', $game->id)->get();
                            foreach ($gameLetters as $letter) {
                                $newLetter = $letter->replicate();
                                $newLetter->game_id = $dest_game_id;
                                $newLetter->save();

                                $oldToNewLetterIds[$letter->id] = $newLetter->id;
                            }

                            $gameImages = GameImage::where('game_id', $game->id)->get();
                            foreach ($gameImages as $image) {
                                $newImage = $image->replicate();
                                $newImage->game_id = $dest_game_id;

                                if (isset($oldToNewLetterIds[$image->game_letter_id])) {
                                    $newImage->game_letter_id = $oldToNewLetterIds[$image->game_letter_id];
                                }

                                $newImage->save();

                                $oldToNewImageIds[$image->id] = $newImage->id;
                            }

                            $gameChoices = Choice::where('game_id', $game->id)->get();
                            foreach ($gameChoices as $choice) {
                                $newChoice = $choice->replicate();
                                $newChoice->game_id = $dest_game_id;

                                if (isset($oldToNewLetterIds[$choice->question_id])) {
                                    $newChoice->question_id = $oldToNewLetterIds[$choice->question_id];
                                }

                                $newChoice->save();
                            }


                            $gameSkills = GameSkills::where('lesson_id', $lesson_id)
                                ->get();
                            foreach ($gameSkills as $skill) {
                                $newSkill = $skill->replicate();
                                $newSkill->lesson_id = $new_lesson->id;
                                $newSkill->save();
                            }
                        }

                    }
                }
            }
        }
        return view('dashboard.program.curriculum', compact(['programs', 'program', 'getProgramIds', 'units']))->with('success', 'Curriculum created successfully!');
    }

    public function editCurriculum($id)
    {
        $program = Program::findOrFail($id);
        $units = Unit::where('program_id', $program->id)->get();
        $lessons = Lesson::whereIn('unit_id', $units->pluck('id'))->get();

        return view('dashboard.program.edit-curriculum', compact('program', 'units', 'lessons'));
    }


    public function getUnitsByProgram(Request $request)
    {
        $programIds = $request->program_id;
        $units = Unit::where('program_id', $programIds)->get();
        return response()->json(['units' => $units]);
    }
    public function getLessonsByUnits(Request $request)
    {
        $unitIds = $request->unit_ids;
        $lessons = Lesson::whereIn('unit_id', $unitIds)->get();
        return response()->json(['lessons' => $lessons]);
    }
    // public function getGamesByLessons(Request $request)
    // {
    //     $lessonIds = $request->lesson_ids;
    //     $games = Game::whereIn('lesson_id', $lessonIds)->get();
    //     return response()->json(['games' => $games]);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'stage_id' => 'required|exists:stages,id',
        ]);

        $program = Program::findOrFail($id);

        $program->update([
            'name' => $request->name,
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id,
        ]);

        $program->courses()->sync($request->course_id);

        return redirect()->route('programs.edit', $program->id)->with('success', 'Cluster updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Cluster deleted successfully!');
    }
}
