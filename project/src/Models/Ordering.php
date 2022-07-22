<?php

namespace App\Models;

class Ordering
{
    public const ORDERING_TYPES = [
        'ASC',
        'DESC',
    ];

    private string $orderedField;

    private string $orderType = 'ASC';

    public function __construct(string $orderedField, string $orderType)
    {
        $this->orderedField = $orderedField;
        $this->orderType = $orderType;
    }

    /**
     * @return string
     */
    public function getOrderedField(): string
    {
        return $this->orderedField;
    }

    /**
     * @param string $orderedField
     */
    public function setOrderedField(string $orderedField): void
    {
        $this->orderedField = $orderedField;
    }

    /**
     * @return string
     */
    public function getOrderType(): string
    {
        return in_array($this->orderType, self::ORDERING_TYPES) ? $this->orderType : 'ASC';
    }

    /**
     * @param string $orderType
     */
    public function setOrderType(string $orderType): void
    {
        $this->orderType = $orderType;
    }
}