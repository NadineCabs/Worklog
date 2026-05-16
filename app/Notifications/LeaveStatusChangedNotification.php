<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(private Leave $leave)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $startDate = optional($this->leave->start_date)->format('M d, Y');
        $endDate = optional($this->leave->end_date)->format('M d, Y');
        $status = ucfirst($this->leave->status);

        return [
            'type' => 'leave_status_changed',
            'leave_id' => $this->leave->id,
            'status' => $this->leave->status,
            'message' => "Your leave request ({$this->leave->leave_type}) {$startDate} - {$endDate} was {$status}.",
            'url' => route('employee.request-leave'),
        ];
    }
}
