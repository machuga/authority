<?php

use Authority\Collection;

class DummyCollection extends Authority\Collection {}

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->items      = [1, 2, 3, 4, 5];
        $this->collection = new Collection;
    }

    public function tearDown()
    {
    }

    public function testCanAddElement()
    {
        $this->collection->push(1);
        $this->assertCount(1, $this->collection);
    }

    public function testCanIterateOverElements()
    {
        $this->collection = new Collection($this->items);
        $callCount = 0;

        foreach ($this->collection as $item) {
            $callCount++;
        }

        $this->assertEquals(5, $callCount);
    }

    public function testCanAccessElement()
    {
        $this->collection->push(1);

        $this->assertEquals(1, $this->collection[0]);
    }

    public function testCanDetermineIfEmpty()
    {
        $this->assertTrue($this->collection->isEmpty());
    }

    public function testCanDetermineIfNotEmpty()
    {
        $this->collection->push(1);

        $this->assertFalse($this->collection->isEmpty());
    }

    public function testCanDetermineCurrentElementCount()
    {
        $this->assertEquals(0, $this->collection->count());
    }

    public function testCanFilterToCollectionOverElements()
    {
        $collection = new Collection($this->items);

        $result = $collection->filter(function($item) { return $item === 5; });

        $this->assertCount(1, $result);
        $this->assertContains(5, $result);
    }

    public function testCanReduceOverElements()
    {
        $collection = new Collection($this->items);

        $result = $collection->reduce(function($total, $item) {
            return $total += $item;
        }, 0);

        $this->assertEquals(15, $result);
    }
}
