<?php

/**
 * Random Articles helper 
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

abstract class ModArticlesRandomHelper
{
    public static function getArticles($params)
    {

        $catids = $params->get("catid");
        $count = $params->get('count');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from('#__content');

        if (is_array($catids) && is_array($count)) {
            $query->where('catid IN (' . implode(',', $catids) . ')');
        }
        $query->where('state=1');
        $query->order('RAND()');
        $db->setQuery($query, $count);
        $articles = $db->loadObjectList();

        foreach ($articles as $article) {
            $article->slug = $article->id . ":" . $article->alias;
            $article->url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language));
        }

        return $articles;
    }

    public function getAjax() // This function will be automatically known by joomla to load - called automatically from com_ajax
    {
        $app = JFactory::getApplication();

        $catids = $app->input->get('catids', '');
        $count = $app->input->get('count', 5);

        $params = new JRegistry();
        $params->set('catid', explode(',', $catids));
        $params->set('count', $count);

        $articles = ModArticlesRandomHelper::getArticles($params);

        return $articles;
    }
}
