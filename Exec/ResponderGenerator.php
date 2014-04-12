<?php
// And our libraries
include __DIR__ . "/Essential.php";

$tags = URI::navLinks();

foreach ($tags as $tag => $route) {
   $file = <<<EOT
<?php

\$app->get(URI::tag("$tag"), function () use (\$app) {
   \$app->redirect('/404/');
});

\$app->post(URI::tag("$tag"), function () use (\$app) {
   \$app->redirect('/404/');
});
EOT;

   print($file);
   print("\n");

}
