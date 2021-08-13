<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return   [
            'user_id' => $this->user_id,
            'blog_subject' => $this->blog_subject,
            'blog_content' => $this->blog_content
        ];
    }
}
