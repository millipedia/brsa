<?php namespace ProcessWire;

/**
 * Admin template just loads the admin application controller, 
 * and admin is just an application built on top of ProcessWire. 
 *
 * This demonstrates how you can use ProcessWire as a front-end 
 * to another application. 
 *
 * Feel free to hook admin-specific functionality from this file, 
 * but remember to leave the require() statement below at the end.
 * 
 */

wire()->addHookAfter('Dashboard::getPanels', function ($event) {
    /* Get list of panels */
    $panels = $event->return;
  
      $panels->add([
        'panel' => 'template',
        'title' => 'Hello',
        'size' => 'small',
        'icon' => 'smiley',
        'data' => [
          'template' => 'dashboard_panel_text.php',
          'variables' => [
            'text' => ''
          ]
        ]
      ]);

      // get count of pending comments

      $comments=wire('fields')->get('comments')->type->find("limit=10,status=Pending");

      if($comments && $comments->count){
        $comments_count=$comments->count;
        $comments_message="<a href='/bradmin/setup/comments/list/comments/pending/'>Comments need reviewing</a>";
      }else{
        $comments_count=0;
        $comments_message="Nothing to see here";
      }

      $panels->add([
        'panel' => 'number',
        'title' => 'Pending Comments',
        'size' => 'small',
        'data' => [
          'number' => $comments_count,
          'detail' => $comments_message
        ]
      ]);

      $panels->add([
        'panel' => 'template',
        'title' => 'Handy links',
        'size' => 'small',
        'icon' => 'plus',
        'data' => [
          'template' => 'dashboard_panel_butts.php',
          'variables' => [
            'text' => ''
          ]
        ]
      ]);


      $panels->add([
        'panel' => 'template',
        'title' => 'Fixins',
        'size' => 'large',
        'icon' => 'bug',
        'data' => [
          'template' => 'dashboard_panel_fixins.php',
          'variables' => [
            'text' => ''
          ]
        ]
      ]);

      $panels->add([
        'panel' => 'template',
        'title' => 'Need more info',
        'size' => 'small',
        'icon' => 'alert-triangle',
        'data' => [
          'template' => 'dashboard_panel_more_info_needed.php',
          'variables' => [
            'text' => ''
          ]
        ]
      ]);



   });

   require($config->paths->adminTemplates . 'controller.php'); 
