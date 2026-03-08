<?php

namespace App\Enums;

enum UserRole: string
{
    case NISIT = 'NISIT';
    case DEPT_HEAD = 'DEPT_HEAD';
    case ASSO_DEAN = 'ASSO_DEAN';
    case DEAN = 'DEAN';
    case NISIT_DEV = 'NISIT_DEV';
    case BOARD = 'BOARD';
    case BOARD_HEAD = 'BOARD_HEAD';
    case CHANCELLOR = 'CHANCELLOR';

    public static function label(self $role): string
    {
        return match ($role) {
            self::NISIT => 'นิสิต',
            self::DEPT_HEAD => 'หัวหน้าภาค',
            self::ASSO_DEAN => 'รองคณบดี',
            self::DEAN => 'คณบดี',
            self::NISIT_DEV => 'กองพัฒนานิสิต',
            self::BOARD => 'กรรมการ',
            self::BOARD_HEAD => 'ประธานกรรมการ',
            self::CHANCELLOR => 'อธิการบดี',
        };
    }

    public function level(): RoleLevel
    {
        return match ($this) {
            self::NISIT => RoleLevel::NISIT,
            self::DEPT_HEAD => RoleLevel::DEPT_HEAD,
            self::ASSO_DEAN => RoleLevel::ASSO_DEAN,
            self::DEAN => RoleLevel::DEAN,
            self::NISIT_DEV => RoleLevel::NISIT_DEV,
            self::BOARD => RoleLevel::BOARD,
            self::BOARD_HEAD => RoleLevel::BOARD_HEAD,
            self::CHANCELLOR => RoleLevel::CHANCELLOR,
        };
    }
}
