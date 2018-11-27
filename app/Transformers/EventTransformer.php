<?php

namespace App\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    protected $ticket_customer_id;

    /**
     * A Fractal transformer.
     *
     * @param \App\Event $event
     *
     * @return array
     */
    public function transform(Event $event)
    {
        $ticket_category_transformer = new TicketCategoryTransformer();
        $ticket_category_transformer->setEventId($event->id);
        return [
                'id'                    => $event->id,
                'name'                  => $event->name,
                'description'           => $event->description,
                'location'              =>$event->location,
                'type'                  => $event->type,
                'image_url'             => $event->media_url,
                'dates'                 => fractal($event->event_dates,EventDateTransformer::class)->withResourceName('dates'),
                'ticket_categories'     => fractal($event->ticket_category_details, $ticket_category_transformer)->withResourceName('ticket_categories'),
                'tickets'               => fractal($event->user_tickets($this->ticket_customer_id)->get(),TicketTransformer::class)->withResourceName('tickets')
            ];
    }

    /**
     * @param mixed $ticket_customer_id
     */
    public function setTicketCustomerId($ticket_customer_id): void
    {
        $this->ticket_customer_id = $ticket_customer_id;
    }

}
