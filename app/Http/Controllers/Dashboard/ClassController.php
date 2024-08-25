<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('school')) {
            $schoolId = $user->school->id;
            $classes = Group::where('school_id', $schoolId)->simplePaginate(10);


        } else {
            $classes = Group::simplePaginate(10);


        }
        return view('dashboard.class.index', compact("classes"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('school')) {
            $schoolId = $user->school->id;
            $schools = School::where('id', $schoolId)->get();
            $programs = Program::with('course')->where('school_id', $schoolId)->get();


        } else {
            $schools = School::all();
            $programs = Program::with('course')->get();
            $classes = Group::simplePaginate(10);


        }

        $stages = Stage::all();

        return view('dashboard.class.create', compact('schools', 'programs', 'stages'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'sec_name' => 'nullable|string|max:255',
        'school_id' => 'required|exists:schools,id',
        'program_id' => 'required|exists:programs,id',
    ]);

    $program = Program::findOrFail($request->program_id);
    $stage_id = $program->stage_id;

    $class = Group::create([
        'name' => $request->name,
        'sec_name' => $request->sec_name,
        'school_id' => $request->school_id,
        'stage_id' => $stage_id, // Set the stage_id from the program
        'program_id' => $request->program_id,
    ]);

        return redirect()->route('classes.create')->with('success', 'Class created successfully.');
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
        $class = Group::findOrFail($id);
        $schools = School::all();
        $programs = Program::all();
        $stages = Stage::all();

        return view("dashboard.class.edit", compact(["class", 'schools', 'stages', 'programs']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

           $request->validate([
            'name' => 'required|string|max:255',
            'sec_name' => 'nullable|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'stage_id' => 'nullable',
            'program_id' => 'required|exists:programs,id',
        ]);

        $program = Program::findOrFail($request->program_id);
        $stage_id = $program->stage_id;

        $class = Group::findOrFail($id);
        $class->update([
            'name' => $request->name,
            'sec_name' => $request->sec_name,
            'school_id' => $request->school_id,
            'program_id' => $request->program_id,
            'stage_id' => $stage_id,
        ]);



        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Group::findOrFail($id);

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
    public function getStages($program_id)
    {
        $program = Program::findOrFail($program_id);
        $stage = Stage::findOrFail($program->stage_id);
        // dd($stage);
        return $stage;
    }

}