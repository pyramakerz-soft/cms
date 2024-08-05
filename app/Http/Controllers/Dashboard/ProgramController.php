<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::with('course')->simplePaginate(10);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'course_id' => 'required|exists:courses,id',
            'stage_id' => 'required|exists:stages,id',
        ]);

        foreach ($request->course_id as $course_id) {

            Program::create([
                'name' => $request->name,
                'school_id' => $request->school_id,
                'course_id' => $course_id,
                'stage_id' => $request->stage_id,
            ]);
        }



        return redirect()->route('programs.create')->with('success', 'Program created successfully!');

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
            'course_id' => 'required|exists:courses,id',
            'stage_id' => 'required|exists:stages,id',
        ]);

        $program = Program::findOrFail($id);

        foreach ($request->course_id as $course_id) {

            $program->update([
                'name' => $request->name,
                'school_id' => $request->school_id,
                'course_id' => $course_id,
                'stage_id' => $request->stage_id,
            ]);
        }



        return redirect()->route('programs.edit', $program->id)->with('success', 'Program updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program deleted successfully!');

    }
}
