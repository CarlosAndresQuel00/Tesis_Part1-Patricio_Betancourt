<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\Notifiable;

class User extends JsonResource
{
    use Notifiable;

    protected $token;

    public function __construct($resource, $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'identity' => $this->identity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'token' => $this->when($this->token, $this->token),
        ];
    }
}
