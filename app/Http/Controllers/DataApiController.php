<?php

namespace App\Http\Controllers;
use App\Models\User;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Models\CustomerContact;
use Filament\Notifications\Actions\Action;

class DataApiController extends Controller
{
    public function dataNotifications(){
        $now = Carbon::now();
        $tenSecondsAgo = $now->subSeconds(1);
        $recentPosts = CustomerContact::where('created_at', '>=', $tenSecondsAgo)->get();
        if ($recentPosts->isEmpty()) {
            $user = User::find(1);
            Notification::make()
                ->success()
                ->title("チャットから依頼")
                ->body('チェックリクエスト')
                ->actions([
                    Action::make('内容確認')
                        ->button()
                        ->markAsRead()
                        ->url(route('filament.admin.resources.customer-contacts.index')),
                ])
                ->sendToDatabase($user); 
    }
    return back();
}
}