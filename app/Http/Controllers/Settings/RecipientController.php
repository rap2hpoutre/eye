<?php

namespace Eyewitness\Eye\Http\Controllers\Settings;

use Eyewitness\Eye\Eye;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\Notifications\Recipient;
use Eyewitness\Eye\Notifications\Messages\TestMessage;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RecipientController extends Controller
{
    use ValidatesRequests;

    /**
     * Show the create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eyewitness::settings.recipients.create');
    }

    /**
     * Delete the given recipient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $recipient = Recipient::findOrFail($id);
        $recipient->delete();

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The recipient has been deleted. They will not receive any further notifications from Eyewitness.');
    }

    /**
     * Send the given recipient a test notificationn..
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendTest(Request $request, $id)
    {
        $recipient = Recipient::findOrFail($id);
        app(Eye::class)->notifier()->sendTo($recipient, new TestMessage);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The recipient has been sent a test '.$recipient->type.' notification.');
    }

    /**
     * Create an email recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function email(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'email',
            'address' => $request->email,
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The email recipient has been created. You can now click the button on the recipient and instantly send a test notification to ensure it is working.');
    }

    /**
     * Create a Slack recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function slack(Request $request)
    {
        $this->validate($request, [
            'slackurl' => 'required|url',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'slack',
            'address' => $request->slackurl,
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The Slack recipient has been created. You can now click the button on the recipient and instantly send a "test notification" to ensure it is working.');
    }

    /**
     * Create a PushOver recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pushover(Request $request)
    {
        $this->validate($request, [
            'pushoverkey' => 'required|string|min:1|max:80',
            'pushoverapi' => 'required|string|min:1|max:80',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'pushover',
            'address' => $request->pushoverkey,
            'meta' => ['token' => $request->pushoverapi],
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The Pushover recipient has been created. You can now click the button on the recipient and instantly send a "test notification" to ensure it is working.');
    }

    /**
     * Create a PagerDuty recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagerduty(Request $request)
    {
        $this->validate($request, [
            'pagerdutykey' => 'required|string|min:1|max:80',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'pagerduty',
            'address' => $request->pagerdutykey,
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The PagerDuty recipient has been created. You can now click the button on the recipient and instantly send a "test notification" to ensure it is working.');
    }

    /**
     * Create a HipChat recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hipchat(Request $request)
    {
        $this->validate($request, [
            'hipchattoken' => 'required|string|min:1|max:80',
            'roomid' => 'required|string|min:4|max:7',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'hipchat',
            'address' => $request->roomid,
            'meta' => ['token' => $request->hipchattoken],
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The HipChat recipient has been created. You can send a test notification to ensure it is working by click on the button next to the recipient.');
    }

    /**
     * Create a Webhook recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $this->validate($request, [
            'webhook' => 'required|url',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'webhook',
            'address' => $request->webhook,
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The Webhook recipient has been created. You can now click the button on the recipient and instantly send a "test notification" to ensure it is working.');
    }

    /**
     * Create a Nexmo recipient.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nexmo(Request $request)
    {
        $this->validate($request, [
            'nexmo_phone' => 'required|string',
            'nexmo_api_key' => 'required|string',
            'nexmo_api_secret' => 'required|string',
            'low' => 'required|boolean',
            'medium' => 'required|boolean',
            'high' => 'required|boolean',
        ]);

        Recipient::create([
            'type' => 'nexmo',
            'address' => $request->nexmo_phone,
            'meta' => [
                'api_key' => $request->nexmo_api_key,
                'api_secret' => $request->nexmo_api_secret,
            ],
            'low' => $request->low,
            'medium' => $request->medium,
            'high' => $request->high,
        ]);

        return redirect(route('eyewitness.settings.index').'#recipients')->withSuccess('The Nexmo recipient has been created. You can now click the button on the recipient and instantly send a "test notification" to ensure it is working.');
    }
}
