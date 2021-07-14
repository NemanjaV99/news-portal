<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Editor;

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
}
