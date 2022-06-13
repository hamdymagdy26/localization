<?php

namespace App\Http\Resources;

use App\Http\Resources\Abstracts\AbstractJsonResource;
use Illuminate\Http\Request;

class ProjectResource extends AbstractJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function modelResponse(Request $request): array
    {
        $translation = $this->translation()->where('locale', $request->header('localization'))->first();
        return [
            'id' => $this->id,
            'logo' => $this->logo,
            'name' => isset($translation->name) ? $translation->name : null,
            'translation' => ProjectTranslationResource::collection($this->translations),
        ];
    }
}
