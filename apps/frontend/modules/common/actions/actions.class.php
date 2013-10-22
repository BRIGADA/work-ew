<?php

/**
 * common actions.
 *
 * @package    edgeworld
 * @subpackage common
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commonActions extends sfActions
{

    /**
     * Executes index action
     *
     * @param sfRequest $request
     *            A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->meltdowns = Doctrine::getTable('Meltdown')->createQuery()
            ->orderBy('id DESC')
            ->limit(10)
            ->execute();
        
        $this->proxy_status = NULL;

        if ($this->proxy_status !== NULL)
            $this->proxy_status = json_decode($this->proxy_status, true);
        
        $this->clientForm = new ClientForm();
    }

    public function executeStats()
    {
        $result = $this->getUser()->RGET('/api/player');
        
        if ($result) {
            $data = json_decode($result, true);
            $response = array();
            $response['level'] = $data['response']['level'];
            $response['platinum'] = $data['response']['platinum'];
            $response['sp'] = $data['response']['sp'];
            $response['xp'] = $data['response']['xp'];
            $response['current_level_xp'] = $data['response']['current_level_xp'];
            $response['next_level_xp'] = $data['response']['next_level_xp'];
            
            $this->getResponse()->setContentType('application/json');
            return $this->renderText(json_encode($response));
        }
        $this->forward404();
    }

    public function executeSetClient(sfWebRequest $request)
    {
        $form = new ClientForm();
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            if ($this->getUser()->initPlayer($form->getValue('host'), $form->getValue('reactor'), $form->getValue('_session_id'), $form->getValue('user_id'))) {
                $this->getUser()->setFlash('success', 'Игрок успешно инициализирован');
            } else {
                $this->getUser()->setFlash('error', 'Ошибка инициализации игрока');
            }
        } else {
            $this->getUser()->setFlash('error', 'Ошибка на форме');
        }
        
        $this->redirect('common/index');
    }

    public function executeSetURL(sfWebRequest $request)
    {
        $url = $request->getParameter('url');
        $this->forward404Unless($url);
        
        $result = parse_url($url);
        
        if ($result) {
            
            parse_str($result['query'], $query);
            
            if (MeltdownTable::getCurrent() != $query['meltdown']) {
                $meltdown = new Meltdown();
                $meltdown->value = $query['meltdown'];
                $meltdown->save();
            }
            
            if ($this->getUser()->initPlayer($result['host'], $query['reactor'], $query['_session_id'], $query['user_id'])) {
                $this->getUser()->setFlash('success', 'Игрок успешно инициализирован');
            } else {
                $this->getUser()->setFlash('error', 'Ошибка инициализации игрока');
            }
        } else {
            $this->getUser()->setFlash('error', 'Ошибка разбора URL');
        }
        
        $this->redirect('common/index');
    }

    public function executeREMOTE(sfWebRequest $request)
    {
        $path = $request->getParameter('path');
        $this->forward404Unless($path);
        
        $query = $request->getParameter('query', array());
        
        $result = $request->getMethod() == sfWebRequest::POST ? $this->getUser()->RPOST($path, $query) : $this->getUser()->RGET($path, $query);
        $this->forward404Unless($result, 'FETCH FAILED');
        
        foreach($request->getParameter('replace', array()) as $replace) {
            $result = preg_replace($replace['s'], $replace['d'], $result);
        }
        
        switch ($request->getParameter('decode')) {
            case 'base64':
                $result = base64_decode($result);
                break;
            case 'amf':
                $this->getContext()->getConfiguration()->registerZend();
                $amf_stream = new Zend_Amf_Parse_InputStream($result);
                $amf_parser = new Zend_Amf_Parse_Amf3_Deserializer($amf_stream);
                $result = json_encode($amf_parser->readTypeMarker());
                break;
            default:
                break;
        }
        
        if (! is_null($request->getParameter('element'))) {
            $result = json_decode($result, true);
            foreach (explode('/', $request->getParameter('element')) as $element) {
                $this->forward404Unless(isset($result[$element]), 'ELEMENT NOT FOUND');
                $result = $result[$element];
            }
            $result = json_encode($result);
        }
        
        $this->getResponse()->setContentType($request->getParameter('type', 'application/json'));
        return $this->renderText($result);
    }

    public function executeResetTestCount()
    {
        $this->getUser()->setAttribute('testCount', 1, 'player/data');
        $this->redirect('common/index');
    }
}
