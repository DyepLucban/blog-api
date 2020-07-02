<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required|min:8',
                'subject' => 'required|min:5',
                'message' => 'required|min:10',            
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 422,
                    'message' => $validator->errors(),
                ], 422);
            }

            $details = [
                'sender_email' => $request->input('email'),
                'sender_name' => $request->input('name'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
            ];

            \Mail::to('lucbanjep@gmail.com')->send(new SendEmail($details));

            return response()->json([
                'message' => 'Email Successfully Sent!',
                'status' =>  'success',
                'code' => 200
            ], 200);

        } catch (\Execption $e) {
            return $e->getMessage();
        }
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
