<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.Fbsocial
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();


class PlgSystemFbsocialog extends JPlugin
{

	public function onAfterDispatch()
  	{

  		// Check if are in the admin section and skip else continue
		$app  = JFactory::getApplication();
		if ($app->isClient('administrator'))
		{
			return;
		}

		$document = JFactory::getDocument();
		$app_id   = $this->params->get('app_id');
		$fb_page  = $this->params->get('fb_page');

		if ( $app_id ) {
			$document->addCustomTag('<meta property="fb:app_id" content="'.$app_id.'" />');
		}

		if ( $fb_page ) {
			$document->addCustomTag('<meta property="fb:pages" content="'.$fb_page.'" />');
		}

  	}

	public function onContentAfterDisplay($context, &$article, &$params, $limitstart)
	{

		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return;
		}

		// Add info in the OG metatag if are in the component content
		if ( $context == 'com_content.article' )
		{

			$document = JFactory::getDocument();

			$article_images 	= json_decode($article->images);
			$article_image 		= '';
			if(isset($article_images->image_fulltext) && !empty($article_images->image_fulltext)) {
				$article_image 	= $article_images->image_fulltext;
			}elseif (isset($article_images->image_introtext) && !empty($article_images->image_introtext)) {
				$article_image 	= $article_images->image_introtext;				
			}

			$document->addCustomTag('<meta property="og:url" content="'.JURI::current().'" />');
			$document->addCustomTag('<meta property="og:type" content="article" />');
			$document->addCustomTag('<meta property="og:title" content="'. $article->title .'" />');
			$document->addCustomTag('<meta property="og:description" content="'. JHtml::_('string.truncate', $article->introtext, 155, false, false ) .'" />');
			if ($article_image) {
				$document->addCustomTag('<meta property="og:image" content="'. JURI::root().$article_image.'" />');
				$document->addCustomTag('<meta property="og:image:width" content="600" />');
				$document->addCustomTag('<meta property="og:image:height" content="315" />');
			}

		}

	}

}
