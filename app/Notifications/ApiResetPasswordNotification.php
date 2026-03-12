<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApiResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $url = "laravel")
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->url == "laravel" ?
            config('app.url')
            . "/reset-password/{$this->token}?email={$notifiable->email}"
                :
            config('app.frontend_url')
            . "/reset-password?token={$this->token}&email={$notifiable->email}";

        return (new MailMessage)
            ->subject('ลิงก์รีเซ็ตรหัสผ่าน '.config('app.name'))
            ->greeting("เรียนท่านผู้ใช้ที่เคารพ")
            ->line('อีเมลฉบับนี้ถูกส่งหาท่านเนื่องจากทางระบบได้รับคำขอรีเซ็ตรหัสผ่านสำหรับรหัสของท่าน')
            ->line("โปรดกดที่ปุ่มด้านล่างเพื่อรีเซ็ตรหัสผ่าน")
            ->action('รีเซ็ตรหัสผ่าน', $url)
            ->line('ลิงก์รีเซ็ตรหัสผ่านนี้จะหมดอายุใน 60 นาที')
            ->line('หากท่านไม่ได้ดำเนินการขอรีเซ็ตรหัสผ่าน โปรดอย่าดำเนินการใด ๆ กับลิงก์ดังกล่าว')
            ->line("ขอแสดงความนับถือ,")
            ->line(config('app.name'))
            ->salutation(" ");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
