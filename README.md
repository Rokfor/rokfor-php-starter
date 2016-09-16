# rokfor-php-starter

Rokfor Website Boilerplate running on PHP using 

- Slim Framework for routing
- Jade for templating
- Sass as CSS processor
- Grunt and Bower to install dependencies

### Installation

#### Prerequisites

- Access to a Rokfor Server
- Composer and Node.js
- PHP >= 5.5

#### Configuration

    $ git clone https://github.com/rokfor/rokfor-php-starter
    $ cd rokfor-php-starter
    $ composer install
    $ cd config
    $ cp settings.local.php settings.php

#### Connect to the Rokfor Server

If you check out the repository, the example will connect to the Rokfor Demo 
Server. There is a read only access and some dummy content available just to make
sure, the application runs.

If you want to use the system for real, ping me [@uphofer](http://twitter.com/uphofer) 
for an account or deploy your own [Rokfor Server](https://github.com/Rokfor/rokfor-slim).

Edit the settings.php File and set the corresponding keys for the Rokfor Server:

    'rokfor' => [
      'ro-key'    => 'YOUR_READ_ONLY_ACCESS_KEY',
      'rw-key'    => 'YOUR_WRITE_ACCESS_KEY',
      'user'      => 'USERNAME_FOR_WRITE_LOGINS',
      'endpoint'  => 'https://www.example.com/api'
    ]

Now you can fire up a local PHP Server using the command:

    /usr/bin/php -S 0.0.0.0:8080 -t public public/index.php

Probably you can just type

    $ ./run-server.sh

And open your Site in the Browser (http://localhost:8080)

### Using Grunt and Bower and Sass

**All files for the build task reside in the `build` directory**

If you want to develop a site, you probably want to use Javascript libraries from Bower.
To install the necessary dependencies, use npm:

    $ npm install
    $ bower install
    $ grunt

There is a `grunt watch` task, which minifies javascript files and copies the assets from
the `build` directory in to the web directory.

Per default, CSS is compiled with SASS. There are dependencies with Bourbon, Bitters and
Neat which are installed automatically with Bower. As an example, FontAwesome is also
copied and installed.

### Templates and Routes

The main work, besides creating CSS styles, is to add routes in the `src/routes.php` file.
Refer to the [Slim Documentation](http://www.slimframework.com/docs/objects/router.html) for
a introduction.

Example Route for `GET /`:

    $app->get('/', function ($request, $response, $args) {
      
      // Load /api/books from the Server
      $result = $this->api->get("books"); 
      
      // Response ok?
      if($result->info->http_code == 200) {     
        // Decode JSON
        $args = $result->decode_response(); 
        // Render index.jade Template
        $this->view->render($response, 'index.jade', $args);
      }
      
      // Throw Error Page
      else {      
        throw new NotFoundException($request, $response);
      }
    }); 


Example Template:

    doctype html
    html
      include parts/layout.meta.jade
      body
        section
          h1
            header='There are '.count($Books).' Books available.'
        section
          each book, index in Books
            br
            h5='Book '.($index+1).': '.$book[Name]
            each chapter in book.Chapters
              span='Chapter: '.$chapter[Name]
              br

You'll find in the Template also a link to the live reload script 
to reload changes depending on `grunt watch`.

### Debugging

If you want to have an in-depth knowledge of what kind of Json data are transmitted
from the server to the client, you can log it into the Chrome Console.

Open the `settings.php` File and set logger->path to `false` and logger->level to
`Monolog\Logger::INFO`:

        'logger' => [                                           // Monolog settings
          'path'          => false,                             // Path to Log File. 
                                                                // If false, PHPConsole for Chrome will be used 
                                                                // http://php-console.com
          'level'         => Monolog\Logger::INFO,              // Error Level: 
                                                                // Monolog\Logger::INFO shows a lot of stuff from the API
                                                                // Monolog\Logger::ERROR
        ]

Make sure, that you have php-console installed in the vendor directory:

    $ composer require php-console/php-console

Additionally, you need to install the Chrome Extension from [PHP-Console](http://php-console.com).
If everything runs well, you can open your website and you'll see a dump of every API decode call.
This eases the creation of templates quite a bit.