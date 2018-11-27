<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Validator;
use DB;

class ContactController extends Controller
{
    /**
    *@author Odole Olukayode <kaythinks@gmail.com>
    *@var string $contact 
    */

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devs = DB::table('contacts')
                 ->select('category', DB::raw('count(id) as num_of_devs'))
                 ->groupBy('category')
                 ->get();

        return response()->json($devs,200);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$credentials = $request->only('first_name','last_name','email','phone_no','github','category');*/
        $credentials = $request->all();

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts',
            'phone_no' => 'required|numeric',
            'github' => 'required|string|max:255',
            'category' => 'required|string|max:255'
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        $this->contact->create($credentials);
        return response()->json(['success'=> 'Developer successffully created'],201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dev = $this->contact->find($id);
        if (!$dev) {
           return response()->json(['success'=> false],404); 
        }
        return response()->json($dev,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $credentials = $request->all();

        $rules = [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts'.$id,
            'phone_no' => 'nullable|numeric',
            'github' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255'
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        $dev = $this->contact->find($id);
        if (!$dev) {
           return response()->json(['success'=> false],404); 
        }
        $dev->update($credentials);
        return response()->json(['success'=> 'Successfully updated'],200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dev = $this->contact->find($id);
        if (!$dev) {
           return response()->json(['success'=> false],404); 
        }
        $dev->delete();
        return response()->json(['success'=>'Successfully deleted'],200);
    }
}
