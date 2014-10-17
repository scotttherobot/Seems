## Seems

Seems is a tiny personal blogging platform. It gets its name from the initialism CMS, which we all
know to mean Content Management System. This is because Seems is, in fact, a simple CMS.

* It's backed by MySQL.
* It's templatable.
 * TODO: Write more templates. Currently the only one is my personal site template.
* It automatically generates images.
 * Asynchronously via a Beanstalk queue.
 * Original, Medium, Small sizes.
 * Images are converted to Progressive JPG for better loading.
 * Images are stored via a filesystem abstraction.
  * Can store locally, in S3, Azure, etc.
* You can create image galleries.
 * Upload images to a gallery wall.
 * Experimental #selfie mode allows visitors to post #selfies into a special gallery with their webcams.
  * Think: a Web 1.0 Guestbook but with a 2k14 spin.
* Content is written in Markdown and rendered out to HTML.
 * Post editor is a JS Markdown editor with syntax highlighting, undo/redo, and image insertion.
* There's currently no management interface.

## Why would you do this?? 

I had every intention of running my personal site on this, and then I was sucked into Ghost because
it's beautiful and I'm an awful designer, and it natively supports Markdown.

### Dependencies

* PHP 5.4
* Beanstalkd
* MySQL
