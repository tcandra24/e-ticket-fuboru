<?php

namespace App\Http\Controllers\Admin\Testing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class SandboxController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = QrCode::size(512)
            // ->format('png')
            ->merge('/public/storage/images/logo/logo.png')
            ->errorCorrection('Q')
            ->generate(
                'https://twitter.com/HarryKir',
            );

        return response($data)
            ->header('Content-type', 'image/svg+xml');
    }
}
