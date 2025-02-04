<?php

namespace RuangDeveloper\LaravelSettings\Enums;

enum Type: string
{
    case String = 'string';
    case Integer = 'integer';
    case Float = 'float';
    case Boolean = 'boolean';
    case Array = 'array';
    case Object = 'object';
}