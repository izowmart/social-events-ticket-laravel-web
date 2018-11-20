<?php

namespace App\Transformers;

use App\EventTicketCategory;
use App\Http\Traits\UniversalMethods;
use App\TicketCategory;
use App\TicketCategoryDetail;
use League\Fractal\TransformerAbstract;

class TicketCategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\TicketCategoryDetail $ticketCategoryDetail
     *
     * @return array
     */
    public function transform(TicketCategoryDetail $ticketCategoryDetail)
    {
        return [
            'id'                    => $ticketCategoryDetail->id,
            'event_id'              => $ticketCategoryDetail->event_id,
            'category_name'         => $ticketCategoryDetail->category->name,
            'remaining_tickets'     => UniversalMethods::getRemainingCategoryTickets($this->event_id,$ticketCategoryeventTicketCategory->id),//remaining = available - sold
            'price'                  => (int) $ticketCategoryDetail->price
        ];
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id): void
    {
        $this->event_id = $event_id;
    }
}
