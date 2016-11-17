<?php

namespace AppBundle\Reversi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use AppBundle\RequestHandler\FacebookRequestHandler;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Reversi\Model\Cell;
use Reversi\Model\Game;
use Reversi\Model\Board;
use Reversi\BoardAnalyzer;

class ReversiController
{

    private $templating;

    public function __construct(EngineInterface $templating)
    {
      $this->templating = $templating;
    }

    public function svgGameBoardAction(Game $game, Request $request)
    {

      $positions = [];

      if(($choiceCellType = $request->query->get('choices_for'))){
        $boardAnalyzer = new BoardAnalyzer($game->getBoard());
        $positions = $boardAnalyzer->getAvailableCellPositions(intval($choiceCellType));
      }

      $response = new Response();
      $response->headers->set('Content-Type', 'image/svg+xml');

      return $this->templating->renderResponse('AppBundle:Reversi:board.xml.twig', [
        'size'      => 400,
        'cells'     => $game->getBoard()->getCells(),
        'positions' => $positions
      ], $response);

    }

    public function pngGameBoardAction(Game $game, Request $request)
    {

      $positions = [];
      $board = $game->getBoard();

      if(($choiceCellType = $request->query->get('choices_for'))){
        $boardAnalyzer = new BoardAnalyzer($board);
        $positions = $boardAnalyzer->getAvailableCellPositions(intval($choiceCellType));
      }

      $img = $this->buildBoardImage($board, $positions);

      $response = new Response($img->encode('png'));
      $response->headers->set('Content-Type', 'image/png');

      return $response;

    }

    private function buildBoardImage(Board $board, $positions = [])
    {

      $size = 400;
      $imageManager = new ImageManager(array('driver' => 'gd'));
      $img = $imageManager->canvas($size, $size, '#008000');

      foreach($board->getCells() as $y => $row){
        $pad = floor($size/count($row));
        $img->line($pad*$y, 0, $pad*$y, $size, function($line){ $line->color('#333'); });
        foreach($row as $x => $cell){
          $img->line(0, $pad*$x, $size, $pad*$x, function($line){ $line->color('#333'); });
          if($cell !== Cell::TYPE_EMPTY){
            $img->circle($pad*0.65, $pad*($x+1)-($pad/2), $pad*($y+1)-($pad/2), function ($draw) use ($cell){
                $draw->background(($cell === Cell::TYPE_BLACK) ? '#000' : '#FFF');
            });
          } elseif (($key = array_search([$x, $y], $positions)) !== false){
            $img->text(strval($key), $pad*$x+($pad/3), $pad*($y+1)-($pad/5), function($font){
              $font->file(dirname(__FILE__) . '/../Resources/public/font/arial.ttf');
              $font->size(35);
            });
          }
        }
      }

      return $img;

    }

}
