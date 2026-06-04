<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Gemini)]
#[Model('gemini-3.1-flash-lite')]
class HospitalComplaintAgent implements Agent
{
    use Promptable;

    public function instructions(): \Stringable|string
    {
        return 'Anda adalah petugas penanganan pengaduan rumah sakit yang profesional, empati, dan solutif.';
    }
}