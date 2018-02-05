<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-lg ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('ui-04', 'svgcolor-white')
            </div>
        </div>
        <div class="flex w-full justify-between">
            <h1 class="font-hairline ml-4 mt-2 text-2xl">Settings for <span class="font-normal">{{ $queue->connection }} ({{ $queue->tube }})</span></h1>

            <div class="mt-1 mr-2">
                <eye-menu title='' color='svgcolor-grey'>
                    <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.queues.destroy', $queue->id) }}" data-method="delete">
                            <div class="flex items-center">
                                <div>
                                    @eyewitness_svg('trash', 'dropdown-menu-svg', 20, 20)
                                </div>
                                <div class="ml-3 -mt-1 font-normal">
                                    Delete
                                </div>
                            </div>
                        </button>
                    </div>
                </eye-menu>
            </div>
        </div>
    </div>
    <div class="text-center px-4 pb-6">
        @eyewitness_tutorial('Here you can configure how this specific queue tube is monitored and which alert(s) are sent. This gives you the flexibility to disable alerts on certain queues that you do not worry about. You can also set normal thresholds, and Eyewitness will alert you if the queue starts operating outside of those boundaries.')

        @eyewitness_info('A setting of "0" means that alert will be disabled.')

        <form v-on:submit.capture="formSubmit" class="form pt-8" method="POST" action="{{ route('eyewitness.queues.update', $queue->id) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="alert_on_failed_job" value="0">


            <div class="flex md:items-center pb-6">
                <div class="w-2/3 md:w-1/2 text-right mr-4">
                    <label class="text-grey-dark" for="alert_on_failed_job">Send alert if queue has failed job</label>
                </div>
                <div class="w-1/3 md:w-1/2 text-left">
                    <div class="pretty p-switch p-fill">
                        <input id="alert_on_failed_job" type="checkbox" {{ old('alert_on_failed_job', $queue->alert_on_failed_job) == '1' ? 'checked="checked"' : '' }} value="1" name="alert_on_failed_job"/>
                        <div class="state p-primary">
                            <label class="text-grey-darker"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex md:items-center pb-4">
                <div class="w-2/3 md:w-1/2 text-right">
                    <label class="text-grey-dark" for="alert_heartbeat_greater_than">Alert if last queue heartbeat time greater than (seconds)</label>
                </div>
                <div class="w-1/3 md:w-1/2 text-left">
                    <div class="flex w-24 text-left">
                        <eye-input id="alert_heartbeat_greater_than" type="text" name="alert_heartbeat_greater_than" label="" value="{{ old('alert_heartbeat_greater_than', $queue->alert_heartbeat_greater_than) }}"></eye-input>
                    </div>
                </div>
            </div>

            <div class="flex md:items-center pb-4">
                <div class="w-2/3 md:w-1/2 text-right">
                    <label class="text-grey-dark" for="alert_wait_time_greater_than">Alert if queue wait time greater than (seconds)</label>
                </div>
                <div class="w-1/3 md:w-1/2 text-left">
                    <div class="flex w-24 text-left">
                        <eye-input id="alert_wait_time_greater_than" type="text" name="alert_wait_time_greater_than" label="" value="{{ old('alert_wait_time_greater_than', $queue->alert_wait_time_greater_than) }}"></eye-input>
                    </div>
                </div>
            </div>

            <div class="flex md:items-center pb-4">
                <div class="w-2/3 md:w-1/2 text-right">
                    <label class="text-grey-dark" for="alert_pending_jobs_greater_than">Alert if queue pending jobs greater than</label>
                </div>
                <div class="w-1/3 md:w-1/2 text-left">
                    <div class="flex w-24 text-left">
                        <eye-input id="alert_pending_jobs_greater_than" type="text" name="alert_pending_jobs_greater_than" label="" value="{{ old('alert_pending_jobs_greater_than', $queue->alert_pending_jobs_greater_than) }}"></eye-input>
                    </div>
                </div>
            </div>

            <div class="flex md:items-center pb-4">
                <div class="w-2/3 md:w-1/2 text-right">
                    <label class="text-grey-dark" for="alert_failed_jobs_greater_than">Alert if queue failed jobs greater than</label>
                </div>
                <div class="w-1/3 md:w-1/2 text-left">
                    <div class="flex w-24 text-left">
                        <eye-input id="alert_failed_jobs_greater_than" type="text" name="alert_failed_jobs_greater_than" label="" value="{{ old('alert_failed_jobs_greater_than', $queue->alert_failed_jobs_greater_than) }}"></eye-input>
                    </div>
                </div>
            </div>

            <div class="text-right mt-8">
                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('check-square-11', 'svgcolor-white h-4 w-4')'>Update queue</eye-btn>
            </div>
        </form>
    </div>
</div>
