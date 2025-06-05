<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserFollowed extends Notification
{
    use Queueable;
    public $follower;
    public $followedUser;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $follower, User $followedUser)
    {
        $this->follower = $follower;
        $this->followedUser = $followedUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'follower_id' => $this->follower->id,
            'followed_user_id' => $this->followedUser->id,
            'message' => "{$this->follower->name} mengikuti Anda.",
            'created_at' => now()->toDateTimeString()
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'follower_id' => $this->follower->id,
            'followed_user_id' => $this->followedUser->id,
            'message' => "{$this->follower->name} mengikuti Anda.",
            'created_at' => now()->toDateTimeString(),
        ];
    }
    public function broadcastType(): string
    {
        return 'user.followed';
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->followedUser->id)
        ];
    }
}
