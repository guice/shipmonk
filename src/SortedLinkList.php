<?php

declare(strict_types=1);

namespace GP\Shipmonk;

/**
 * @implements \Iterator<Node>
 */
class SortedLinkList implements \Iterator
{
    public function __construct(
        protected readonly ?Order $order = Order::ASCENDING,
        protected ?DataType $dataType = null,
        protected ?Node $head = null,
        protected ?Node $current = null,
    )
    {}

    public function insert(int|string $value, ?Node $next = null): void {

        $this->validateDataType($value);

        $node = new Node($value, $next);

        if ($this->head === null) {
            $this->head = $node;
            return;
        }

        $ascending = ($this->order === Order::ASCENDING);

        if (($ascending && $this->head->value > $value)
            || (!$ascending && $this->head->value < $value)) {
            $node->next = $this->head;
            $this->head = $node;

            return;
        }

        $current = $this->head;

        while ($current->next !== null &&
            (($ascending && $current->next->value < $value)
                || (!$ascending && $current->next->value > $value))) {
            $current = $current->next;
        }

        $node->next = $current->next;
        $current->next = $node;

    }

    #[\Override]
    public function current(): int|string|null
    {
        return $this->current?->value;
    }

    #[\Override]
    public function next(): void
    {
        $this->current = $this->current?->next;
    }

    #[\Override]
    public function key(): null
    {
        // Nothing to see here, move along
        return null;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->current !== null;
    }

    #[\Override]
    public function rewind(): void
    {
        $this->current = $this->head;
    }

    private function validateDataType(int|string $value) : void
    {
        if ($this->dataType === null) {
            $this->dataType = is_int($value) ? DataType::INT : DataType::STRING;
            return;
        }

        if ($this->dataType === DataType::INT && !is_int($value)) {
            throw new \InvalidArgumentException("Invalid data type: got type STRING, expected type INT");
        }
        elseif ($this->dataType === DataType::STRING && !is_string($value)) {
            throw new \InvalidArgumentException("Invalid data type: got type INT, expected type STRING");
        }
    }

    /**
     * @return array<int|string> $values
     */
    public function flatten(): array
    {
        $current = $this->head;

        $values = [];
        while ($current !== null) {
            $values[] = $current->value;
            $current = $current->next;
        }

        return $values;
    }
}