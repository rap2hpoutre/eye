<?php

namespace Eyewitness\Eye\Http\Controllers;

use Eyewitness\Eye\Eye;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\History\Dns;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DnsController extends Controller
{
    use ValidatesRequests;

    /**
     * Show the DNS history.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required|string'
        ]);

        $dns = DNS::where('meta', $request->domain)->orderBy('created_at', 'desc')->get();

        if (! count($dns)) {
            return redirect(route('eyewitness.dashboard').'#dns')->withError('Sorry - we could not find any DNS history for that domain. If you have just added the record, you should wait for the first test to run (usaully within an hour).');
        }

        return view('eyewitness::dns.show')->withEye(app(Eye::class))
                                           ->withDomain($request->domain)
                                           ->withDns($dns);
    }
}
