<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\TeacherProgram;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('school')) {
            $query = User::with(['details.stage', 'teacher_programs.program'])
                ->where('role', '1')->where('is_student', '0')->where("school_id", Auth::user()->school_id);
        } else {
            $query = User::with(['details.stage', 'teacher_programs.program'])
                ->where('role', '1')->where('is_student', '0');
        }


        if ($request->filled('school')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('school_id', $request->input('school'));
            });
        }

        if ($request->filled('program')) {
            $query->whereHas('teacher_programs', function ($q) use ($request) {
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

        $instructors = $query->simplePaginate(10);

        $schools = School::all();
        $programs = Program::all();
        $grades = Stage::all();
        $classes = Group::all();

        return view('dashboard.instructors.index', compact('instructors', 'schools', 'programs', 'grades', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        // $schools = School::all();
        // $programs = Program::all();
        $stages = Stage::all();
        $groups = Group::all();
        
        if ($user->hasRole('school')) {
            $schoolId = $user->school->id;
            $programs = Program::where('school_id', $schoolId)->get();
            $schools = School::where('id', $schoolId)->get();
        } else {
            $schools = School::all();
            $programs = Program::all();

        }
        // $roles = Role::all();

        return view('dashboard.instructors.create', compact('schools', 'programs', 'stages', 'groups'));
    }
    public function getGroups($program_id, $stage_id)
    {
        $groups = Group::where('program_id', $program_id)->where('stage_id', $stage_id)->get();
        return response()->json($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|confirmed|min:6',
            'school_id' => 'required|exists:schools,id',
            'program_id' => 'required|exists:programs,id',
            'stage_id' => 'required|exists:stages,id',
            'group_id' => 'nullable|exists:groups,id',
            'parent_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $teacher = $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'school_id' => $request->school_id,
            'role' => '1',
            'is_student' => 0
        ]);
foreach($request->program_id as $program_id){
        TeacherProgram::create([
            'teacher_id' => $user->id,
            'program_id' => $program_id,
            'grade_id' => $request->stage_id
        ]);
}

        UserDetails::create([
            'user_id' => $user->id,
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id
        ]);
        $group = Group::findOrFail($request->group_id);
        $group->update([
            'teacher_id' => $teacher->id,

        ]);
        $teacher->assignRole('teacher');


        if ($request->hasFile('parent_image')) {
            $imagePath = $request->file('parent_image')->store('images', 'public');
            $user->parent_image = $imagePath;
            $user->save();
        }

        return redirect()->route('instructors.index')->with('success', 'Teacher created successfully.');
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
        $instructor = User::findOrFail($id);
        $user = User::findOrFail($id);
        // $schools = School::all();
        // $programs = Program::all();
        $stages = Stage::all();
        $groups = Group::all();
        
        $stages = Stage::all();
        $groups = Group::all();
        
        if ($user->hasRole('school')) {
            $schoolId = $user->school->id;
            $programs = Program::where('school_id', $schoolId)->get();
            $schools = School::where('id', $schoolId)->get();
        } else {
            $schools = School::all();
            $programs = Program::all();
}

        return view('dashboard.instructors.edit', compact('instructor', 'schools', 'programs', 'stages', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'school_id' => 'required|exists:schools,id',
            'program_id' => 'required|exists:programs,id',
            'stage_id' => 'required|exists:stages,id',
            'group_id' => 'required|exists:groups,id',
            'parent_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'parent_image.image' => 'The image field must be an image jpg , jpeg , png .'
        ]);

        $instructor = User::findOrFail($id);
        $instructor->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'school_id' => $request->school_id
        ]);

TeacherProgram::where('teacher_id', $instructor->id)->delete();
foreach($request->program_id as $program_id){
        TeacherProgram::create([
            'teacher_id' => $instructor->id,
            'program_id' => $program_id,
            'grade_id' => $request->stage_id
        ]);
}


        // TeacherProgram::where('teacher_id', $instructor->id)->delete();

        UserDetails::where('user_id', $instructor->id)->update([
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id
        ]);


        if ($request->hasFile('parent_image')) {
            $imagePath = $request->file('parent_image')->store('images', 'public');
            $instructor->parent_image = $imagePath;
            $instructor->save();
        }

        return redirect()->route('instructors.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor = User::findOrFail($id);

        $instructor->delete();

        return redirect()->route('instructors.index')->with('success', 'Teacher deleted successfully.');
    }
}