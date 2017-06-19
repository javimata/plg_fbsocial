<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.Fbsocial
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

/**
 * Plug-in to show a custom field in eg an article
 * This uses the {fields ID} syntax
 *
 * @since  3.7.0
 */
class PlgContentFbsocial extends JPlugin
{
	/**
	 * Plugin that shows a custom field
	 *
	 * @param   string  $context  The context of the content being passed to the plugin.
	 * @param   object  &$item    The item object.  Note $article->text is also available
	 * @param   object  &$params  The article params
	 * @param   int     $page     The 'page' number
	 *
	 * @return void
	 *
	 * @since  3.7.0
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart)
	{


		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return;
		}

		if ( $context == 'com_content.article' )
		{

			$app_id     = $this->params->get('app_id');
			$article_images 	= json_decode($article->images);
			$article_image 		= '';
			if(isset($article_images->image_fulltext) && !empty($article_images->image_fulltext)) {
				$article_image 	= $article_images->image_fulltext;
			}elseif (isset($article_images->image_introtext) && !empty($article_images->image_introtext)) {
				$article_image 	= $article_images->image_introtext;				
			}

			// print_r($article);

			$document = JFactory::getDocument();

			if ( $app_id ) {
				$document->addCustomTag('<meta property="fb:app_id" content="'.$app_id.'" />');
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

		// print_r($params);

	}
}
