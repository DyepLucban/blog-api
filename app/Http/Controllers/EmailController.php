<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('emails.inquire');
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
        $details = [
            'sender_email' => $request->input('email'),
            'sender_name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ];

        \Mail::to('lucbanjep@gmail.com')->send(new SendEmail($details));

        return response()->json([
            'message' => 'Email Successfully Send',
            'status' =>  'success',
            'code' => 200
        ], 200);
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
        //
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
        //
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

    public function sendEmail(Request $request)
    {
        // $to_name = "test name";
        // $to_email = "lucbanjep@gmail.com";

        // $details = [
        //     'title' => 'Hi this is title',
        //     'body' => 'Hi this is a body'
        // ];

        // \Mail::to('lucbanjep@gmail.com')->send(new SendEmail($details));

        // return 'email sent';        
    }
}
