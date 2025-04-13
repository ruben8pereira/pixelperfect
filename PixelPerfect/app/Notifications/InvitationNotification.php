<?php

namespace App\Notifications;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The invitation instance.
     *
     * @var \App\Models\UserInvitation
     */
    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\UserInvitation  $invitation
     * @return void
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('invitations.accept', $this->invitation->token));
        $expiresAt = $this->invitation->expires_at->format('M d, Y H:i');
        $organization = $this->invitation->organization->name;
        $inviter = $this->invitation->inviter->name;
        $role = $this->invitation->role->name;

        return (new MailMessage)
            ->subject('Invitation to Join ' . $organization)
            ->greeting('Hello!')
            ->line('You have been invited by ' . $inviter . ' to join ' . $organization . ' as a ' . $role . '.')
            ->line('This invitation will expire on ' . $expiresAt . '.')
            ->action('Accept Invitation', $url)
            ->line(new HtmlString('If you did not expect this invitation, you can safely ignore this email.'))
            ->salutation('Regards, <br>The PixelPerfect Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'invitation_id' => $this->invitation->id,
            'organization_name' => $this->invitation->organization->name,
            'inviter_name' => $this->invitation->inviter->name,
            'role_name' => $this->invitation->role->name,
        ];
    }
}
