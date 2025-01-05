<?php


namespace App\Http\Controllers;


use App\CronJobLog;

class CronJobController extends Controller
{
    public function index(){
        $cronJobs = CronJobLog::orderBy('created_at', 'DESC')->where('is_archive', '=', '0')->paginate(10);

        return view('cron_jobs.index', compact('cronJobs'));
    }

    public function show($uuid){
        $cronJob = CronJobLog::where('cron_job_log_uuid', '=', $uuid)->where('is_archive', '=', '0')->firstOrFail();

        return view('cron_jobs.show', compact('cronJob'));
    }
}
