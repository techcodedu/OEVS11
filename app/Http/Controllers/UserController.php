<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['student', 'inactive_student'])->paginate();
        return view('admin.users', compact('users'));
    }

    public function toggleActivation(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!in_array($user->role, User::getValidRoles())) {
            return redirect()->back()->withErrors(['Invalid role value']);
        }

        if ($user->role === 'student') {
            $user->role = 'inactive_student';
        } elseif ($user->role === 'inactive_student') {
            $user->role = 'student';
        }

        $user->save();

        return redirect()->back()->with('success', 'User activation status updated successfully');
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $avatarName = $user->id . '_' . time() . '.' . $request->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('public/avatars', $avatarName);

        $user->update(['avatar' => $avatarName]);

        return back()->with('success', 'Avatar updated successfully.');
    }



}
