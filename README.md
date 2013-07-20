PHP Schema
============
This project is for symfony php framework, specially for symfony + doctrine.

Intro
-----
The thinking of this project has a very long history in my mind. But it comes true just in these few days.

The simplest way of my thinking is if i have an ini file, for example:

```
; this is Article.ini
title = An example article
content = this is an article...contents...etc...
author = yarco
pubdate = 2013-07-12 20:00
clicks = 0
```

So if you have a such ini file, and after i run some command, the tool will help me to create such a mysql table:

```
CREATE TABLE Article
(
id ...,
title VARCHAR(200) NOT NULL DEFAULT '',
content TEXT,
author VARCHAR(200) NOT NULL DEFAULT 'anonymous',
pubdate DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
clicks INTEGER NOT NULL DEFAULT 0
) ENGINE=InnoDB;
```

Though it does't need so much accurate. But i really like this during my 7 years programming life. And it appears in my own php framework which i never really published. Why? Because soon i fall in fighting with different low level sql statements. That makes me boring.

But now by using doctrine, it comes true.

Though the thinking is very common, this project only works under symfony + doctrine. That means you can only use it in symfony project.

How to install
--------------
0. make sure you are doing symfony project
1. you could use `composer` to install this class. add `"yarco/php-schema": "v0.0.1"` in your composer.json's require field like the following:

```
{
	...
	"require": {
		...
		"yarco/php-schema": "v0.0.1"
	}
}
```

2. run `composer update` to download it
3. add `$bundles[] = new Schema\Bundle\SchemaBundle();` in `registerBundles` function in `app/AppKernel.php` in symfony framework
4. now you can run `php app/console list` to view the command. You will found there will be a new `schema:generate:entity` command.

How to use
-----------
0. you put an ini file under `schemas/`, for example:

```
yarco@me test$ ls
LICENSE        UPGRADE-2.2.md app            composer.json  proj           src            web
README.md      UPGRADE.md     bin            composer.lock  schemas        vendor
yarco@me test$ cat schemas/Post.ini 
; a real example from
; http://www.latimes.com/news/world/worldnow/la-fg-wn-snowden-in-russia-wants-meet-with-lawyers-20130712,0,4831802.story?asdf

[fields]
title = In Russia, Snowden seeks meeting with lawyers, rights activists
author = Sergei L. Loiko
; July 12, 2013, 1:12 a.m.
pubdate = 2013-07-12 13:12
content = MOSCOW — Edward Snowden, the self-professed leaker of classified National Security Agency documents that reveal the U.S. government's secret phone and Internet surveillance programs, has asked for a meeting with lawyers and human rights activists, Interfax news agency reported Friday morning...
```

1. run `php app/console schema:generate:entity --entity=AcmeDemoBundle:Post  --schema=Post` just like you are running `php app/console doctrine:generate:entity --entity=AcmeDemoBundle:Post --fields="..."` . Only `fields` option is replaced by `schema`
2. finnaly, you get the entity file. It certainly can be used for `doctrine:schema:update`. For example:

```
yarco@me test$ php app/console doctrine:schema:update --dump-sql
CREATE TABLE Post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, pubdate DATETIME NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
```

It is really amazing cause you don't need to worry about the `types`.

Extra notice
------------
Certainly, it is just like what doctrine said. Such automatic tool should be only used in develope environment. You must keep an optimize one for product purpose. And currently the following types are not supported:

* smallint, bigint
* decimal
* array, object
* guid, blob

Supported commands
------------------
* schema:generate:entity -- generate entity by schema definition (an ini file)
* schema:init:data -- import fixture data from a csv file

ChangeLog
----------
* v0.0.1-v0.0.2:
  * add import fixture data in a simple way. see more: `php app/console schema:init:data --help`

Contact
--------
You could contact [me][] through <yarco.wang@gmail.com> according to further debugging or maintance. Programming related topics are also welcomed.

And this guy is also searching for a job which could be:

* Sr. php programmer
* php team leader
* nodejs developer

Prefer salary between 14k to 22k (RMB)

timezone: GMT+0800  
update: 2013-07-20

[me]:http://bbish.net
