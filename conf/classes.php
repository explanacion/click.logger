<?php

/*
 * Вспомогательные классы
 */

namespace Classes;

/**
 * Данные о хите
 */
class ClickRecord {
    public $ip;
    public $name;
    public $timestamp;
    
    public function isValid()
    {
        // корректное название точки
        if (filter_var($this->name,FILTER_SANITIZE_SPECIAL_CHARS))
        {
            // корректное время в формате UNIX time
            if (is_numeric($this->timestamp))
            {
                // корректный ip-адрес
                if (filter_var($this->ip,FILTER_VALIDATE_IP))
                {
                    return true;
                }
            }
        }
        return false;
    }
}

