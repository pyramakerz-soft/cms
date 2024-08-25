<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Game;
use App\Models\GameImage;
use App\Models\GameLetter;
use App\Models\GameSkills;
use App\Models\Lesson;
use App\Models\LessonPlan;
use App\Models\PPT;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\Unit;
use App\Models\Video;
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

//     public function addcurriculum($id, Request $request)
//     {
//         $program = Program::findOrFail($id);
//         $programs_c = Program::where('school_id', $program->school_id)->pluck('course_id');
//         $programs_s = Program::where('school_id', $program->school_id)->pluck('stage_id');
//         $programs = Program::where('name', 'Mindbuzz')->whereIn('course_id', $programs_c)->whereIn('stage_id', $programs_s)->get();
//         $selectedCourseId = $program->course_id;
//         $selectedStageId = $program->stage_id;

//         $getProgramIds = Program::whereHas('course', function ($query) use ($selectedCourseId) {
//             $query->where('course_id', $selectedCourseId);
//         })->whereHas('stage', function ($query) use ($selectedStageId) {
//             $query->where('id', $selectedStageId);
//         })->where('name', 'Mindbuzz')->pluck('id');
// if($request->program_id){
//     // dd($request->program_id,Program::where('school_id',$program->school_id)->where('course_id',Program::find($request->program_id)->course_id)->first(),$programs,$program);
//     $real_p = Program::where('school_id',$program->school_id)->where('course_id',Program::find($request->program_id)->course_id)->first()->id;
    
// }
//         $units = Unit::where('program_id', $request->program_id)->get();
//         if (isset($request->unit_id)) {
//             foreach ($request->unit_id as $unit_id) {
//                 $new_unit = Unit::find($unit_id)->replicate();
//                 $new_unit->program_id = $real_p;
//                 $new_unit->save();
//                 $pptRecords = PPT::where('unit_id', $unit_id)->get();
//                 foreach ($pptRecords as $ppt) {
//                     $newPpt = $ppt->replicate();
//                     $newPpt->unit_id = $new_unit->id;
//                     $newPpt->save();
//                 }

//                 $lessonPlans = LessonPlan::where('unit_id', $unit_id)->get();
//                 foreach ($lessonPlans as $plan) {
//                     $newPlan = $plan->replicate();
//                     $newPlan->unit_id = $new_unit->id;
//                     $newPlan->save();
//                 }

//                 $ebooks = Ebook::where('unit_id', $unit_id)->get();
//                 foreach ($ebooks as $ebook) {
//                     $newEbook = $ebook->replicate();
//                     $newEbook->unit_id = $new_unit->id;
//                     $newEbook->save();
//                 }

//                 $videos = Video::where('unit_id', $unit_id)->get();
//                 foreach ($videos as $video) {
//                     $newVideo = $video->replicate();
//                     $newVideo->unit_id = $new_unit->id;
//                     $newVideo->save();
//                 }

//                 foreach ($request->lesson_id as $lesson_id) {
//                     // dd($request->lesson_id);
//                     if (Lesson::find($lesson_id)->unit_id == Unit::find($unit_id)->id) {
//                         $new_lesson = Lesson::find($lesson_id)->replicate();
//                         $new_lesson->unit_id = $new_unit->id;
//                         $new_lesson->save();

//                         $games = Game::where('lesson_id', $lesson_id)->get();
//                         $oldToNewGameIds = [];
//                         foreach ($games as $game) {
//                             $newGame = $game->replicate();
//                             $newGame->lesson_id = $new_lesson->id;
//                             $newGame->next_game_id = NULL;
//                             $newGame->prev_game_id = NULL;
//                             $newGame->save();
//                             $oldToNewGameIds[$game->id] = $newGame->id;
//                         }
//                         foreach ($games as $game) {
//                             $dest_game_id = $oldToNewGameIds[$game->id];
//                             $oldToNewLetterIds = [];
//                             $oldToNewImageIds = [];

//                             $gameLetters = GameLetter::where('game_id', $game->id)->get();
//                             foreach ($gameLetters as $letter) {
//                                 $newLetter = $letter->replicate();
//                                 $newLetter->game_id = $dest_game_id;
//                                 $newLetter->save();

//                                 $oldToNewLetterIds[$letter->id] = $newLetter->id;
//                             }

//                             $gameImages = GameImage::where('game_id', $game->id)->get();
//                             foreach ($gameImages as $image) {
//                                 $newImage = $image->replicate();
//                                 $newImage->game_id = $dest_game_id;

//                                 if (isset($oldToNewLetterIds[$image->game_letter_id])) {
//                                     $newImage->game_letter_id = $oldToNewLetterIds[$image->game_letter_id];
//                                 }

//                                 $newImage->save();

//                                 $oldToNewImageIds[$image->id] = $newImage->id;
//                             }

//                             $gameChoices = Choice::where('game_id', $game->id)->get();
//                             foreach ($gameChoices as $choice) {
//                                 $newChoice = $choice->replicate();
//                                 $newChoice->game_id = $dest_game_id;

//                                 if (isset($oldToNewLetterIds[$choice->question_id])) {
//                                     $newChoice->question_id = $oldToNewLetterIds[$choice->question_id];
//                                 }

//                                 $newChoice->save();
//                             }


//                             $gameSkills = GameSkills::where('lesson_id', $lesson_id)
//                                 ->get();
//                             foreach ($gameSkills as $skill) {
//                                 $newSkill = $skill->replicate();
//                                 $newSkill->lesson_id = $new_lesson->id;
//                                 $newSkill->save();
//                             }
//                         }

