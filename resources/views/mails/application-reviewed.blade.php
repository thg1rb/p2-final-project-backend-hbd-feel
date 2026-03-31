<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body
    style="background-color: #f3f4f6; font-family: 'Sarabun', 'Helvetica Neue', Arial, sans-serif; padding: 20px 0; margin: 0;">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
       style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">

    <tr>
        <td style="background-color: #22c55e; background-image: linear-gradient(to bottom right, #22c55e, #059669); padding: 30px 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">NisitDeeden</h1>
            <p style="color: #c1e1e0; margin: 8px 0 0 0; font-size: 14px;">ระบบนิสิตดีเด่น</p>
        </td>
    </tr>

    <tr>
        <td style="padding: 30px 40px; color: #374151; line-height: 1.6;">
            <p style="margin-top: 0;">เรียน {{ $application->user->firstName }} {{ $application->user->lastName }},</p>
            <p>{{ \App\Enums\UserRole::label($approver->role) }}ได้พิจารณาใบสมัครของคุณเรียบร้อย โดยมีรายละเอียดดังต่อไปนี้:</p>

            <table border="0" cellpadding="15" cellspacing="0" width="100%"
                   style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; margin: 25px 0;">

                <tr>
                    <td width="30%" style="font-weight: bold; color: #4b5563; border-bottom: 1px solid #e5e7eb;">
                        สถานะ:
                    </td>
                    <td style="border-bottom: 1px solid #e5e7eb;">
                        @if(in_array($status, ['APPROVED', 'Approved', 'approved']))
                            <span
                                style="background-color: #def7ec; color: #03543f; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">✅ อนุมัติผ่านการพิจารณา</span>
                        @else
                            <span
                                style="background-color: #fde8e8; color: #9b1c1c; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">❌ ไม่ผ่านการพิจารณา</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; color: #4b5563; border-bottom: 1px solid #e5e7eb;">พิจารณาโดย:</td>
                    <td style="border-bottom: 1px solid #e5e7eb;">
                        {{ $approver->firstName }} {{ $approver->lastName }}
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; color: #4b5563;">เหตุผล/ข้อเสนอแนะ:</td>
                    <td style="font-style: italic; color: #6b7280;">
                        {{ $reason ?: 'ไม่มีข้อเสนอแนะเพิ่มเติม' }}
                    </td>
                </tr>
            </table>

            <p style="margin-bottom: 0;">หากมีข้อสงสัยเพิ่มเติม สามารถติดต่อสอบถามได้ที่หน่วยงานที่รับผิดชอบ</p>

            <div style="margin-top: 30px;">
                <p style="margin: 0; font-weight: bold;">ขอแสดงความนับถือ,</p>
                <p style="margin: 4px 0 0 0;">ทีมงานระบบนิสิตดีเด่น</p>
            </div>
        </td>
    </tr>

    <tr>
        <td style="background-color: #f3f4f6; padding: 20px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb;">
            อีเมลฉบับนี้เป็นการแจ้งเตือนอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
        </td>
    </tr>
</table>

</body>
</html>
