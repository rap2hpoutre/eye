<?php

namespace Eyewitness\Eye\Tools;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Eyewitness\Eye\Monitors\Custom;
use Eyewitness\Eye\Repo\History\Queue;
use Eyewitness\Eye\Repo\History\Custom as CustomHistory;
use Eyewitness\Eye\Repo\History\Database;
use Eyewitness\Eye\Repo\History\Scheduler;

class ChartTransformer
{
    /**
     * The transformed scheduler data.
     *
     * @var array
     */
    protected $scheduler = [];

    /**
     * The transformed custom witness data.
     *
     * @var array
     */
    protected $custom = [];

    /**
     * The transformed database data.
     *
     * @var array
     */
    protected $database = [];

    /**
     * The transformed queue data.
     *
     * @var array
     */
    protected $queue = [];


    /**
     * Get the scheduler data and transform it into the required format for our charts.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return array
     */
    public function generateScheduler($scheduler)
    {
        if (count($this->scheduler)) {
            return $this->scheduler;
        }

        $history = Scheduler::select([DB::Raw('avg(time_to_run) as avg_time_to_run'), DB::Raw('DATE(created_at) day')])
                            ->where('scheduler_id', $scheduler->id)
                            ->groupBy('day')
                            ->get();

        // https://adamwathan.me/2016/07/14/customizing-keys-when-mapping-collections
        $history = $history->reduce(function ($history, $result) {
            $history[$result['day']] = round($result['avg_time_to_run'], 2);
            return $history;
        }, []);

        // Fill the array gaps - because the chart wont populate correctly without 0 values
        for($i=20; $i>=0; $i--) {
            $this->scheduler['day'][] = Carbon::now($scheduler->timezone)->subDay($i)->format('d');

            if (isset($history[Carbon::now($scheduler->timezone)->subDay($i)->format('Y-m-d')])) {
                $this->scheduler['count'][] = $history[Carbon::now($scheduler->timezone)->subDay($i)->format('Y-m-d')];
            } else {
                $this->scheduler['count'][] = 0;
            }
        }

        return $this->scheduler;
    }

