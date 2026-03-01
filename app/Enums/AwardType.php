<?php

namespace App\Enums;

enum AwardType: string
{
  case EXTRACURRICULAR = 'EXTRACURRICULAR';
  case INNOVATION = 'INNOVATION';
  case CONDUCT = 'CONDUCT';

  public static function label(self $type): string
  {
    return match ($type) {
      self::EXTRACURRICULAR => 'ด้านกิจกรรมเสริมหลักสูตร',
      self::INNOVATION => 'ด้านความคิดสร้างสรรค์และนวัตกรรม',
      self::CONDUCT => 'ด้านความประพฤติดี',
    };
  }
}
