<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('school')) {
            $query = User::with(['details.stage', 'userCourses.program', 'groups'])
                ->where('role', '2')
                ->where('is_student', 1)
                ->where('school_id', Auth::user()->school_id);
        } else {
            $query = User::with(['details.stage', 'userCourses.program', 'groups'])
                ->where('role', '2')
                ->where('is_student', 1)
            ;
        }

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
        $programs = Program::with('course')->when(Auth::user()->hasRole('school'), function ($query) {
            return $query->where('school_id', Auth::user()->school_id);
        })->get();
        $grades = Stage::all();
        $classes = Group::when(Auth::user()->hasRole('school'), function ($query) {
            return $query->where('school_id', Auth::user()->school_id);
        })->get();

        return view('dashboard.students.index', compact('students', 'schools', 'programs', 'grades', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $user = auth()->user();
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

        return view('dashboard.students.create', compact('schools', 'programs', 'stages', 'groups'));
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'school_id' => $request->school_id,
            'role' => '2',
            'is_student' => 1
        ]);
        foreach ($request->program_id as $program_id) {
            UserCourse::create([
                'user_id' => $user->id,
                'program_id' => $program_id
            ]);
        }

        UserDetails::create([
            'user_id' => $user->id,
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id
        ]);
        if ($request->has('group_id')) {
        foreach ($request->group_id as $group_id) {
            GroupStudent::create([
                'group_id' => $group_id,
                'student_id' => $user->id
            ]);
        }
    }

        
        // $user->assignRole($request->input('roles'));

        if ($request->hasFile('parent_image')) {
            $imagePath = $request->file('parent_image')->store('images', 'public');
            $user->parent_image = $imagePath;
            $user->save();
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully.');


    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
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
    $student = User::findOrFail($id);
    $schools = School::all();
    $userDetails = UserDetails::where('user_id', $id)->first();
    
    if ($userDetails && $userDetails->stage_id) {
        $programs = Program::where('stage_id', $userDetails->stage_id)->get();
    } else {
        $programs = Program::all();
    }

    $stages = Stage::all();
    $groups = Group::all();
    $selectedGroups = GroupStudent::where('student_id', $id)->pluck('group_id')->toArray(); // Retrieve selected groups

    return view('dashboard.students.edit', compact('student', 'schools', 'programs', 'stages', 'groups', 'selectedGroups'));
}

public function update(Request $request, string $id)
{
    // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'phone' => 'required|string|max:15',
        'school_id' => 'required|exists:schools,id',
        'program_id' => 'required|exists:programs,id',
        'stage_id' => 'required|exists:stages,id',
        'group_id' => 'nullable|array', // Accept multiple group IDs
        'group_id.*' => 'exists:groups,id', // Validate each group ID
        'parent_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Find the student
    $student = User::findOrFail($id);
    $student->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'school_id' => $request->school_id
    ]);

    // Update programs
    UserCourse::where('user_id', $student->id)->delete();
    foreach ($request->program_id as $program_id) {
        UserCourse::create([
            'program_id' => $program_id,
            'user_id' => $student->id
        ]);
    }

    // Update user details
    UserDetails::where('user_id', $student->id)->update([
        'school_id' => $request->school_id,
        'stage_id' => $request->stage_id
    ]);

    // Update the student's group associations
    GroupStudent::where('student_id', $student->id)->delete(); // Remove old associations
    if ($request->has('group_id')) {
        foreach ($request->group_id as $group_id) {
            GroupStudent::create([
                'group_id' => $group_id,
                'student_id' => $student->id
            ]);
        }
    }

    // Handle image upload
    if ($request->hasFile('parent_image')) {
        $imagePath = $request->file('parent_image')->store('images', 'public');
        $student->parent_image = $imagePath;
        $student->save();
    }

    return redirect()->route('students.index')->with('success', 'Student updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = User::findOrFail($id);

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');

    }

    ///////////////////////////////////
    //Getters
    public function getCourses($id, $schoolId)
    {
        $courses = Program::with('course')->where('stage_id', $id)->where('school_id', $schoolId)->get();
        return response()->json($courses);
    }
    ///////////////////////////////////
}