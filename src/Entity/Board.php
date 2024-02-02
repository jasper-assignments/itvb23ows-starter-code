<?php

namespace App\Entity;

class Board
{
    public const array OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    private array $tiles;

    public function __construct(array $tiles = [])
    {
        $this->tiles = $tiles;
    }

    public function getTiles(): array
    {
        return $this->tiles;
    }

    public function isPositionEmpty(string $pos): bool
    {
        return !isset($this->tiles[$pos]);
    }

    public function setPosition(string $pos, int $player, string $piece): void
    {
        $this->tiles[$pos] = [[$player, $piece]];
    }

    public function getAllOccupiedPositions(): array
    {
        return array_keys($this->tiles);
    }

    public function getAllPositionsOwnedByPlayer(int $player): array
    {
        return array_filter($this->getAllOccupiedPositions(), fn($pos) => $this->isTileOwnedByPlayer($pos, $player));
    }

    public function getCurrentTileOnPosition(string $pos): array
    {
        return $this->tiles[$pos][count($this->tiles[$pos])-1];
    }

    public function isTileOwnedByPlayer(string $pos, int $player): bool
    {
        return $this->tiles[$pos][count($this->tiles[$pos])-1][0] == $player;
    }

    public function popTile(string $pos): array
    {
        $tile = array_pop($this->tiles[$pos]);
        if (!count($this->tiles[$pos])) {
            unset($this->tiles[$pos]);
        }
        return $tile;
    }

    public function pushTile(string $pos, array $tile): void
    {
        if ($this->isPositionEmpty($pos)) {
            $this->tiles[$pos] = [];
        }
        array_push($this->tiles[$pos], $tile);
    }

    private function isNeighbour(string $a, string $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if (
            ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) ||
            ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) ||
            ($a[0] + $a[1] == $b[0] + $b[1])
        ) {
            return true;
        }
        return false;
    }

    public function hasNeighbour(string $a): bool
    {
        foreach (array_keys($this->tiles) as $b) {
            if ($this->isNeighbour($a, $b)) {
                return true;
            }
        }
        return false;
    }

    public function neighboursAreSameColor(int $player, string $a): bool
    {
        foreach ($this->tiles as $b => $st) {
            if (!$st) {
                continue;
            }
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($a, $b)) {
                return false;
            }
        }
        return true;
    }

    private function len(?array $tile): int
    {
        return $tile ? count($tile) : 0;
    }

    public function slide(string $from, string $to): bool
    {
        if (!$this->hasNeighbour($to) || !$this->isNeighbour($from, $to)) {
            return false;
        }

        // Find the common neighbours between the to position and from position
        $common = array_filter(
            $this->getNeighbourPositions($to, fn($neighbour) => !$this->isPositionEmpty($neighbour)),
            fn($pos) => $this->isNeighbour($from, $pos)
        );

        if (
            !isset($this->tiles[$common[0]]) &&
            !isset($this->tiles[$common[1]]) &&
            !isset($this->tiles[$from]) &&
            !isset($this->tiles[$to])
        ) {
            return false;
        }
        return min(
                $this->len($this->tiles[$common[0]] ?? []),
                $this->len($this->tiles[$common[1]] ?? [])
            ) <= max(
                $this->len($this->tiles[$from] ?? []),
                $this->len($this->tiles[$to] ?? [])
            );
    }

    public function getNeighbourPositions(string $pos, callable $filterFunction = null): array
    {
        $b = explode(',', $pos);
        $neighbours = [];
        foreach (self::OFFSETS as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            $neighbour = "$p,$q";
            if (!isset($filterFunction) || $filterFunction($neighbour)) {
                $neighbours[] = $neighbour;
            }
        }
        return $neighbours;
    }

    public function willMoveSplitHive(string $from, string $to): bool
    {
        $board = clone $this;
        $board->popTile($from);
        if (!$board->hasNeighbour($to)) {
            return true;
        } else {
            $all = $board->getAllOccupiedPositions();
            $queue = [array_shift($all)];
            while ($queue) {
                $next = explode(',', array_shift($queue));
                foreach (Board::OFFSETS as $pq) {
                    list($p, $q) = $pq;
                    $p += $next[0];
                    $q += $next[1];
                    if (in_array("$p,$q", $all)) {
                        $queue[] = "$p,$q";
                        $all = array_diff($all, ["$p,$q"]);
                    }
                }
            }
            if ($all) {
                return true;
            }
        }
        return false;
    }

    public function getPositionOfTile(int $player, string $piece): ?string
    {
        $positions = $this->getAllOccupiedPositions();
        foreach ($positions as $pos) {
            $tilesOnPosition = $this->tiles[$pos];
            foreach ($tilesOnPosition as $tile) {
                if ($tile[0] == $player && $tile[1] == $piece) {
                    return $pos;
                }
            }
        }
        return null;
    }

    public function isPositionSurrounded(string $pos): bool
    {
        foreach ($this->getNeighbourPositions($pos) as $neighbour) {
            if ($this->isPositionEmpty($neighbour)) {
                return false;
            }
        }
        return true;
    }
}
