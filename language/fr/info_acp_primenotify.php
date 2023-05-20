<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_PRIMENOTIFY_TITLE'					=> 'Prime Notify',
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'Paramètres Prime Notify',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'Réglages généraux',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'Paramètres de Prime Notify sauvegardés avec succès!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>Paramètres de Prime Notify modifiés</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'Choix de l\'utilisateur',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'Les options de choix de l\'utilisateur seront affichées dans la section <strong>Modifier les paramètres généraux</strong> de l\'onglet <strong>Préférences du forum</strong> sur le <strong>Panneau de configuration de l\'utilisateur</strong>, lorsqu\'elles sont activées.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'Activer pour les publications',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Inclure le contenu d\'une publication dans le courriel de notification envoyé aux utilisateurs.',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'Activer pour les messages privés',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Inclure le contenu d\'un message privé dans le courriel de notification envoyé aux utilisateurs.',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Conserver les BBCodes',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Les courriel sont envoyés en texte brut afin que les BBCodes ne soient pas formaté. Les conserver peut aider à afficher la mise en forme souhaitée, tandis que les supprimer peut rendre le message plus propre.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Courriel à chaque nouvelle publication',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Envoyez un courriel de notification pour chaque nouveau message publié dans le sujet ou le forum auquel vous êtes abonné. Normalement, un courriel de notification est envoyé uniquement pour le premier nouveau message depuis la dernière visite de lutilisateur sur le sujet.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'Limiter la longueur des messages',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'Tronque les messages plus longs que la limite de caractères spécifiée. Mettre à 0 pour aucune limite.',
));
