<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\OauthProvider;
use Illuminate\Http\Request;

class OauthProviderController extends Controller
{
    protected $base_view_path = OauthProvider::VIEW_PATH;

    public function __construct()
    {
        $this->authorizeResource(OauthProvider::class, 'oauthProvider');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            //
        }

        return view($this->base_view_path . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oauthProvider = OauthProvider::create([
            //
        ]);

        if ($request->wantsJson()) {
            return $oauthProvider;
        }

        return redirect($oauthProvider->edit_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz erstellt.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auth\OauthProvider  $oauthProvider
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OauthProvider $oauthProvider)
    {
        if ($request->wantsJson()) {
            return $oauthProvider;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $oauthProvider);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auth\OauthProvider  $oauthProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(OauthProvider $oauthProvider)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $oauthProvider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auth\OauthProvider  $oauthProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OauthProvider $oauthProvider)
    {
        $attributes = $request->validate([
            //
        ]);

        $oauthProvider->update($attributes);

        if ($request->wantsJson()) {
            return $oauthProvider;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auth\OauthProvider  $oauthProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, OauthProvider $oauthProvider)
    {
        if ($is_deletable = $oauthProvider->isDeletable()) {
            $oauthProvider->delete();
            $status = [
                'type' => 'success',
                'text' => 'Datensatz gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'Datensatz kann nicht gelöscht werden.',
            ];
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $is_deletable,
            ];
        }

        return redirect($oauthProvider->index_path)
            ->with('status', $status);
    }
}
