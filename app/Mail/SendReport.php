<?php

namespace knet\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $idDocListed;
    public $fileToAttach;
    public $url;
    public $urlInvito;
    public $nomeReport = 'Report kNet';
    public $urlTracking = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $fileToAttach, $nomeReport)
    {
        $this->user = $user;
        $this->nomeReport = $nomeReport;
        Log::info('Email file Attached: ' . $fileToAttach);
        $this->fileToAttach = $fileToAttach;
        $this->url = route("doc::list");
        $this->urlInvito = null;//route("user::resetPassword", $this->user->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Invio Report '.$this->nomeReport.' - ' . $this->user->name);

        $from = 'automatico@k-group.com';
        return $this->from($from, 'kNet - KronaKoblenz')
        ->subject($this->nomeReport.' - KronaKoblenz')
        ->markdown('_emails.reportsToSend')
        ->attach($this->fileToAttach);
    }

}
