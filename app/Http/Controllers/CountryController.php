<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $countries = Country::all();
        return view('admin.country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.country.create');
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
            'code'  =>   'required|unique:countries',
            'phone_no_prefix' => 'required|unique:countries'
        ]);
        Country::create([
            'name'  =>  $request->name,
            'code' =>  $request->code,
            'phone_no_prefix' => $request->phone_no_prefix
        ]);
        return redirect('admin/countries')->with('success', 'Successfully country added');
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
        $country = Country::find($id);
        return view('admin.country.edit', compact('country'));
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
            'code'  =>   ['required', Rule::unique('countries')->ignore($id)],
            'phone_no_prefix' => ['required', Rule::unique('countries')->ignore($id)]
        ]);

        $country = Country::find($id);
        $country->name = $request['name'];
        $country->code = $request['code'];
        $country->phone_no_prefix = $request['phone_no_prefix'];
        $country->save();
        return redirect('admin/countries')->with('success', 'Successfully country updated');
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
