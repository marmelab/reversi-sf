<?php

namespace AppBundle\Reversi;

use GuzzleHttp\ClientInterface;
use Reversi\Model\Game;
use Reversi\Model\Cell;

class GameAi
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBestCellChange(Game $game)
    {
        $cellType = $game->getCurrentPlayer()->getCellType();
        $boardCells = $game->getBoard()->getCells();

        $response = $this->client->get('/?type=' . $cellType, ['json' => $boardCells]);
        $cellObject = json_decode($response->getBody());

        return new Cell($cellObject->X, $cellObject->Y, $cellObject->CellType);
    }
}
