<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Campaign\CampaignSetTaskToSentMessage;
use YellowProject\Campaign;
use YellowProject\CampaignItem;
use YellowProject\CampaignMessage;
use YellowProject\CampaignSticker;
use YellowProject\Campaign\CampaignSchedule;
use YellowProject\Campaign\ScheduleCampaign;
use YellowProject\Segment\Segment;
use YellowProject\Segment\QuickSegment;
use YellowProject\Segment\CoreFunction as SegmentCoreFunction;

class CoreFunction extends Model
{
    public static function setDataTaskToSendMessage()
    {
    	$campaignSetTaskToSentMessages = CampaignSetTaskToSentMessage::where('check_status',1)->get();
    	foreach ($campaignSetTaskToSentMessages as $key => $campaignSetTaskToSentMessage) {
    		$campaignSetTaskToSentMessage->update([
    			'check_status' => 0
    		]);
    		$campaign = Campaign::find($campaignSetTaskToSentMessage->campaign_id);
    		if($campaign){
	    		if($campaignSetTaskToSentMessage->type == 'normal'){
	    			if($campaign->segment_type == 'normal'){
			            $segment = $campaign->segment;
			            $segmentFormat = Segment::segmentSetToFormat($segment->id);
			            $datas = SegmentCoreFunction::queryData($segmentFormat);
			        }else{
			            $segment = $campaign->quickSegment;
			            $datas = QuickSegment::getDatas($segment->id);
			        }

			        $campaign->update([
		                'status'  => 'Sent',
		            ]);
			        
			        $mids = Segment::segmentCampaign($datas);
			        Campaign::sentCampaign($campaign,$mids);
	    		}else{
	    			$campaign->update([
		                'is_start_schedule'  => 1,
		                'status'  => 'Sent',
		            ]);

		            $scheduleCampaign = ScheduleCampaign::where('campaign_id',$campaign->id)->first();
		            if($scheduleCampaign){
		                $scheduleCampaign->update([
		                    'status' => 'process',
		                ]);
		            }else{
		                ScheduleCampaign::create([
		                    'campaign_id' => $campaign->id,
		                    'status' => 'process',
		                ]);
		            }

		            Campaign::setScheduleData($campaign);
	    		}
    		}
    	}
    }
}
