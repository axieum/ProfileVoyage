<?php

namespace App\Http\Controllers;

use App\Social;
use App\SocialPlatform;
use Auth;
use Illuminate\Http\Request;
use LaraFlash;
use Socialite;

class SocialController extends Controller
{
    /**
     * Resource constructor
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socials = Auth::user()->socials;
        $platforms = SocialPlatform::all();
        return view('link.index')->withSocials($socials)->withPlatforms($platforms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        // Check if the platform is supported - database query.
        if (!SocialPlatform::where('name', $request->platform)->exists())
        {
            LaraFlash::danger('Unsupported platform! (' . $request->platform . ')');
            return redirect()->back();
        }

        // Send the OAuth request for the desired platform.
        return Socialite::with($request->platform)->redirect();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($platform)
    {
        // Check if the platform is supported - database query.
        $platformObject = SocialPlatform::where('name', $platform)->first();
        if (is_null($platformObject))
            abort(404, 'Unsupported platform! (' . $platform . ')');

        // Retrieve the entity.
        $entity = Socialite::driver($platformObject->name)->user()->accessTokenResponseBody;

        // Link entity to the user.
        $social = new Social;
        $social->user_id = Auth::user()->id;
        $social->platform_id = $platformObject->id;

        // Handle the stored value (UUID, Username, etc.)
        $socialValue = null;
        switch ($platformObject->name)
        {
            case 'twitter':
                $socialValue = '@' . $entity['screen_name'];
                break;
            default:
                $socialValue = isset($entity['user_id']) ? $entity['user_id'] : null;
                break;
        }
        $social->value = $socialValue;

        // Check if duplicate entry (would prefer a composite unique in the database).
        if(Social::where('user_id', Auth::user()->id)->where('platform_id', $platformObject->id)->where('value', $socialValue)->exists())
        {
            LaraFlash::warning('You\'ve already linked that ' . $platformObject->display_name . ' account!');
            return redirect(route('link.index'));
        }

        // Return
        if (!is_null($socialValue) && $social->save())
            LaraFlash::success('Successfully linked a new ' . $platformObject->display_name . ' account!');
        else
            LaraFlash::danger('Something prevented us from linking the ' . $platformObject->display_name . ' account!');

        return redirect(route('link.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlink($id)
    {
        $social = Social::where('id', $id)->first();
        $social_platform = $social->platform->display_name;

        if (is_null($social))
            abort(404, 'A social link with the id ' . $id . " couldn't be found.");

        if ($social->user_id != Auth::user()->id)
            abort(403, 'You do not have permission to delete that profile!');

        if ($social->delete())
        {
            LaraFlash::success('The social account (' . $social_platform . ') has been unlinked.');
        }
        else
        {
            LaraFlash::danger('Something prevented us from unlinking the account!');
        }

        return redirect(route('link.index'));
    }
}