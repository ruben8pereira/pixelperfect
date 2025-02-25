<?php

namespace App\Filament\Resources\DefectTypeResource\Pages;

use App\Filament\Resources\DefectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefectType extends EditRecord
{
    protected static string $resource = DefectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