//                     }
//                 }
//             }
//         }
//         return view('dashboard.program.curriculum', compact(['programs', 'program', 'getProgramIds', 'units']))->with('success', 'Curriculum created successfully!');
//     }
    
    
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
    
    if($request->program_id) {
        $real_p = Program::where('school_id',$program->school_id)->where('course_id',Program::find($request->program_id)->course_id)->first()->id;
    }
    
    $units = Unit::where('program_id', $request->program_id)->get();
    if (isset($request->unit_id)) {
        foreach ($request->unit_id as $unit_id) {
            $new_unit = Unit::find($unit_id)->replicate();
            $new_unit->program_id = $real_p;
            $new_unit->save();
            
            // Replicate related tables
            $this->replicateRelatedTables($unit_id, $new_unit->id);

            foreach ($request->lesson_id as $lesson_id) {
                if (Lesson::find($lesson_id)->unit_id == Unit::find($unit_id)->id) {
                    $new_lesson = Lesson::find($lesson_id)->replicate();
                    $new_lesson->unit_id = $new_unit->id;
                    $new_lesson->save();

                    // Replicate games and their components
                    $this->replicateGames($lesson_id, $new_lesson->id);

                    // Replicate skills with the new lesson ID
                    $this->replicateSkills($lesson_id, $new_lesson->id);
                }
            }
        }
    }
    
    return view('dashboard.program.curriculum', compact(['programs', 'program', 'getProgramIds', 'units']))->with('success', 'Curriculum created successfully!');
}

private function replicateRelatedTables($old_unit_id, $new_unit_id)
{
    // Replicate ppt
    $pptRecords = PPT::where('unit_id', $old_unit_id)->get();
    foreach ($pptRecords as $ppt) {
        $newPpt = $ppt->replicate();
        $newPpt->unit_id = $new_unit_id;
        $newPpt->save();
    }

    // Replicate lesson_plan
    $lessonPlans = LessonPlan::where('unit_id', $old_unit_id)->get();
    foreach ($lessonPlans as $plan) {
        $newPlan = $plan->replicate();
        $newPlan->unit_id = $new_unit_id;
        $newPlan->save();
    }

    // Replicate ebook
    $ebooks = Ebook::where('unit_id', $old_unit_id)->get();
    foreach ($ebooks as $ebook) {
        $newEbook = $ebook->replicate();
        $newEbook->unit_id = $new_unit_id;
        $newEbook->save();
    }

    // Replicate video
    $videos = Video::where('unit_id', $old_unit_id)->get();
    foreach ($videos as $video) {
        $newVideo = $video->replicate();
        $newVideo->unit_id = $new_unit_id;
        $newVideo->save();
    }
}

private function replicateGames($old_lesson_id, $new_lesson_id)
{
    $games = Game::where('lesson_id', $old_lesson_id)->get();
    $oldToNewGameIds = [];
    
    // First pass: replicate games
    foreach ($games as $game) {
        $newGame = $game->replicate();
        $newGame->lesson_id = $new_lesson_id;
        $newGame->prev_game_id = null; // Set to null initially
        $newGame->next_game_id = null; // Set to null initially
        $newGame->save();
        $oldToNewGameIds[$game->id] = $newGame->id;
    }

    // Second pass: update prev_game_id and next_game_id
    foreach ($games as $game) {
        $newGame = Game::find($oldToNewGameIds[$game->id]);
        if ($game->prev_game_id && isset($oldToNewGameIds[$game->prev_game_id])) {
            $newGame->prev_game_id = $oldToNewGameIds[$game->prev_game_id];
        }
        if ($game->next_game_id && isset($oldToNewGameIds[$game->next_game_id])) {
            $newGame->next_game_id = $oldToNewGameIds[$game->next_game_id];
        }
        $newGame->save();
    }

    foreach ($games as $game) {
        $dest_game_id = $oldToNewGameIds[$game->id];
        $this->replicateGameComponents($game->id, $dest_game_id);
    }
}

private function replicateGameComponents($old_game_id, $new_game_id)
{
    $oldToNewLetterIds = [];
    $oldToNewImageIds = [];

    $gameLetters = GameLetter::where('game_id', $old_game_id)->get();
    foreach ($gameLetters as $letter) {
        $newLetter = $letter->replicate();
        $newLetter->game_id = $new_game_id;
        $newLetter->save();

        $oldToNewLetterIds[$letter->id] = $newLetter->id;
    }

    $gameImages = GameImage::where('game_id', $old_game_id)->get();
    foreach ($gameImages as $image) {
        $newImage = $image->replicate();
        $newImage->game_id = $new_game_id;

        if (isset($oldToNewLetterIds[$image->game_letter_id])) {
            $newImage->game_letter_id = $oldToNewLetterIds[$image->game_letter_id];
        }

        $newImage->save();

        $oldToNewImageIds[$image->id] = $newImage->id;
    }

    $gameChoices = Choice::where('game_id', $old_game_id)->get();
    foreach ($gameChoices as $choice) {
        $newChoice = $choice->replicate();
        $newChoice->game_id = $new_game_id;

        if (isset($oldToNewLetterIds[$choice->question_id])) {
            $newChoice->question_id = $oldToNewLetterIds[$choice->question_id];
        }

        $newChoice->save();
    }
}

private function replicateSkills($old_lesson_id, $new_lesson_id)
{
    $gameSkills = GameSkills::where('lesson_id', $old_lesson_id)->get();
    foreach ($gameSkills as $skill) {
        $newSkill = $skill->replicate();
        $newSkill->lesson_id = $new_lesson_id;
        $newSkill->save();
    }
}






    private function replicateUnitData($unit_id, $new_unit_id)
    {

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
