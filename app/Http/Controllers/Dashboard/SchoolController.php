<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = DB::table('users')->where('is_active', 1)
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->select('schools.id', 'schools.name', 'users.email', 'users.phone', 'schools.type', 'users.id as user_id')
            ->where('users.role', 3)
            ->simplePaginate(10);


        return view('dashboard.school.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.school.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|max:15',
            'type' => 'required|string|in:national,international',
            'password' => 'required|string|min:6',
        ]);
        // dd($request->type);
        $school = School::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 3,
            'is_active' => 1,
            'phone' => $request->phone,
            'school_id' => $school->id,
        ]);

        // if ($request->hasFile('image')) {
        //     $file_extension = $request->image->getClientOriginalExtension();
        //     $file_name = time() . '.' . $file_extension;
        //     $path = 'images/school_images';
        //     $request->image->move($path, $file_name);
        //     $data['image'] = $path . '/' . $file_name;
        // }
        $user->assignRole('school');


        return redirect()->route('schools.create')->with('success', 'School created successfully!');
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
        $roles = Role::pluck('name', 'name')->all();

        $school = School::findOrFail($id);
        // $school = School::simplePaginate(10);

        $schools = User::where('school_id', $id)->firstOrFail();
        $currentRole = $schools->role;

        return view("dashboard.school.edit", compact("schools", 'roles', 'school', 'currentRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $school = User::where('school_id', $id)->firstOrFail();
        // dd($school->id);

        $request->validate([
            'name' => 'required|string|max:255',


            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'type' => 'required|string|in:national,international',
            'password' => 'nullable|string|min:6',
        ]);

        // Find the school by its ID in the users table

        // Exclude fields that are not part of the update request
        $data = $request->except(['type', 'status', 'description', 'roles']);



        // Handle password update if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Update the school (user)
        $school->update($data);



        // Redirect back with success message
        return redirect()->route('schools.edit', $school->school_id)->with('success', 'School updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $school = School::findOrFail($id);

        // Delete image if exists
        if ($school->image) {
            Storage::disk('public')->delete($school->image);
        }

        $school->delete();

        return redirect()->route('schools.index')->with('success', 'School deleted successfully!');
    }
}
