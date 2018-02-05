@extends('eyewitness::layout')

@section('content')

    <div class="flex flex-grow w-full max-w-1200 mx-auto pt-6 mt-8 md:px-4 lg:px-8">
        <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner w-full">
            <div class="flex mb-8 border-b border-grey-light pb-4">
                <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                    <div class="h-full flex justify-center items-center">
                        @eyewitness_svg('multiple-11', 'svgcolor-white')
                    </div>
                </div>
                <h1 class="font-hairline ml-4 mt-2 text-2xl">Add new notification recipient</h1>
            </div>
            <div class="text-center px-6 pb-4">
                <p class="text-2xl text-grey-darker font-medium mb-4">1. Please choose a notification type:</p>

                <eye-recipients v-cloak>
                    <eye-recipient name="Email" image="@eyewitness_img_raw_base64('email.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.email') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure Email notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">Please enter an email address you would like to receive notifications. Note: this will use your application mail driver, so you must have email configured.</p>
                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('email-83', 'mb-3', 24, 24)
                                <eye-input type="email" name="email" label="Email" value="{{ old('email') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create Email recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="Slack" image="@eyewitness_img_raw_base64('slack.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.slack') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure Slack notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">Creating a Slack hook is quick and easy. Just follow these steps:</p>
                            <ol class="text-left text-grey mb-8">
                                <li class="py-1">Go to Slack and select <span class="code">Add an app or integration</span>.</li>
                                <li class="py-1">Select (or search) for <span class="code">Incoming WebHooks</span>.</li>
                                <li class="py-1">Click on <span class="code">Add Configuration</span>, select the channel you would like Eyewitness.io notifications to be sent to, and then click on <span class="code">Add Incoming WebHooks integration</span>.</li>
                                <li class="py-1">Copy and paste the <span class="code">Webhook URL</span> into the field below:</li>
                            </ol>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('link-67', 'mb-3', 24, 24)
                                <eye-input type="text" name="slackurl" label="Slack Webhook URL" value="{{ old('slackurl') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create Slack recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="Pushover" image="@eyewitness_img_raw_base64('pushover.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.pushover') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure Pushover notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">To create a Pushover recipient just follow these steps:</p>
                            <ol class="text-left text-grey mb-8">
                                <li class="py-1">Login to your Pushover account online.</li>
                                <li class="py-1">Copy and paste your <span class="code">user key</span> below.</li>
                                <li class="py-1">You also need an API Token. You can create a free application on Pushover, then copy and paste your <span class="code">API Token/Key</span> below.</li>
                            </ol>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="pushoverkey" label="Pushover User Key" value="{{ old('pushoverkey') }}"></eye-input>
                            </div>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="pushoverapi" label="Pushover API Token/Key" value="{{ old('pushoverapi') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create Pushover recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="Nexmo" image="@eyewitness_img_raw_base64('nexmo.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.nexmo') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure Nexmo SMS notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">To be able to send SMS via Nexmo just follow these steps:</p>
                            <ol class="text-left text-grey mb-8">
                                <li class="py-1">Type the full international full phone of the person you want to send the SMS alert.</li>
                                <li class="py-1">Go to Nexmo and get your <span class="code">api_key</span> and <span class="code">api_secret</span> and type them below:</li>
                            </ol>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('phone-call', 'mb-3', 24, 24)
                                <eye-input type="text" name="nexmo_phone" label="Recipients full international phone number" value="{{ old('nexmo_phone') }}"></eye-input>
                            </div>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="nexmo_api_key" label="Nexmo API key" value="{{ old('nexmo_api_key') }}"></eye-input>
                            </div>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('lock', 'mb-3', 24, 24)
                                <eye-input type="text" name="nexmo_api_secret" label="Nexmo API secret" value="{{ old('nexmo_api_secret') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create Nexmo recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="PagerDuty" image="@eyewitness_img_raw_base64('pagerduty.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.pagerduty') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure PagerDuty notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">To create a PagerDuty recipient just follow these steps:</p>
                            <ol class="text-left text-grey mb-8">
                                <li class="py-1">Go to PagerDuty and go to the Service you want to link.</li>
                                <li class="py-1">In the Service page - click <span class="code">Integrations</span> and then click <span class="code">New Integration</span>.</li>
                                <li class="py-1">Type an Integration Name of <span class="code">Eyewitness</span> and select an Integration Type of <span class="code">Use our API directly</span>. Then click <span class="code">Add Integration</span>.</li>
                                <li class="py-1">Copy and paste the generated <span class="code">Integration Key</span> into the field below:</li>
                            </ol>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="pagerdutykey" label="PagerDuty Integration Key" value="{{ old('pagerdutykey') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create PagerDuty recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="HipChat" image="@eyewitness_img_raw_base64('hipchat.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.hipchat') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure HipChat notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">Creating a HipChat room alert is quick and easy. Just follow these steps:</p>
                            <ol class="text-left text-grey mb-8">
                                <li class="py-1">Go to HipChat and select <span class="code">Account Settings</span>.</li>
                                <li class="py-1">Select <span class="code">API access</span>.</li>
                                <li class="py-1">Create a new token with a label of <span class="code">Eyewitness</span>. It must have the <span class="code">Send Notification</span> scope.</li>
                                <li class="py-1">Copy and paste the generated <span class="code">token</span> list below.</li>
                                <li class="py-1">Your <span class="code">Room ID</span> is the 4-7 digit number in the URL of the room you want notifications to be sent to. Paste it below:</li>
                            </ol>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="hipchattoken" label="HipChat Token" value="{{ old('hipchattoken') }}"></eye-input>
                            </div>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('home-51', 'mb-3', 24, 24)
                                <eye-input type="text" name="roomid" label="HipChat Room ID" value="{{ old('roomid') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create HipChat recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                    <eye-recipient name="Webhook" image="@eyewitness_img_raw_base64('webhook.png')">
                        <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.recipients.create.webhook') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="low" value="0">
                            <input type="hidden" name="medium" value="0">
                            <input type="hidden" name="high" value="0">

                            <p class="text-2xl text-grey-darker font-medium mb-4 mt-8">2. Configure Webhook notification:</p>
                            <p class="text-grey max-w-md mx-auto mb-6">We can send a notification as <span class="code">POST</span> webhook, then you are free to act upon the alert however you like. The webhook will be <span class="code">JSON</span> in the format of:</p>
<pre class="text-white bg-black text-left mx-8 px-8 mb-8">
{
  "application": {
    "id": "the ID of the application sending the alert",
    "name": "the name of the application sending the alert"
  },
  "url": "a full url to link to the application on Eyewitness.io",
  "icon": "a full url for a 192x192 PNG icon for Eyewitness.io",
  "error": "a boolean true/false if the alert is an error or not",
  "type": "the alert type - such as queue, email, ssl etc",
  "title": "the title of the alert",
  "description": "more information about the alert",
  "meta": {
    "additional array of any extra meta information - not in all alerts"
  }
}
</pre>

                            <div class="flex mb-8 text-left">
                                @eyewitness_svg('link-67', 'mb-3', 24, 24)
                                <eye-input type="text" name="webhook" label="Webhook URL" value="{{ old('webhook') }}"></eye-input>
                            </div>

                            @include('eyewitness::settings.recipients.severity')

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>Create Webhook recipient</eye-btn>
                            </div>
                        </form>
                    </eye-recipient>
                </eye-recipients>
            </div>
        </div>
    </div>
@endsection
