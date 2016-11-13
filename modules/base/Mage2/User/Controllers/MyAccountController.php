<?php

namespace Mage2\User\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mage2\Framework\System\Controllers\Controller;
use Mage2\User\Requests\UserProfileRequest;

class MyAccountController extends Controller {

    public function home() {
        $user = Auth::user();

        return view('user.my-account.home')
                        ->with('user', $user);
    }

    public function edit() {
        $user = Auth::user();

        return view('user.my-account.edit')
                        ->with('user', $user);
    }

    public function store(UserProfileRequest $request) {
        $user = Auth::user();
        $user->update($request->all());

        return redirect()->route('my-account.home');
    }

    public function uploadImage() {
        return view('user.my-account.upload-image');
    }

    public function uploadImagePost(Request $request) {
        
        $user = Auth::user();
        $image = $request->file('profile_image');
        $destinationPath = 'uploads/users/';
        $relativePath = implode('/', str_split(strtolower(str_random(3)))) . '/';
        $image->move($destinationPath . $relativePath, $image->getClientOriginalName());

        $user->update(['image_path' => $relativePath . $image->getClientOriginalName()]);
        
        
        return redirect()->route('my-account.home')->with('notificationText', 'User Profile Image Uploaded successfully!!');
    }

}
