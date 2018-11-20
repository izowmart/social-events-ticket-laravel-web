<?php

namespace App\Transformers;

use App\Ticket;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Ticket $ticket
     *
     * @return array
     */
    public function transform(Ticket $ticket)
    {


        return [
            'id'                    => $ticket->id,
            'event_id'              => $ticket->event->id,
            'event_name'            => $ticket->event->name,
            'event_dates'           => '', //FIXME:: what/how do we capture here/this??
            'qr_code_image_url'     => $ticket->qr_code_image_url,
            'ticket_type'           => $ticket->category->name,
            'scan_status'           => $ticket->scanned(),
            'ticket_number'         => $ticket->unique_ID, //FIXME:: pending testing
        ];
    }
}
