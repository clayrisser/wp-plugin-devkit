# WP Plugin DevKit

A modern way to develop WordPress plugins

![](assets/wp-plugin-devkit.png)


## Features

* Namespace and initialize your plugin with a simple wizard
* Test your plugin in an automatically generated [WordPress](https://wordpress.org/) environment
* View the database in an automatically generated [phpMyAdmin](https://www.phpmyadmin.net/) environment
* [Composer](https://getcomposer.org/) PHP package management support
* Linted with [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) using the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)
* End-to-end smoke tested with [bats](https://github.com/bats-core/bats-core) and [wp-cli](https://wp-cli.org/)
* Built using WordPress best practices
* Development environment starts in DEBUG mode


## Installation

```sh
git clone git@gitlab.com:bitspur/misc/wp-plugin-devkit.git
cd wp-plugin-devkit
make prepare
```


## Dependencies

* [Git](https://git-scm.com/) - Version control
* [Docker](https://www.docker.com/) - Containerization engine
* [Make](https://www.gnu.org/software/make/) - Command abstraction
* [PHP](https://www.php.net/) 8.1+ - General-purpose scripting language
* [Composer](https://getcomposer.org/) - PHP package management

`make prepare` bootstraps everything: php and composer via the system package
manager (brew/apt/dnf), bats and shfmt via [asdf](https://asdf-vm.com/) as
pinned in `.tool-versions` (php itself is not asdf-managed because the asdf
php plugin compiles from source), and the composer dependencies.


## Usage

```sh
make init                # initialization wizard (rename the plugin)
make docker/provision    # start wordpress + database, install wordpress
```

The [WordPress](https://wordpress.org/) development website is located at
[http://localhost:8888](http://localhost:8888) and logs in with the
`WP_ADMIN_USER` / `WP_ADMIN_PASSWORD` credentials from `.env`
(default _admin_ / _wordpress_).

[phpMyAdmin](https://www.phpmyadmin.net/) is available under the `dev`
profile at [http://localhost:8889](http://localhost:8889).

Ports, database credentials and the WordPress admin account are all
configured through `.env`, which is bootstrapped from `.env.example` on the
first `make` invocation.

### Make Targets

| Target                  | Description                                                        |
| ----------------------- | ------------------------------------------------------------------ |
| `make prepare`          | Install system tools, the asdf toolchain and composer dependencies |
| `make init`             | Initialization wizard                                              |
| `make lint`             | Check code style (phpcs + shfmt)                                   |
| `make format`           | Auto-format code (phpcbf + shfmt)                                  |
| `make test/e2e`         | End-to-end smoke tests (bats + wp-cli against the docker stack)    |
| `make count`            | Count lines of code                                                |
| `make docker/up`        | Run wordpress and database in the foreground                       |
| `make docker/up-d`      | Run wordpress and database detached                                |
| `make docker/up/dev`    | Also run the dev profile (phpMyAdmin)                              |
| `make docker/provision` | Start the stack detached and install WordPress headlessly          |
| `make docker/logs`      | Follow container logs                                              |
| `make docker/ps`        | List containers                                                    |
| `make docker/down`      | Tear down the stack, volumes included                              |
| `make clean`            | Remove generated make state                                        |
| `make purge`            | Remove everything not tracked by git                               |

`make test/e2e` stands up an isolated compose project (port 18888), installs
WordPress with wp-cli, activates the plugin, asserts it activates and serves
without errors, and exercises the init wizard in a throwaway copy of the
repo. Set `KEEP_STACK=1` to leave the test stack running for inspection.


## Support

Submit an [issue](https://gitlab.com/bitspur/misc/wp-plugin-devkit/-/issues/new)


## License

[GPL-3.0-or-later](LICENSE)

[Clay Risser](https://clayrisser.com) © 2017-2026


## Credits

* [Clay Risser](https://clayrisser.com) - Author
* Loosely based on the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)


## Changelog

0.1.0 (2026-08-12)
* Revival: PHP 8 compatibility, current WordPress support, compose-based dev environment, phpcs linting, e2e smoke tests

0.0.1 (2017-04-28)
* Beta release
