<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use YellowProject\Segment;
use YellowProject\Company;
use YellowProject\MessageFolder;
use YellowProject\Message;
use YellowProject\CampaignItem;
use YellowProject\TrackingURL;
use YellowProject\Field;
use YellowProject\CampaignTrigger;
use YellowProject\LineWebHooks;
use YellowProject\Segment\Segment;
use YellowProject\Segment\QuickSegment;
// use YellowProject\CampaignSchedule;
use YellowProject\Campaign\CampaignSchedule;
use YellowProject\Campaign\ScheduleSet;
use YellowProject\Campaign\ScheduleCampaign;
use YellowProject\Campaign\CampaignRecurringTask;
use YellowProject\Campaign\CampaignSendMessage;
use YellowProject\TemplateMessage\CoreFunction as TemplateMessageCoreFunction;
use YellowProject\Segment\CoreFunction as SegmentCoreFunction;
use Carbon\Carbon;
use Log;

class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alt_text',
        'send_time',
        'datetime',
        'start_date',
        'end_date',
        'is_active',
        'folder_id',
        'segment_id',
        'schedule_id',
        'send_status',
        'is_start_schedule',
        'segment_type',
        'status',
    ];

    public $timestamps = true;


  //Event Listener
     protected static function boot() {
        parent::boot();

        static::deleting(function($campaign) {
            $campaign->campaignItems()->delete();
        });


    }


    public function campaignItems()
    {
        return $this->hasMany(CampaignItem::class,'campaign_id','id');
    }

    public function campaignSendMessages()
    {
        return $this->hasMany(CampaignSendMessage::class,'campaign_id','id');
    }

    public function folder()
    {
        return $this->belongsTo(MessageFolder::class, 'folder_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(CampaignSchedule::class, 'schedule_id', 'id');
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class, 'segment_id', 'id');
    }

    public function quickSegment()
    {
        return $this->belongsTo(QuickSegment::class, 'segment_id', 'id');
    }


    //
    public static function checkDuplicate($name, $iD = null)
    {
        if (is_null($iD)) {
            $item = Campaign::where('name',$name)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        } else {
            $item = Campaign::where('name',$name)->where('id',"!=", $iD)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        }
        
    }
    


    public static function sentCampaign($campaign,$subscriberDatas)
    {
        // *** $subscriberDatas = Arrays mids ***
        //$campaign = Campaign::find($id);

        $items  = $campaign->campaignItems;
        // $messages = collect();

        $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
        foreach ($subscriberDatas as $key => $subscriberData) {
            $messages = array();
            foreach ($items as $item) {
                switch ($item->messageType->type) {
                    case 'text':
                        $messages[] = [
                            "type" => "text",
                            'text' => CampaignMessage::encodeMessageEmo($item->message->message,$subscriberData),
                        ];

                        break;
                    case 'sticker':
                        $messages[] = [
                            "type"      => "sticker",
                            "packageId" => $item->sticker->packageId,
                            "stickerId" =>  $item->sticker->stickerId
                        ];
                    break;

                    case 'imagemap':
                        // $messages = array();
                        $messages[] = LineWebHooks::setImagemap($item->campaign_richmessage_id);
                        // dd($richMessageData);
                        // $messages->push($richMessageData);
                    break;

                    case 'template_message':
                        $messages[] = TemplateMessageCoreFunction::setTemplateMessage($item->template_message_id);
                    break;
                }
            }

            if (isset($messages) && sizeof($messages) > 0) {

                $datas = collect();
                $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
                $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
                
                $data = collect([
                    "to" => $subscriberData,
                    // "to" => ['U0a6eb3ec8ce36a962b94b0eb8effb320'],
                    "messages"   => $messages
                ]);
                $datas->put('data', $data->toJson());

                CampaignSendMessage::create([
                    'campaign_id' => $campaign->id,
                    'mid' => $subscriberData,
                ]);
                
                self::sent($datas);
            }
        }
    }

    private static function sent($arrDatas)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $arrDatas['sentUrl'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $arrDatas['data'],
          CURLOPT_HTTPHEADER => array(
            "authorization: ".$arrDatas['token'],
            "cache-control: no-cache",
            "content-type: application/json; charset=UTF-8",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::debug('cURL Error #: =>'. $err);
        } else {
            Log::debug(' Reply After eventFollow =>'. $response);
        }
    }


    public static function setScheduleData($campaign)
    {
        $schedule = $campaign->schedule;
        if($campaign->segment_type == 'normal'){
            // $segment = $campaign->segment;
            $segmentFormat = Segment::segmentSetToFormat($campaign->segment_id);
            $subscriberLines = SegmentCoreFunction::queryData($segmentFormat);
            // $subscriberLines = Segment::getSegmentData($campaign->segment_id);
            // $datas = Segment::getSegmentData($segment->id);
        }else{
            // $segment = $campaign->quickSegment;
            $subscriberLines = QuickSegment::getDatas($campaign->segment_id);
            // $datas = QuickSegment::getDatas($segment->id);
        }
        $subscriberDatas = Segment::segmentCampaign($subscriberLines);
        // dd($subscriberDatas);
        if($schedule->schedule_type == 'One-time'){
            if(count($subscriberDatas) > 0){
                foreach ($subscriberDatas as $key => $mid) {
                    ScheduleSet::create([
                        'campaign_id' => $campaign->id,
                        'mid' => $mid,
                        'send_time' => $schedule->schedule_start_date,
                        'status' => 'process',
                    ]);
                }
            }
        }else{
            $dateNow = Carbon::now()->format('Y-m-d H:i:s');
            if($schedule->schedule_start_date <= $dateNow && $dateNow <= $schedule->schedule_end_date){
                $dt = Carbon::parse($schedule->schedule_end_date);
                $diffInDays = Carbon::now()->diffInDays($dt, false);
                for ($i=1; $i <= $diffInDays; $i++) {
                    if($schedule->schedule_recurrence_type == 'Daily'){
                        $date = Carbon::now()->addDays($i)->format('Y-m-d');
                        $dateTimeSend = $date." ".$schedule->schedule_schedule_send_time;
                        CampaignRecurringTask::create([
                            'campaign_schedule_id' => $schedule->id,
                            'campaign_id' => $campaign->id,
                            'schedule_start_date' => $schedule->schedule_start_date,
                            'schedule_end_date' => $schedule->schedule_end_date,
                            'schedule_send_date' => $dateTimeSend
                        ]);
                    }else{
                        if($schedule->schedule_recurrence_running == -1){
                            $date = Carbon::now()->addDays($i);
                            $flag = 0;
                            if($schedule->schedule_recurrence_sun && $date->isSunday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_mon && $date->isMonday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_tue && $date->isTuesday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_wed && $date->isWednesday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_thu && $date->isThursday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_fri && $date->isFriday){
                                $flag = 1;
                            }else if($schedule->schedule_recurrence_sat && $date->isSaturday){
                                $flag = 1;
                            }else{
                                $flag = 0;
                            }

                            if($flag == 1){
                                $date = $date->format('Y-m-d');
                                $dateTimeSend = $date." ".$schedule->schedule_schedule_send_time;
                                CampaignRecurringTask::create([
                                    'campaign_schedule_id' => $schedule->id,
                                    'campaign_id' => $campaign->id,
                                    'schedule_start_date' => $schedule->schedule_start_date,
                                    'schedule_end_date' => $schedule->schedule_end_date,
                                    'schedule_send_date' => $dateTimeSend
                                ]);
                            }
                        }else{
                            $date = Carbon::now()->addDays($i);
                            if($schedule->schedule_recurrence_running == 1){
                                $dateNew = $date->format('d');
                                if($dateNew == 1){
                                    $date = Carbon::now()->addDays($i)->format('Y-m-d');
                                    $dateTimeSend = $date." ".$schedule->schedule_schedule_send_time;
                                    CampaignRecurringTask::create([
                                        'campaign_schedule_id' => $schedule->id,
                                        'campaign_id' => $campaign->id,
                                        'schedule_start_date' => $schedule->schedule_start_date,
                                        'schedule_end_date' => $schedule->schedule_end_date,
                                        'schedule_send_date' => $dateTimeSend
                                    ]);
                                }
                            }else if($schedule->schedule_recurrence_running == 2){
                                $lastDayOfTheMonth = $date->endOfMonth()->format('d');
                                $dateNew = Carbon::now()->addDays($i)->format('d');
                                if($dateNew == $lastDayOfTheMonth){
                                    $date = Carbon::now()->addDays($i)->format('Y-m-d');
                                    $dateTimeSend = $date." ".$schedule->schedule_schedule_send_time;
                                    CampaignRecurringTask::create([
                                        'campaign_schedule_id' => $schedule->id,
                                        'campaign_id' => $campaign->id,
                                        'schedule_start_date' => $schedule->schedule_start_date,
                                        'schedule_end_date' => $schedule->schedule_end_date,
                                        'schedule_send_date' => $dateTimeSend
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function setScheduleRecurringData()
    {
        $dateStart = Carbon::now()->format('Y-m-d')." 00:00:00";
        $dateEnd = Carbon::now()->addDays(1)->format('Y-m-d')." 00:00:00";
        // dd($dateStart,$dateEnd);
        $campaignRecurringTasks = CampaignRecurringTask::where('schedule_send_date','>=',$dateStart)->where('schedule_send_date','<=',$dateEnd)->get();
        if($campaignRecurringTasks->count() > 0){
            foreach ($campaignRecurringTasks as $key => $campaignRecurringTask) {
                $campaign = $campaignRecurringTask->campaign;
                if($campaign->is_active){
                    if($campaign->segment_type == 'normal'){
                        $segmentFormat = Segment::segmentSetToFormat($campaign->segment_id);
                        $subscriberLines = SegmentCoreFunction::queryData($segmentFormat);
                    }else{
                        $subscriberLines = QuickSegment::getDatas($campaign->segment_id);
                    }
                    $subscriberDatas = Segment::segmentCampaign($subscriberLines);
                    foreach ($subscriberDatas as $key => $mid) {
                        ScheduleSet::create([
                            'campaign_id' => $campaign->id,
                            'mid' => $mid,
                            'send_time' => $campaignRecurringTask->schedule_start_date,
                            'status' => 'process',
                        ]);
                    }
                    $campaignRecurringTask->delete();
                }
            }
        }
        // dd($campaignRecurringTasks);
    }

    public static function scheduleSentMessage()
    {
        $scheduleCampaigns = ScheduleCampaign::where('status','process')->get();
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        foreach ($scheduleCampaigns as $key => $scheduleCampaign) {
            $scheduleCampaign->update([
                'status' => 'success',
            ]);
            $scheduleSets = ScheduleSet::where('campaign_id',$scheduleCampaign->campaign_id)->where('status','process')->where('send_time','<=',$dateNow)->get();
            if($scheduleSets->count() > 0){
                foreach ($scheduleSets as $key => $scheduleSet) {
                    $scheduleSet->update([
                        'status' => 'success',
                    ]);
                    $campaign = Campaign::find($scheduleSet->campaign_id);
                    $mids = [$scheduleSet->mid];
                    Campaign::sentCampaign($campaign,$mids);
                }
            }
        }
    }
}