<?php

namespace API\Users;

enum Role: string {
  case ADMIN = 'admin';
  case USER = 'user';
}
