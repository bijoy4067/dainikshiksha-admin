<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('New News')
                ->url('/control-panel/news/create'),

            Actions\DeleteAction::make(),
        ];
    }
}
