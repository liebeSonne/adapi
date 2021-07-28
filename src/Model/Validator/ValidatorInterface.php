<?php

namespace App\Model\Validator;

/**
 * Интерфейс валидатора.
 *
 */
interface ValidatorInterface
{
    /**
     * Проверка на валидность значений.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Возврат всех ошибок при проверке валидации.
     * @return array
     */
    public function getErrors(): array;

    /**
     * Возврат первой ошибки при проверке валидации.
     * @return string
     */
    public function getFirstError(): string;
}
