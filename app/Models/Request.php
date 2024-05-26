<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'send_by',
        'status'
    ];

    /**
     * 
     * Send request to user
     * @param User $user user to send request
     * @return array     array with object request and creation message
     * 
     */
    public function sendRequest(User $user): array
    {
        try {
            $request = $this->create([
                'user_id' => $user->id,
                'send_by' => auth()->id()
            ]);
            $message = $request instanceof Request ? 'Friend request sent to ' . $user->name : 'Failed to send friend request to ' . $user->name;
            return ['request' => $request, 'message' => $message];
        } catch (\Exception $e) {
            return ['request' => null, 'message' => $e->getMessage()];
        }
    }

    /**
     * 
     * Validate if you can send a friend request to a user. 
     * The response will be false if you have a pending request or if they are already your friend
     * @param User $user user to whom the request isi to be sent
     * @return bool      true if you can send the request, false if you can't
     * 
     */
    public function canSendRequest(User $user): bool
    {
        return !$this->existsRequest($user) && !$this->isFriend($user);
    }

    /**
     * 
     * Validate if there is a request between two users
     * @param User $user user to whom the request isi to be sent
     * @return boolean   true if there is a request, false if there isn't
     * 
     */
    public function existsRequest(User $user): bool
    {
        $sentRequest = Request::where('user_id', $user->id)->where('send_by', auth()->id())->count() > 0;
        $sentMeARequest = Request::where('user_id', auth()->id())->where('send_by', $user->id)->count() > 0;
        return $sentRequest || $sentMeARequest;
    }

    /**
     * 
     * Validate if two users are friends
     * @param User $user user to whom the request isi to be sent
     * @return boolean   true if they are friends, false if they aren't
     * 
     */
    public function isFriend(User $user): bool
    {
        $isMyFriend = Friend::where('user_id', $user->id)->where('friend_id', auth()->id())->count() > 0;
        $amIYourFriend = Friend::where('user_id', auth()->id())->where('friend_id', $user->id)->count() > 0;
        return $isMyFriend || $amIYourFriend;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'send_by');
    }
}
