<?php

namespace App\Notifications;

use App\Models\ReportInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ReportInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The invitation instance.
     *
     * @var \App\Models\ReportInvitation
     */
    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\ReportInvitation  $invitation
     * @return void
     */
    public function __construct(ReportInvitation $invitation)
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
        $url = url(route('reports.shared', $this->invitation->token));
        $expiresAt = $this->invitation->expires_at->format('M d, Y H:i');
        $report = $this->invitation->report->title;
        $inviter = $this->invitation->inviter->name;

        return (new MailMessage)
            ->subject("You've been invited to view a report: {$report}")
            ->greeting('Hello!')
            ->line("{$inviter} has invited you to view a report: {$report}")
            ->line("This invitation will expire on {$expiresAt}.")
            ->action('View Report', $url)
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
            'report_invitation_id' => $this->invitation->id,
            'report_title' => $this->invitation->report->title,
            'inviter_name' => $this->invitation->inviter->name,
        ];
    }
}
