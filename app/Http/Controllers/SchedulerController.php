<?php

namespace Eyewitness\Eye\Http\Controllers;

use Eyewitness\Eye\Eye;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\Scheduler;
use Eyewitness\Eye\Tools\ChartTransformer;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SchedulerController extends Controller
{
    use ValidatesRequests;

    /**
     * Show the scheduler.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scheduler = Scheduler::findOrFail($id);

        return view('eyewitness::scheduler.show')->withEye(app(Eye::class))
                                                 ->withScheduler($scheduler)
                                                 ->withTransformer(new ChartTransformer);
    }

    /**
     * Update the scheduler.
     *
     * @param  Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $scheduler = Scheduler::findOrFail($id);

        $this->validate($request, [
            'alert_on_missed' => 'required|boolean',
            'alert_on_fail' => 'required|boolean',
            'alert_run_time_greater_than' => 'required|numeric|min:0|max:9999',
            'alert_run_time_less_than' => 'required|numeric|min:0|max:9999',
            'alert_last_run_greater_than' => 'required|numeric|min:0|max:9999999',
        ]);

        $scheduler->fill($request->only([
            'alert_on_missed',
            'alert_on_fail',
            'alert_run_time_greater_than',
            'alert_run_time_less_than',
            'alert_last_run_greater_than'
        ]));

        $scheduler->save();

        return redirect(route('eyewitness.schedulers.show', $scheduler->id).'#settings')->withSuccess('The scheduler configuration has been updated.');
    }

    /**
     * Destroy the scheduler.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $scheduler = Scheduler::findOrFail($id);

        $scheduler->history()->delete();
        $scheduler->delete();

        return redirect(route('eyewitness.dashboard').'#scheduler')->withSuccess('The scheduler has been deleted.');
    }
}
