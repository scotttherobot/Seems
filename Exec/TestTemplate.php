<?php
// And our libraries
foreach (glob("../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob("../Objects/*.php") as $filename) {
   include $filename;
}


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
