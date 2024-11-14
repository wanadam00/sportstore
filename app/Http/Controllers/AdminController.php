<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', ''); // Get the search input or default to an empty string

        $users = User::query()
            ->filtered()
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('Admin/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search, // Pass the search term as a filter
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // dd($request);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        // Validation rules, making password optional
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/', // Password is optional
        ]);

        // Collect the fields to update
        $data = $request->only('name', 'email');

        // Only update the password if it is provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Update the user with the collected data
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
