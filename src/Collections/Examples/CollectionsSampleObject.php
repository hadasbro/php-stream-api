<?php

namespace Collections\Examples;

use Serializable;

class CollectionsSampleObject implements Serializable
{
    private int $id;
    private int $type = 1;

    /**
     * __construct
     *
     * CollectionsSampleObject constructor.
     * @param $lineId
     * @param $type
     */
    public function __construct($lineId, $type = 1){
        $this->id = $lineId;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return json_encode([
            'id' => $this->id,
            'type' => $this->type
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $s = json_decode($serialized, true);
        new self($s['id'], $s['type']);
    }
}