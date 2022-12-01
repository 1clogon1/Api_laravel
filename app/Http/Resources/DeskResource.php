<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)//Это так называемая доска для красиво внешнего вывода данных и указания какие данные нам нужно выводить
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            //'lists' =>$this->lists,//За счет данного пункта мы выведем связанную с данной таблице(один ко многим) другую таблицу, ниже через lists
            'lists' =>DeskListResource::collection($this->lists),//DeskListResource::collection($this->lists) - для того чтобы передать какие данные мы выводим для дополнительной(связанной с нашей через связь один ко многим) таблице
        ];
    }
}
