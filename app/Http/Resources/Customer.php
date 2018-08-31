<?php

namespace knet\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try {
            return parent::toArray($request);
        } catch (Exception $error) {
            return $error->toArray();
        }
    }
}
