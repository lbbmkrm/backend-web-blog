<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BlogLiked extends Notification
{
    use Queueable;
    public $blog;
    public $liker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Blog $blog, User $liker)
    {
        $this->blog = $blog;
        $this->liker = $liker;
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
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'liker_name' => $this->liker->username,
            'message' => "{$this->liker->username} liked your blog: {$this->blog->title}",
            'created_at' => now()->toDateTimeString(),
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
            'id' => $this->blog->id,
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'liker_name' => $this->liker->username,
            'message' => "{$this->liker->username} liked your blog: {$this->blog->title}",
            'data' => $this->toArray($notifiable),
            'created_at' => now()->toDateTimeString(),
        ];
    }
    public function broadcastType(): string
    {
        return 'blog.liked';
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->blog->user_id),
        ];
    }
}
