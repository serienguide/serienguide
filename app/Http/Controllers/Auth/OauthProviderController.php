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
        $this->authorizeResource(OauthProvider::class, 'provider');
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

        return view($this->base_view_path . '.index')
            ->with('oauth_providers', auth()->user()->oauth_providers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auth\OauthProvider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, OauthProvider $provider)
    {
        if ($is_deletable = $provider->isDeletable()) {
            $provider->delete();
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

        return redirect($provider->index_path)
            ->with('status', $status);
    }
}
