<?php

$app->get(URI::tag('BLOG'), function() use ($app) {
   $page = new Page($app);

   // Anticipage human-readable page numbers
   $pageno = idx($_GET, 'p', 1);
   // Make sure we don't go negative.
   if ($pageno < 1)
      $pageno = 1;

   // Magic number, 5 posts-per-page
   $offset = ($pageno - 1) * 5;

   $posts = Content::all($offset);

   // If we have no posts, go back a page?
   if (count($posts) == 0 && $pageno != 1) {
      $app->redirect("/blog?p=" . ($pageno - 1));
   }

   $page->addData([
      'posts' => $posts,
      // Pagination links
      'nextPage' => count($posts) < 5 ? false : $pageno + 1,
      'previousPage' => $pageno - 1 ?: false,
   ]);
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

   $post = new Content($page->user);
   $page->addData(['post' => $post]);
   // Add the post editor
   $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.min.js');
   $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/mode/markdown/markdown.min.js');
   $page->addRemoteStyle('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.css');
   $page->addTemplate('markdownEditor.phtml');
   $page->addScript('markdownEditor.js');
   // And add the media manager
   $page->addTemplate('mediaManager.phtml');
   $page->addRemoteScript('/3P/reveal/jquery.reveal.js');
   $page->addRemoteStyle('/3P/reveal/reveal.css');
   $page->addScript("mediaManager.js");
   $page->addStyle("mediaManager.css");

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
   $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.min.js');
   $page->addRemoteScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/mode/markdown/markdown.min.js');
   $page->addRemoteStyle('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.0.3/codemirror.css');
   $page->addTemplate('markdownEditor.phtml');
   $page->addScript('markdownEditor.js');

   // And add the media manager
   $page->addTemplate('mediaManager.phtml');
   $page->addRemoteScript('/3P/reveal/jquery.reveal.js');
   $page->addRemoteStyle('/3P/reveal/reveal.css');
   $page->addScript("mediaManager.js");
   $page->addStyle("mediaManager.css");

   $page->render();
});
