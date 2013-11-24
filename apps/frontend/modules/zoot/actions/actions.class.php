<?php

/**
 * zoot actions.
 *
 * @package    edgeworld
 * @subpackage zoot
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class zootActions extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request) {
    $r = $this->getUser()->RGET('/api/player');
    $this->forwardUnless($r, 'common', 'index');

    $d = json_decode($r, true);

    $this->tokens = 0;
    foreach ($d['response']['items'] as $item) {
      if ($item['type'] == 'LotteryToken') {
        $this->tokens = $item['quantity'];
        break;
      }
    }
  }

//    POST /api/lottery
//    _session_id=null
//    meltdown=45733806cd75e877ae48cdc642b7cbbbd8d8a044
//    reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
//    testCount=12
//    user_id=608208

  public function executeLottery() {
    $r = $this->getUser()->RPOST('/api/lottery');
    $this->forward404Unless($r);

    $d = json_decode($r, true);

    $result = array();

    if ($d['response']['success']) {
      $result['success'] = true;
      $result['prize'] = $this->getPartial('prize', array('type' => $d['response']['prize']));
      $result['tokens'] = 0;
      foreach ($d['response']['items'] as $item) {
        if ($item['type'] == 'LotteryToken') {
          $result['tokens'] = $item['quantity'];
          break;
        }
      }
    } else {
      $result['success'] = false;
      $result['error'] = implode("\n", $d['response']['errors']);
    }

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($result));
  }

}
