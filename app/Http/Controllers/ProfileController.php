<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Profile\UpdateEditorInfoRequest;
use App\Http\Requests\Profile\UpdateMainInfoRequest;
use App\Http\Requests\Profile\UpdateAvatarRequest;
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

        // Temp: Update should always return 1 because of the updated_at field being always updated, a row will always be affected

        if ($status === 1) {

            return redirect()->back()->with('profile_updated', 'Successfully updated.');

        } else if ($status === 0) {

             // We failed to create the comment
             return redirect()->back()->withErrors(['update_error' => 'Nothing to update.'], 'main');
        }
    }

    public function updateEditor(UpdateEditorInfoRequest $request, Editor $editor)
    {
        $data = $request->validated();
        $data['id'] = Auth::user()->id;

        $status = $editor->updateEditor($data);

        // Temp: Update should always return 1 because of the updated_at field being always updated, a row will always be affected

        if ($status === 1) {

            return redirect()->back()->with('profile_updated', 'Successfully updated.');

        } else if ($status === 0) {

             // We failed to create the comment
             return redirect()->back()->withErrors(['update_error' => 'Nothing to update.'], 'editor');
        }
    }

    public function updateAvatar(UpdateAvatarRequest $request, User $user)
    {
        // All avatars are going to be stored in the same location, but each one should have a unique name/identifier,
        // and that unique name will be stored in users database

        $avatar = Image::make($request->file('avatar'));

        $avatar->resize(250, null, function($constraint) {
            $constraint->aspectRatio();
        });

        $avatarPath = 'avatars/' . bin2hex(random_bytes(20)) . '.' . $request->file('avatar')->extension();

        $avatar->save(storage_path('app/public/') . $avatarPath);

        $status = $user->updateAvatar([
            'id' => Auth::user()->id,
            'avatar' => $avatarPath
        ]);

        // Temp: Update should always return 1 because of the updated_at field being always updated, a row will always be affected

        if ($status === 1) {

            return redirect()->back()->with('profile_updated', 'Successfully updated.');

        } else if ($status === 0) {

            return redirect()->back()->withErrors(['update_error' => 'Nothing to update.'], 'editor');
        }
    }
}
