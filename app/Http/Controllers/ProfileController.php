<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Profile\UpdateMainInfoRequest;
use App\Models\Editor;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Editor $editor)
    {
        $user = Auth::user();

        $return = ['user' => $user];

        if ($user->isEditor()) {

            // If the user is editor then we want additional information that he can update/preview
            $return['editor'] = $editor->getByUserId($user->id)->first();
        }

        return view('user.profile', $return);
    }

    public function updateMain(UpdateMainInfoRequest $request, User $user)
    {
        $data = $request->validated();
        $data['id'] = Auth::user()->id;

        $status = $user->updateMainInfo($data);

        if ($status) {

            return redirect()->back()->with('success_main', 'Successfully updated.');

        } else {

             // We failed to create the comment
             return redirect()->back()->withErrors(['update_error' => 'Failed to update info. Please try again.'], 'main');
        }
    }

    public function updateEditor()
    {

    }
}
