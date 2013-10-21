<?php

/**
 * message actions.
 *
 * @package    edgeworld
 * @subpackage message
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messageActions extends sfActions
{

    /**
     * Executes index action
     *
     * @param sfRequest $request
     *            A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $r = $this->getUser()->RGET('/api/player/messages.amf');
        $this->forwardUnless($r, 'common', 'index');
        
        $this->getContext()->getConfiguration()->registerZend();
        $amf_stream = new Zend_Amf_Parse_InputStream($r);
        $amf_parser = new Zend_Amf_Parse_Amf3_Deserializer($amf_stream);
        $this->result = $amf_parser->readTypeMarker();

//        $this->getUser()->setAttribute('unread', $response->unread_messages, 'player/messages');
//        $this->getUser()->setAttribute('total', count($response->messages));
        
    }
    
    public function executeRead(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $this->forward404Unless($id);
        
        $query = array();
        $query['_method'] = 'put';
        
        $r = $this->getUser()->RPOST("/api/player/messages/{$id}/read", $query);
        $this->forward404Unless($r);
        
        $response = json_decode($r)->response;
        
        $this->getUser()->setAttribute('unread', $response->unread_messages, 'player/messages');
        
        return $this->renderPartial("body-{$response->message->type}", array('message'=>$response->message));
        
    }

    // POST /api/player/messages/
    // _method=delete
    // _session_id=2bfd5bfe94b754089a890e7b19144f95
    // ids=256332777,908324902843,23423424323
    // meltdown=e23282d0c83bbd54a0aa8a1981da85ba8a13b753
    // reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
    // testCount=266
    // user_id=608208
    
    public function executeDelete(sfWebRequest $request)
    {
        $ids = $request->getParameter('id');
        $this->forward404Unless($ids);
        if(is_array($ids)) {
            $ids = implode(',', $ids);
        }
        
        $query = array();
        $query['_method'] = 'delete';
        $query['ids'] = $ids;
        
        $r = $this->getUser()->RPOST('/api/player/messages', $query);
        $this->forward404Unless($r);
        
        $response = json_decode($r)->response;
        
        $this->getUser()->setAttribute('unread', $response->unread_messages, 'player/messages');
        $this->getUser()->setAttribute('total', count($response->messages));
        
        return sfView::HEADER_ONLY;
    }
}
