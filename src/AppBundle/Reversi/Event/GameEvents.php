<?php

namespace AppBundle\Reversi\Event;

class GameEvents
{

  const ACTION_WELCOME = 'game.welcome';
  const ACTION_ASK_FOR_POSITION = 'game.ask_position';
  const ACTION_FINISH = 'game.finish';
  const ACTION_SHOW_BOARD = 'game.show_board';
  const ACTION_NOT_YOUR_TURN = 'game.not_your_turn';
  const ACTION_EXPLAIN_START = 'game.explain_how_to_play';
  const ACTION_INVALID_INPUT = 'game.invalid_input';

  const NOTIFICATION = 'game.notification';

}
