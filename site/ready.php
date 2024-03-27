<?php

namespace ProcessWire;

/**
 * Add a hook after ProcessPageEdit that loads some js to 
 * resize any CKEditors.
 */

function resizeEditor(HookEvent $event)
{
	$js_path = wire()->config->urls->templates . "resize_editor.js";
	wire()->config->scripts->add($js_path);
}

wire()->addHookAfter('ProcessPageEdit::execute', null, 'resizeEditor');

/**
 * 
 * Skeleton build cash on save. 
 * 
 */

function skeleton_cache(HookEvent $event)
{

	$page = $event->arguments(0);

	if ($page->template == "whatevertemplate") {

		// get cache
		$tag_array_string = wire()->cache->get("ac-taglist");

		// build cache
		$all_tags = wire()->pages->find("template=tag");
		$tag_array = array();


		foreach ($all_tags as $tag) {

			if ($tag->references('template=resource')->count > 0) {
				$tag_array[] = '"' . addslashes($tag->title) . '"';
			}
		}

		$tag_array_string = implode(',', $tag_array);

		// save cache
		wire()->cache->save('ac-taglist', $tag_array_string);
	}
}

wire()->addHookAfter('Pages::saveReady', null, 'skeleton_cache');



// before we save a shop page lets sort some fields.
$this->addHookBefore('Pages::save', function (HookEvent $event) {

	// Get the object the event occurred on, if needed
	// $pages = $event->object;

	// Get values of arguments sent to hook (and optionally modify them)
	$page = $event->arguments(0);

	if(!$page->id) return;
	
	$options = $event->arguments(1);

	if ($page->template != 'shop') return;

	/* Replace some junk from Wix */
	if($page->content){
		
		$content=$page->content;
		$content=str_replace('<figcaption>Picture</figcaption>','',$content);
		$content=str_replace('<p><img alt="Picture" src="https://www.editmysite.com/editor/images/na.png" /></p>','',$content);
		$content=str_replace('img alt="Picture"','img alt=""',$content);

		$page->content=$content;
		
	}

	// We now set county fields to the parent of the town.
	// I guess this isn't third normal form but probably
	// more efficient for searching ... maybe.
	
	if($page->addresses){

		foreach($page->addresses as $address){

			if($address->town){
				$address->setAndSave('county', $address->town->parent);

			}

		}

	}

	// Populate back arguments (if you have modified them)
	$event->arguments(0, $page);
	$event->arguments(1, $options);


});


/**
 * Search shops for typeahead field.
 */

$wire->addHook('/typeahead/', function($event) {

	$response='';

	// look for a GET variable named 'q' and sanitize it
	$q = wire('sanitizer')->text(wire('input')->get->q); 
	if($q) { 
		// Sanitize for placement within a selector string. This is important for any 
		// values that you plan to bundle in a selector string like we are doing here.
		$q = wire('sanitizer')->selectorValue($q); 
		$selector = "template=shop,title~*=$q, limit=10";
		$matches = wire('pages')->find($selector); 

		// did we find any matches?
		if($matches->count) {

			foreach($matches as $smatch){

				$malue=$smatch->title;

				if($smatch->get_county()){
					$malue.= ' - ' . $smatch->get_county()->title;
				}
				$response.='<option value="' . $malue .'" data-shopurl="' . $smatch->url . '">' . $malue . '</option>';
			}


		}

	}

	return $response;

  });
