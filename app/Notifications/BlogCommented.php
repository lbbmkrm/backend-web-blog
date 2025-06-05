<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class BlogCommented extends Notification
{
    public $comment;
    public $blog;
    public $commenter;
    public function __construct(
        Comment $comment,
        Blog $blog,
        User $commenter
    ) {
        $this->comment = $comment;
        $this->blog = $blog;
        $this->commenter = $commenter;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'commenter_name' => $this->commenter->username,
            'message' => "{$this->commenter->username} commented on your blog: {$this->blog->title}",
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->comment->id,
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
            'commenter_name' => $this->commenter->username,
            'message' => "{$this->commenter->username} commented on your blog: {$this->blog->title}",
            'data' => $this->toDatabase($notifiable),
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    public function broadcastType(): string
    {
        return 'blog.commented';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->blog->user_id)
        ];
    }
}
