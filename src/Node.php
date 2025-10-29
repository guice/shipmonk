<?php

namespace  GP\Shipmonk;
class Node {

    public function __construct(
        public int|string $value {
            get {
                return $this->value;
            }
        },
        public ?Node         $next = null {
            get {
                return $this->next;
            }
            set(?Node $node) {
                $this->next = $node;
            }
        },
        public ?Node         $previous = null {
            get {
                return $this->previous;
            }
            set(?Node $node) {
                $this->previous = $node;
            }
        },
    ) {}

}