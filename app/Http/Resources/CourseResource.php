<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'duration' => $this->duration,
            'description' => $this->description,
            'meta_description' => $this->meta_description,
            'excerpt' => Str::words(strip_tags($this->description), 30),
            'media' => MediaResource::make($this->image->first()),
        ];
    }
}
