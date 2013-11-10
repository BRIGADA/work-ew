<?php

/**
 * chat actions.
 *
 * @package    edgeworld
 * @subpackage chat
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chatActions extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex() {
    $this->messages = Doctrine::getTable('Chat')
            ->createQuery()
            ->limit(10)
            ->orderBy('id DESC')
            ->fetchArray();
  }

  public function executeNew(sfWebRequest $request) {
    $id = $request->getParameter('id');
    $this->forward404Unless($id);
    
    $messages = Doctrine::getTable('Chat')
            ->createQuery()
            ->orderBy('id DESC')
            ->where('id > ?', $id)
            ->fetchArray();

    if (!count($messages)) {
      return sfView::HEADER_ONLY;
    }

    return $this->renderPartial('messages', ['messages' => $messages]);
  }

  public function executeOld($request) {
    $id = $request->getParameter('id');
    $this->forward404Unless($id);

    $messages = Doctrine::getTable('Chat')
            ->createQuery()
            ->orderBy('id DESC')
            ->where('id < ?', $id)
            ->limit(100)
            ->fetchArray();

    return $this->renderPartial('messages', ['messages' => $messages]);
  }

}
