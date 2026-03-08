<?php

namespace App\Enums;

enum RoleLevel: int
{
  case NISIT = 0;
  case DEPT_HEAD = 1;
  case ASSO_DEAN = 2;
  case DEAN = 3;
  case NISIT_DEV = 4;
  case BOARD = 5;
  case CHANCELLOR = -1;
}
