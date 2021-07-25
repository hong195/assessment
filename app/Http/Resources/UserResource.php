<?php

namespace App\Http\Resources;

use App\Domain\Model\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => (string) $this->getId(),
            'first_name' =>  $this->getFullName()->firstName(),
            'last_name' =>  $this->getFullName()->lastName(),
            'middle_name' =>  $this->getFullName()->patronymic(),
            'login' => (string) $this->getLogin(),
            'role' => (string) $this->getRole()
        ];
    }
}
