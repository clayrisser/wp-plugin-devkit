# WP Plugin DevKit _Beta_

A modern way to develop WordPress plugins

Please &#9733; this repo if you found it useful &#9733; &#9733; &#9733;


## Features
<!------------------------------------------------------->

* Namespace and initialize your plugin with a simple wizard!!!
* Test plugin in an automatically generated [WordPress](https://wordpress.org/) environment
* View database in an automatically generated [phpMyAdmin](https://www.phpmyadmin.net/) environment
* SSH into WordPress container
* Composer PHP package management support
* Built using WordPress best practices
* Development environment starts in DEBUG mode


## Installation
<!------------------------------------------------------->

```sh
git clone https://github.com/jamrizzi/wp-plugin-devkit.git
cd wp-plugin-devkit
make install
```


## Dependencies
<!------------------------------------------------------->

* [Git](https://git-scm.com/) - Version control
* [Docker](https://www.docker.com/) - Containerization engine
* [Make](https://www.gnu.org/software/make/) - Command abstraction
* [PHP](http://php.net/) - General-purpose scripting language
* [Composer](https://getcomposer.org/) - PHP package management


## Usage
<!------------------------------------------------------->

```sh
make init
make start
```

[WordPress](https://wordpress.org/) development website located at [http://localhost:8888](http://localhost:8888)

[phpMyAdmin](https://www.phpmyadmin.net/) located at [http://localhost:8889](http://localhost:8889)

Default Username: _admin_

Default Password: _hellowordpress_

### Make Commands
| Command           | Description                                                                       |
| ----------------- | --------------------------------------------------------------------------------- |
| `make init`       | Initialization wizard                                                             |
| `make start`      | Runs wordpress, database, and sql client for development testing                  |
| `make wordpress`  | Runs wordpress for development testing (depends on `make database`)               |
| `make database`   | Runs database for development testing                                             |
| `make sql_client` | Runs a phpmyadmin sql client for development testing (depends on `make database`) |
| `make ssh`        | SSH into running wordpress container (depends on `make wordpress`)                |


## Support
<!------------------------------------------------------->

Submit an [issue](https://github.com/jamrizzi/readme/issues/new)


## Buy Me Coffee
<!------------------------------------------------------->

A ridiculous amount of coffee was consumed in the process of building this project.

[Add some fuel](https://jamrizzi.com/#!/buy-me-coffee) if you'd like to keep me going!


## Contributing
<!------------------------------------------------------->

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D


## License
<!------------------------------------------------------->

[GPL-3.0 License](https://github.com/jamrizzi/readme/blob/master/LICENSE)

[Jam Risser](https://jamrizzi.com) &copy; 2017


## Credits
<!------------------------------------------------------->

* [Jam Risser](https://jamrizzi.com) - Author
* Loosely based on the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)


## Changelog
<!------------------------------------------------------->

0.0.1 (2017-04-28)
* Beta release
