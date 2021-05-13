<?php

namespace Siganushka\RBACBundle\Sorter;

// $paths = [
//     '/users/add',
//     '/users/delete/{id}',
//     '/',
//     '/goods/{id}/orders/{orderId}',
//     '/users',
//     '/users/edit/{id}',
//     '/goods/{id}/delete',
//     '/goods/{id}/orders/{orderId}/items/{itemId}',
//     '/goods/{id}/orders/{orderId}/shipping',
//     '/users/{id}/orders/{orderId}',
//     '/users/{id}/carts/{goodsId}/delete',
// ];
//
// $sorter = new PathSorter();
// $sorter->sort($paths);
//
// dd($paths);

class PathSorter
{
    public function sort(array &$paths): void
    {
        usort($paths, [$this, 'compare']);
    }

    public function compare(string $a, string $b): int
    {
        return $this->compareArray(
            array_filter( explode('/', $a) ),
            array_filter( explode('/', $b) )
        );
    }

    private function compareString(string $a, string $b): int
    {
        return $a <=> $b;
    }

    private function compareArray(array $a, array $b): int
    {
        $aLength = count($a);
        $bLength = count($b);
        if ($aLength === 0 || $bLength === 0) {
            return $aLength <=> $bLength;
        }

        $aValue = array_shift($a);
        $bValue = array_shift($b);

        if ($this->compareString($aValue, $bValue) === 0) {
            return $this->compareArray($a, $b);
        }

        return $aValue <=> $bValue;
    }
}
