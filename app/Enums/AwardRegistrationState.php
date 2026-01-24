<?php

namespace App\Enums;

enum AwardRegistrationState: string
{
    case SUBMITTED = 'ส่งคำขอแล้ว';

    // หัวหน้าภาควิชา
    case DEPT_HEAD_PENDING = 'รอหัวหน้าภาควิชาพิจารณา';
    case DEPT_HEAD_APPROVED = 'หัวหน้าภาควิชาเห็นชอบ';
    case DEPT_HEAD_REJECTED = 'หัวหน้าภาควิชาไม่เห็นชอบ';

    // รองคณบดี
    case ASSOC_DEAN_PENDING = 'รอรองคณบดีพิจารณา';
    case ASSOC_DEAN_APPROVED = 'รองคณบดีเห็นชอบ';
    case ASSOC_DEAN_REJECTED = 'รองคณบดีไม่เห็นชอบ';

    // คณบดี
    case DEAN_PENDING = 'รอคณบดีพิจารณา';
    case DEAN_APPROVED = 'คณบดีเห็นชอบ';
    case DEAN_REJECTED = 'คณบดีไม่เห็นชอบ';

    // กองพัฒนานิสิต
    case STUDENT_AFFAIRS_PENDING = 'รอกองพัฒนานิสิตตรวจสอบ';
    case STUDENT_AFFAIRS_VERIFIED = 'กองพัฒนานิสิตตรวจสอบแล้ว';
    case STUDENT_AFFAIRS_CATEGORY_CHANGED = 'กองพัฒนานิสิตแก้ไขประเภทรางวัล';

    // คณะกรรมการ
    case COMMITTEE_PENDING = 'รอคณะกรรมการพิจารณา';
    case COMMITTEE_APPROVED = 'คณะกรรมการเห็นชอบ';
    case COMMITTEE_REJECTED = 'คณะกรรมการไม่เห็นชอบ';

    // ประธานคณะกรรมการ
    case CHAIRMAN_PENDING = 'รอประธานคณะกรรมการลงนาม';
    case CHAIRMAN_SIGNED = 'ประธานคณะกรรมการลงนามแล้ว';

    // อธิการบดี
    case RECTOR_PENDING = 'รออธิการบดีลงนาม';
    case RECTOR_APPROVED = 'อธิการบดีลงนามประกาศแล้ว';

    // สิ้นสุดกระบวนการ
    case COMPLETED = 'เสร็จสิ้นกระบวนการ';
    case REJECTED = 'ไม่ผ่านการพิจารณา';
}
