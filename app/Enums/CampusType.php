<?php

namespace App\Enums;

enum CampusType: string
{
  case BANGKHEN = 'BANGKHEN';
  case KAMPHANGSAEN = 'KAMPHANGSAEN';
  case SRIRACHA = 'SRIRACHA';
  case SAKONNAKHON = 'SAKONNAKHON';
  case SUPHANBURI = 'SUPHANBURI';

  public static function label(self $type): string
  {
    return match ($type) {
      self::BANGKHEN => 'บางเขน',
      self::KAMPHANGSAEN => 'กำแพงแสน',
      self::SRIRACHA => 'ศรีราชา',
      self::SAKONNAKHON => 'เฉลิมพระเกียรติ จังหวัดสกลนคร',
      self::SUPHANBURI => 'สุพรรณบุรี',
    };
  }
}
