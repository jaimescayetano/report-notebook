<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Tag;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create tags if not exists
        $tagIds = [];

        if (isset($data['tags'])) {
            foreach($data['tags'] as $tag) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                $tagIds[] = $tag->id;
            }
        }
        unset($data['tags']);
        $data['user_id'] = auth()->id();
        $task = static::getModel()::create($data);
        $task->tags()->syncWithoutDetaching($tagIds);
        return $task;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
