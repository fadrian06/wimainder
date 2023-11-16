<?php

namespace App\Lang;

final class Spanish {
  static function validations(): array {
    return [
      'required' => '{Field} es requerido',
      'email' => '{Field} must be a valid email address',
      'alpha' => '{Field} must contain only alphabets and spaces',
      'text' => '{Field} must contain only alphabets and spaces',
      'textonly' => '{Field} must contain only alphabets',
      'alphanum' => '{Field} must contain only alphabets and numbers',
      'alphadash' => '{Field} must contain only alphabets, numbers, dashes and underscores',
      'username' => '{Field} must contain only alphabets, numbers and underscores',
      'number' => '{Field} must contain only numbers',
      'float' => '{Field} must contain only numbers',
      'date' => '{Field} must be a valid date',
      'min' => '{Field} debe ser de al menos %s caracteres',
      'max' => '{Field} must not exceed %s characters',
      'between' => '{Field} must be between %s and %s characters long',
      'match' => '{Field} must match the %s field',
      'contains' => '{Field} must contain %s',
      'boolean' => '{Field} must be a boolean',
      'in' => '{Field} must be one of the following: %s',
      'notin' => '{Field} must not be one of the following: %s',
      'ip' => '{Field} must be a valid IP address',
      'ipv4' => '{Field} must be a valid IPv4 address',
      'ipv6' => '{Field} must be a valid IPv6 address',
      'url' => '{Field} must be a valid URL',
      'domain' => '{Field} must be a valid domain',
      'creditcard' => '{Field} must be a valid credit card number',
      'phone' => '{Field} must be a valid phone number',
      'uuid' => '{Field} must be a valid UUID',
      'slug' => '{Field} must be a valid slug',
      'json' => '{Field} must be a valid JSON string',
      'regex' => '{Field} must match the pattern %s',
      'array' => '{field} must be an array',
    ];
  }
}
