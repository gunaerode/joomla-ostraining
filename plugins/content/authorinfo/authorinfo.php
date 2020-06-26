<?php
/**
 * Author Info Plugin
 */

defined('_JEXEC') or die;

class PlgContentAuthorinfo extends JPlugin
{
    public function onContentPrepare($context, &$row, $params, $page = 0)
    {
        $tag = '{author_info}';

        if(!$this->showInfo($context, $row, $tag)) {
            return true;
        }

        $display = $this->params->get('display', array());

        if(!in_array('tag', $display)) {
            $row->text = str_replace($tag, '', $row->text);
            return true;
        }

        $author_info = $this->getAuthorInfo($row->created_by);

        $row->text = str_replace($tag, $author_info, $row->text);

        $matches = array();
        $regex = '/{author_info\s(.*?)}/i';
        preg_match_all($regex, $row->text, $matches, PREG_SET_ORDER);

        foreach($matches as $match) {

            $id = str_replace('id=', '', $match[1]);

            $match_author_info = '';

            if(is_numeric($id)) {
                $match_author_info = $this->getAuthorInfo($id);
            }

            $row->text = str_replace($match[0], $match_author_info, $row->text);
        }

        return true;
    }

    protected function showInfo($context, &$row, $tag)
    {
        $app = JFactory::getApplication();

        if($app->isAdmin())
        {
            return false;
        }

        $allowed_contexts = array('com_content.article');

        if(!in_array($context, $allowed_contexts))
        {
            $row->text = str_replace($tag, '', $row->text);
            return false;
        }

        if(empty($row->created_by) || !$row->created_by)
        {
            return false;
        }

        $categories_include = $this->params->get('cat_include', array());
        $categories_exclude = $this->params->get('cat_exclude', array());

        if(!in_array($row->catid, $categories_include))
        {
            $row->text = str_replace($tag, '', $row->text);
            return false;
        }

        if(in_array($row->catid, $categories_exclude))
        {
            $row->text = str_replace($tag, '', $row->text);
            return false;
        }

        return true;
    }

    protected function getAuthorInfo($author_id)
    {
        $author = new JUser($author_id);
        $custom_text = $this->params->get('custom_text', '');

        $profile = JUserHelper::getProfile($author_id);

        ob_start();
        include JPluginHelper::getLayoutPath('content', 'authorinfo');
        $author_info = ob_get_clean();

        return $author_info;
    }

    public function onContentBeforeDisplay($context, &$row, $params, $page = 0)
    {
        $tag = '{author_info}';

        if(!$this->showInfo($context, $row, $tag)) {
            return true;
        }

        $display = $this->params->get('display', array());

        if(!in_array('before', $display)) {
            return true;
        }

        $author_info = $this->getAuthorInfo($row->created_by);

        return $author_info;
    }

    public function onContentAfterDisplay($context, &$row, $params, $page = 0)
    {
        $tag = '{author_info}';

        if(!$this->showInfo($context, $row, $tag)) {
            return true;
        }

        $display = $this->params->get('display', array());

        if(!in_array('after', $display)) {
            return true;
        }

        $author_info = $this->getAuthorInfo($row->created_by);

        return $author_info;
    }
}