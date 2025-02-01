<?php
namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    // public function show($username)
    // {
    //     $user = Auth::user();

    //     if ($user && $user->username === $username) {
    //         $profile = $user->profile;
    //     } else {
    //         $profile = Profile::whereHas('user', function ($query) use ($username) {
    //             $query->where('username', $username);
    //         })->firstOrFail();
    //     }

    //     return view('profiles.show', compact('profile'));
    // }

    /**
     * Show the form for editing the user's profile.
     *
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    // public function edit($username)
    // {
    //     $user = Auth::user();

    //     if ($user->username === $username) {
    //         $profile = $user->profile;
    //         return view('profiles.edit', compact('profile'));
    //     } else {
    //         abort(403); // Unauthorized access
    //     }
    // }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // ユーザー情報を更新
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // プロフィール情報を更新または作成
        if ($request->user()->profile) {
            $request->user()->profile->update($request->only('introduction'));
        } else {
            $request->user()->profile()->create($request->only('introduction'));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $username)
    // {
    //     $user = Auth::user();

    //     if ($user->username === $username) {
    //         $profile = $user->profile;

    //         $request->validate([
    //             'self_introduction' => 'nullable|string|max:255',
    //         ]);

    //         $profile->update([
    //             'self_introduction' => $request->self_introduction,
    //         ]);

    //         return redirect()->route('profile.show', ['username' => $user->username]);
    //     } else {
    //         abort(403); // Unauthorized access
    //     }
    // }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
