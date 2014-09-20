'use strict';
var util = require('util'),
    path = require('path'),
    yeoman = require('yeoman-generator'),
    yosay = require('yosay'),
    _ = require('lodash'),
    _s = require('underscore.string'),
    pluralize = require('pluralize'),
    asciify = require('asciify');

var SlimangularGenerator = module.exports = function SlimangularGenerator(args, options, config) {
  yeoman.generators.Base.apply(this, arguments);

  this.on('end', function () {
    this.installDependencies({ skipInstall: options['skip-install'] });

    if (this.generatorConfig.databaseType === 'sqlite') {
      this.spawnCommand('sqlite3', ['-line', this.generatorConfig.databaseName, 'select 1']);
    }

    if (this.generatorConfig.composer) {
      this.spawnCommand('composer', ['update']);
    }
  });

  this.pkg = JSON.parse(this.readFileAsString(path.join(__dirname, '../package.json')));
};

util.inherits(SlimangularGenerator, yeoman.generators.Base);

SlimangularGenerator.prototype.welcome = function welcome() {
  if (!this.options['skip-welcome-message']) {
    this.log(yosay());
  }
};

SlimangularGenerator.prototype.askFor = function askFor() {

  var cb = this.async();

  var prompts = [{
    type: 'input',
    name: 'baseName',
    message: 'What is the name of your application?',
    default: 'myApp'
  },
  {
    type: 'list',
    name: 'databaseType',
    message: 'Which database would you like to use?',
    choices: ['MySQL', 'SQLite', 'PostgreSQL'],
    default: 'MySQL'
  },
  {
    type: 'input',
    name: 'hostName',
    message: 'What is your host name?',
    default: 'localhost'
  },
  {
    type: 'input',
    name: 'databaseName',
    message: 'What is your database name?',
    default: 'example'
  },
  {
    type: 'input',
    name: 'userName',
    message: 'What is your database user name?',
    default: 'username'
  },
  {
    type: 'input',
    name: 'password',
    message: 'What is your database password?',
    default: 'password'
  },
  {
    type: 'confirm',
    name: 'composer',
    message: 'Is PHP composer installed globally (so that "composer update" can be run automatically)?',
    default: false
  }];

  this.prompt(prompts, function (props) {
    this.baseName = props.baseName;
    this.databaseType = props.databaseType == 'PostgreSQL' ? 'pgsql' : props.databaseType.toLowerCase();
    this.hostName = props.hostName;
    if (props.databaseType == 'SQLite' && props.databaseName.indexOf('/') != 0) {
      this.databaseName = props.databaseName + '.sqlite';
    } else {
      this.databaseName = props.databaseName;
    }
    this.userName = props.userName;
    this.password = props.password;
    this.composer = props.composer;

    cb();
  }.bind(this));
};

SlimangularGenerator.prototype.app = function app() {

  this.entities = [];
  this.generatorConfig = {
    "baseName": this.baseName,
    "databaseType": this.databaseType,
    "hostName": this.hostName,
    "databaseName": this.databaseName,
    "userName": this.userName,
    "password": this.password,
    "entities": this.entities,
    "composer": this.composer,
  };
  this.generatorConfigStr = JSON.stringify(this.generatorConfig, null, '\t');

  this.template('_generator.json', 'generator.json');
  this.template('_package.json', 'package.json');
  this.template('_bower.json', 'bower.json');
  this.template('bowerrc', '.bowerrc');
  this.template('Gruntfile.js', 'Gruntfile.js');
  this.copy('gitignore', '.gitignore');

  var serverDir = 'server/'
  var configDir = serverDir + 'config/'
  var modelsDir = serverDir + 'models/'
  var migrationsDir = configDir + 'migrations/'
  var publicDir = 'public/'
  var vendorDir = 'vendor/'
  this.mkdir(serverDir);
  this.mkdir(configDir);
  this.mkdir(modelsDir);
  this.mkdir(migrationsDir);
  this.mkdir(publicDir);
  this.mkdir(vendorDir);

  this.template('_composer.json', 'composer.json');
  this.template('server/config/_phpmig.php', configDir + 'phpmig.php');
  this.template('server/config/_app.php', configDir + 'app.php');
  this.template('server/_app.php', serverDir + 'app.php');
  this.template('server/_validator.php', serverDir + 'validator.php');
  this.template('public/_index.php', publicDir + 'index.php');
  this.copy('public/htaccess', publicDir + '.htaccess');

  var publicCssDir = publicDir + 'css/';
  var publicJsDir = publicDir + 'js/';
  var publicViewDir = publicDir + 'views/';
  this.mkdir(publicCssDir);
  this.mkdir(publicJsDir);
  this.mkdir(publicViewDir);
  this.template('public/_index.html', publicDir + 'index.html');
  this.copy('public/css/app.css', publicCssDir + 'app.css');
  this.template('public/js/_app.js', publicJsDir + 'app.js');
  this.template('public/js/home/_home-controller.js', publicJsDir + 'home/home-controller.js');
  this.template('public/views/home/_home.html', publicViewDir + 'home/home.html');
};

SlimangularGenerator.prototype.projectfiles = function projectfiles() {
  this.copy('editorconfig', '.editorconfig');
  this.copy('jshintrc', '.jshintrc');
};
