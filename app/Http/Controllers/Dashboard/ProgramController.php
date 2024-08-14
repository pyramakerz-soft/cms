<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
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
