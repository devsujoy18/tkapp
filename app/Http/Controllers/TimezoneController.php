<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timezone;
use Illuminate\Validation\Rule;

class TimezoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timezones = Timezone::all();
        return view('admin.timezone.index', compact('timezones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.timezone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         =>   'required',
            'code'  =>   'required|unique:timezones'
        ]);
        Timezone::create([
            'name'  =>  $request->name,
            'code' =>  $request->code
        ]);
        return redirect('admin/timezones')->with('success', 'Successfully timezone added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timezone = Timezone::find($id);
        return view('admin.timezone.edit', compact('timezone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         =>   'required',
            'code'  =>   ['required', Rule::unique('timezones')->ignore($id)]
        ]);

        $timezone = Timezone::find($id);
        $timezone->name = $request['name'];
        $timezone->code = $request['code'];
        $timezone->save();
        return redirect('admin/timezones')->with('success', 'Successfully timezone updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
