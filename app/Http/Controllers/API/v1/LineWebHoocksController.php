<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use YellowProject\Http\Controllers\Controller;

use Log;
use YellowProject\LineWebHooks;
use YellowProject\GreetingMessage;
use YellowProject\AutoReplyDefault;
use YellowProject\LineUserProfile;
use YellowProject\HistoryAddBlock;
use YellowProject\GeneralFunction\CoreFunction;
use YellowProject\LineFunction\LineCoreFunction;
use Carbon\Carbon;

class LineWebHoocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       dd('WTF');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
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


    public function getReceive(Request $request)
    {
        Log::debug('Not Support Method Get');
        return \Response::json(ok, 200);       
    }

    public function postReceive(Request $request)
    {
        fastcgi_finish_request();
        $now = Carbon::now();
        $dateNowStart = $now;
        Log::debug("date start => ".$dateNowStart);
        //sleep(1);
        //Log::debug("sleep => 1 sec ");

        //success
        $header = $request->header();
        $body = $request->all();
        try  {
            //Log::debug($header);
            Log::debug($body);
            $lineWebhookCoreFunction = LineCoreFunction::lineWebhookCoreFunction($header,$body,$dateNowStart);
            return \Response::json([
                'status' => 'ok'
            ], 200);
        } 
        catch(Exception $e) {
            Log::debug('Error '.$e);
            return \Response::json([
                'status' => 'ok'
            ], 200);
        }

    }
}
