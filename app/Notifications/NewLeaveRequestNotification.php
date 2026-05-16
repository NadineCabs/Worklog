<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification
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
        $employeeName = $this->leave->employee?->name ?? 'Employee';
        $startDate = optional($this->leave->start_date)->format('M d, Y');
        $endDate = optional($this->leave->end_date)->format('M d, Y');

        return [
            'type' => 'leave_submitted',
            'leave_id' => $this->leave->id,
            'status' => $this->leave->status,
            'employee_name' => $employeeName,
            'message' => "New leave request from {$employeeName} ({$this->leave->leave_type}) {$startDate} - {$endDate}.",
            'url' => route('leave.index'),
        ];
    }
}
