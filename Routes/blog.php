<?php

$app->get(URI::tag('BLOG'), function() use ($app) {
   $page = new Page($app);

   $posts = Content::all();

   $page->addData(['posts' => $posts]);
   $page->addTemplate('blog.phtml');
   $page->setTitle("Blog");

   $page->render();
});

$app->get(URI::tag('BLOG') . ":id/", function($id) use ($app) {
   $page = new Page($app);

   $post = new Content($page->userid, $id);

   $page->addData(['post' => $post]);
   $page->addStyle("content.css");
   $page->addTemplate('content.phtml');
   $page->setTitle($post->title);

   $page->render();
});

$app->get(URI::tag('CREATE'), function() use ($app) {
   $page = new Page($app);
   $page->setTitle("Edit");

   $post = false;
   $page->addData(['post' => $post]);
   // Add the post editor
   $page->addTemplate('edit.phtml');
   $page->addScript("edit.js");
   $page->addStyle("edit.css");
   // And add the media manager
   $page->addTemplate('mediaManager.phtml');
   $page->addScript("mediaManager.js");

   $page->render();
});

$app->get(URI::tag('EDIT') . ":id", function($id) use ($app) {
   $page = new Page($app);
   $page->setTitle("Edit");

   $post = new Content($page->userid, $id);
   // Add the post data to the page
   $page->addData(['post' => $post]);
   // Add the post id to the App variable for JS
   $page->addJSData(['post' => $post]);

   // Add the post editor
   if (isset($_GET['medium'])) {
      $page->addTemplate('edit.phtml');
      $page->addScript("edit.js");
      $page->addStyle("edit.css");
   } else {
      $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.min.js');
      $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/mode/markdown/markdown.min.js');
      $page->addRemoteStyle('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.css');
      $page->addTemplate('markdownEditor.phtml');
      $page->addScript('markdownEditor.js');
   }

   // And add the media manager
   $page->addTemplate('mediaManager.phtml');
   $page->addScript("mediaManager.js");

   $page->render();
});
