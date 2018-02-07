<?php

namespace Eyewitness\Eye\Http\Controllers;

use Exception;
use Eyewitness\Eye\Eye;
use Illuminate\Http\Request;
use Eyewitness\Eye\Repo\Queue;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Eyewitness\Eye\Repo\Notifications\History;

class FailedJobController extends Controller
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * Create a new Failed Job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->eye = app(Eye::class);
    }

    /**
     * Show the given job.
     *
     * @param  integer  $queue_id
     * @param  integer  $job_id
     * @return \Illuminate\Http\Response
     */
    public function show($queue_id, $job_id)
    {
        $queue = Queue::findOrFail($queue_id);
        $job = $this->eye->queue()->getFailedJob($job_id);

        return view('eyewitness::queue.failed_job')->withJob($job)->withQueue($queue);
    }

    /**
     * Retry the given job.
     *
     * @param  integer  $queue_id
     * @param  integer  $job_id
     * @return \Illuminate\Http\Response
     */
    public function retry($queue_id, $job_id)
    {
        try {
            Artisan::call('queue:retry', ['id' => [$job_id]]);
        } catch (Exception $e) {
            $this->eye->logger()->error('Retry of failed job unsuccessful', $e, ['job_id' => $job_id, 'queue_id' => $queue_id]);
            return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withError('Sorry - we could not retry that job. Please check your logs for further information.');
        }

        return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withSuccess('The job has been pushed back onto the queue to be tried again.');
    }

    /**
     * Delete a specific failed job.
     *
     * @param  integer  $queue_id
     * @param  integer  $job_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($queue_id, $job_id)
    {
        try {
            if (app('queue.failer')->forget($job_id)) {
                return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withSuccess('The job has been deleted from the failed table.');
            }
        } catch (Exception $e) {
            $this->eye->logger()->error('Delete of failed job unsuccessful', $e, ['job_id' => $job_id, 'queue_id' => $queue_id]);
            return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withError('Sorry - we could not delete that job. Please check your logs for further information.');
        }

        return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withError('Sorry - we could not find that job in the list. Perhaps it was already deleted?');
    }

    /**
     * Retry all the jobs on this queue.
     *
     * @param  integer  $queue_id
     * @return \Illuminate\Http\Response
     */
    public function retryAll($queue_id)
    {
        $queue = Queue::findOrFail($queue_id);
        $jobs = $this->eye->queue()->getFailedJobs($queue);
        $failed = false;

        foreach ($jobs as $job) {
            try {
                Artisan::call('queue:retry', ['id' => [$job->id]]);
            } catch (Exception $e) {
                $this->eye->logger()->error('Retry of failed job unsuccessful', $e, ['job_id' => $job->id, 'queue_id' => $queue_id]);
                $failed = true;
            }
        }

        if ($failed) {
            return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withWarning('Some of the jobs did not get retried successfully. You should check your logs for further information.');
        }

        return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withSuccess('All the jobs have been pushed back onto the queue to be tried again.');
    }

    /**
     * Delete a specific failed job.
     *
     * @param  integer  $queue_id
     * @return \Illuminate\Http\Response
     */
    public function destroyAll($queue_id)
    {
        $queue = Queue::findOrFail($queue_id);
        $jobs = $this->eye->queue()->getFailedJobs($queue);
        $failed = false;

        foreach ($jobs as $job) {
            try {
                app('queue.failer')->forget($job->id);
            } catch (Exception $e) {
                $this->eye->logger()->error('Delete of failed job unsuccessful', $e, ['job_id' => $job->id, 'queue_id' => $queue_id]);
                $failed = true;
            }
        }

        if ($failed) {
            return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withWarning('Some of the jobs did not get deleted successfully. You should check your logs for further information.');
        }

        return redirect(route('eyewitness.queues.show', $queue_id).'#failed')->withSuccess('All the jobs have been deleted from the failed table.');
    }
}