    /**
     * Get the witness data and transform it into the required format for our charts.
     *
     * @param  \Eyewitness\Eye\Monitors\Custom  $witness
     * @return array
     */
    public function generateCustom($witness)
    {
        if (isset($this->custom[$witness->getSafeName()])) {
            return $this->custom[$witness->getSafeName()];
        }

        $history = CustomHistory::select([DB::Raw('avg(value) as avg_value'), DB::Raw('DATE(created_at) day')])
                                ->where('meta', $witness->getSafeName())
                                ->groupBy('day')
                                ->get();

        // https://adamwathan.me/2016/07/14/customizing-keys-when-mapping-collections
        $history = $history->reduce(function ($history, $result) {
            $history[$result['day']] = round($result['avg_value'], 2);
            return $history;
        }, []);

        // Fill the array gaps - because the chart wont populate correctly without 0 values
        for($i=20; $i>=0; $i--) {
            $this->custom[$witness->getSafeName()]['day'][] = Carbon::now()->subDay($i)->format('d');

            if (isset($history[Carbon::now()->subDay($i)->format('Y-m-d')])) {
                $this->custom[$witness->getSafeName()]['count'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')];
            } else {
                $this->custom[$witness->getSafeName()]['count'][] = 0;
            }
        }

        return $this->custom[$witness->getSafeName()];
    }

    /**
     * Get the database data and transform it into the required format for our charts.
     *
     * @param  \Eyewitness\Eye\Repo\Monitors\Database  $connection
     * @return array
     */
    public function generateDatabase($connection)
    {
        if (isset($this->database[$connection])) {
            return $this->database[$connection];
        }

        $history = Database::select([DB::Raw('avg(value) as avg_size'), DB::Raw('DATE(created_at) day')])
                           ->where('meta', $connection)
                           ->groupBy('day')
                           ->get();

        // https://adamwathan.me/2016/07/14/customizing-keys-when-mapping-collections
        $history = $history->reduce(function ($history, $result) {
            $history[$result['day']] = round($result['avg_size'], 2);
            return $history;
        }, []);

        // Fill the array gaps - because the chart wont populate correctly without 0 values
        for($i=20; $i>=0; $i--) {
            $this->database[$connection]['day'][] = Carbon::now()->subDay($i)->format('d');

            if (isset($history[Carbon::now()->subDay($i)->format('Y-m-d')])) {
                $this->database[$connection]['count'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')];
            } else {
                $this->database[$connection]['count'][] = 0;
            }
        }

        return $this->database[$connection];
    }

    /**
     * Get the database data and transform it into the required format for our charts.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return array
     */
    public function generateQueue($queue)
    {
        if (isset($this->queue[$queue->id])) {
            return $this->queue[$queue->id];
        }

        $history = Queue::select([DB::Raw('avg(pending_count) as avg_pending_count'),
                                  DB::Raw('sum(sonar_time) as total_sonar_time'),
                                  DB::Raw('sum(sonar_count) as total_sonar_count'),
                                  DB::Raw('sum(process_time) as total_process_time'),
                                  DB::Raw('sum(process_count) as total_process_count'),
                                  DB::Raw('sum(idle_time) as total_idle_time'),
                                  DB::Raw('sum(exception_count) as total_exception_count'),
                                  DB::Raw('DATE(date) day')])
                        ->where('queue_id', $queue->id)
                        ->groupBy('day')
                        ->get();

        // https://adamwathan.me/2016/07/14/customizing-keys-when-mapping-collections
        $history = $history->reduce(function ($history, $result) {
            $history[$result['day']]['avg_wait_time'] = $this->calculateAvg($result['total_sonar_time'], $result['total_sonar_count']);
            $history[$result['day']]['avg_process_time'] = $this->calculateAvg($result['total_process_time'], $result['total_process_count']);
            $history[$result['day']]['total_process_count'] = $result['total_process_count'];
            $history[$result['day']]['avg_pending_count'] = round($result['avg_pending_count']);
            $history[$result['day']]['idle_time'] = is_null($result['total_idle_time']) ? 0 : $result['total_idle_time'];
            $history[$result['day']]['exception_count'] = is_null($result['total_exception_count']) ? 0 : $result['total_exception_count'];
            return $history;
        }, []);

        // Fill the array gaps - because the chart wont populate correctly without 0 values
        for($i=14; $i>=0; $i--) {
            $this->queue[$queue->id]['day'][] = Carbon::now()->subDay($i)->format('d');

            if (isset($history[Carbon::now()->subDay($i)->format('Y-m-d')])) {
                $this->queue[$queue->id]['avg_wait_time'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['avg_wait_time'];
                $this->queue[$queue->id]['avg_process_time'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['avg_process_time'];
                $this->queue[$queue->id]['avg_pending_count'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['avg_pending_count'];
                $this->queue[$queue->id]['total_process_count'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['total_process_count'];
                $this->queue[$queue->id]['idle_time'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['idle_time'];
                $this->queue[$queue->id]['exception_count'][] = $history[Carbon::now()->subDay($i)->format('Y-m-d')]['exception_count'];
            } else {
                $this->queue[$queue->id]['avg_wait_time'][] = 0;
                $this->queue[$queue->id]['avg_process_time'][] = 0;
                $this->queue[$queue->id]['avg_pending_count'][] = 0;
                $this->queue[$queue->id]['total_process_count'][] = 0;
                $this->queue[$queue->id]['idle_time'][] = 0;
                $this->queue[$queue->id]['exception_count'][] = 0;
            }
        }

        return $this->queue[$queue->id];
    }

    /**
     * Calculate the average of the numbers, protected against divide-by-zero.
     *
     * @param  int  $total
     * @param  int  $count
     * @return int
     */
    protected function calculateAvg($total, $count)
    {
        if ($count < 1){
            return 0;
        }

        return round($total/$count, 2);
    }
}
