<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\People\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    protected $base_view_path = Person::VIEW_PATH;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Person::orderBy('name', 'ASC')
                ->paginate();
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
        $person = Person::create([
            //
        ]);

        if ($request->wantsJson()) {
            return $person;
        }

        return redirect($person->edit_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz erstellt.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\People\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Person $person)
    {
        if ($request->wantsJson()) {
            return $person;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\People\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\People\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        $attributes = $request->validate([
            //
        ]);

        $person->update($attributes);

        if ($request->wantsJson()) {
            return $person;
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
     * @param  \App\Models\People\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Person $person)
    {
        if ($is_deletable = $person->isDeletable()) {
            $person->delete();
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

        return redirect($person->index_path)
            ->with('status', $status);
    }
}
