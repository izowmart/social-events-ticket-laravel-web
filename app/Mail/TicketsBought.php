<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketsBought extends Mailable
{
    use Queueable, SerializesModels;

    private $data;


    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->view('tickets.emails.bought_tickets')
            ->from('info@fikaplaces.com',"FIKA Places")
            ->subject('Your Tickets')
            ->with($this->data)
        ;

        foreach ($this->data['files'] as $index => $file) {
            $message->attach($file,
                [
                    'as' => 'Ticket'.($index+1).'.pdf',
                    'mime' => 'application/pdf',
                ]); // attach each file
        }

        return $message; //Send mail
    }
}
