<?php

namespace Eyewitness\Eye\Http\Controllers;

use Eyewitness\Eye\Eye;
use Illuminate\Http\Request;
use Eyewitness\Eye\Repo\Queue;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Tools\ChartTransformer;
use Illuminate\Foundation\Validation\ValidatesRequests;

class QueueController extends Controller
{
    use ValidatesRequests;

    /**
     * Show the queue.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $queue = Queue::findOrFail($id);

        return view('eyewitness::queue.show')->withEye(app(Eye::class))
                                             ->withQueue($queue)
                                             ->withTransformer(new ChartTransformer);
    }

    /**
     * Update the queue.
     *
     * @param  Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $queue = Queue::findOrFail($id);

        $this->validate($request, [
            'alert_on_failed_job' => 'required|boolean',
            'alert_heartbeat_greater_than' => 'required|numeric|min:0|max:99999',
            'alert_pending_jobs_greater_than' => 'required|numeric|min:0|max:99999',
            'alert_failed_jobs_greater_than' => 'required|numeric|min:0|max:99999',
            'alert_wait_time_greater_than' => 'required|numeric|min:0|max:99999',
        ]);

        $queue->fill($request->only(['alert_on_failed_job', 'alert_heartbeat_greater_than', 'alert_pending_jobs_greater_than', 'alert_failed_jobs_greater_than', 'alert_wait_time_greater_than']));
        $queue->save();

        return redirect(route('eyewitness.queues.show', $queue->id).'#settings')->withSuccess('The queue configuration has been updated.');
    }

    /**
     * Destroy the queue.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $queue = Queue::findOrFail($id);

        $queue->history()->delete();
        $queue->delete();

        return redirect(route('eyewitness.dashboard').'#queue')->withSuccess('The queue has been deleted.');
    }
}
