<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Moji Kuponi';
    protected static ?string $navigationGroup = 'Marketing';

    // Prodavači ne mogu kreirati kupone kroz formu
    public static function form(Form $form): Form
    {
        return $form->schema([]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod Kupona')
                    ->searchable()
                    ->copyable() // Omogućava prodavaču da jednim klikom kopira kod
                    ->copyMessage('Kod kopiran!')
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Popust')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percent' ? $state . '%' : $state . ' BAM')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Iskorišteno')
                    ->description(fn ($record) => "Limit: " . ($record->usage_limit ?? '∞'))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Važi do')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->placeholder('Nema roka'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivni kuponi'),
            ])
            ->actions([
                // Samo View akcija jer seller ne smije Edit/Delete
                Tables\Actions\ViewAction::make()->label('Detalji'),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        // KLJUČNO: Prodavač vidi SAMO kupone koji su dodijeljeni njegovom user_id-u
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
        ];
    }
    
    // Onemogućavamo dugme "Create" na listi
    public static function canCreate(): bool
    {
        return false;
    }
}