<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::where('status', 1)->paginate(10);
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
            'email' => 'required|email|unique:schools',
            'phone' => 'required|string|max:15',
            'type' => 'required|string|in:national,international',
            'status' => 'required',
            'description' => 'nullable|string',
            'password' => 'required|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $file_extension = $request->image->getClientOriginalExtension();
            $file_name = time() . '.' . $file_extension;
            $path = 'images/school_images';
            $request->image->move($path, $file_name);
            $data['image'] = $path . '/' . $file_name;
        }

        School::create($data);

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
        $schools = School::findOrFail($id);
        return view("dashboard.school.edit", compact("schools"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email,' . $id,
            'phone' => 'required|string|max:15',
            'type' => 'required|string|in:national,international',
            'status' => 'required',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $school = School::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($school->image) {
                Storage::delete('public/' . $school->image);
            }

            $file_extension = $request->image->getClientOriginalExtension();
            $file_name = time() . '.' . $file_extension;
            $path = 'images/school_images';
            $request->image->move($path, $file_name);
            $data['image'] = $path . '/' . $file_name;
        }

        // Hash the password if it is provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            // Exclude the password from the data array if it is not provided
            unset($data['password']);
        }

        $school->update($data);

        return redirect()->route('schools.edit', $school->id)->with('success', 'School updated successfully!');
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
