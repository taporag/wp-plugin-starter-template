const templateConfig = {
  name: "Template config",
  mainFileName: "main.php",
  copyFiles: {
    includes: ["*"],
    excludes: [
     "composer.lock", "assets/admin/inc", "assets/admin/editor.js",
     "functions", "utils", "views"
    ],
    conditional: [
      {
        key: 'enableFunctional', // Questions name
        dir: 'functions'
      },
      {
        key: 'enableUtils',
        dir: 'utils',
      },
      {
        key: 'enableSettings',
        dir: 'views',
      }
    ]
  },
  placeholderAndQuestionsMap: [
    {
      type: "input",
      name: "name",
      message: "Plugin Name (required): ",
      validate: (input) => (input ? true : "Plugin Name is required."),
      placeholder: "__NAME__",
    },
    {
      type: "input",
      name: "slug",
      message: "Plugin Slug: ",
      default: (answers) => answers.name,
      slugify: true,
      placeholder: "__SLUG__",
    },
    {
      type: "input",
      name: "namespace",
      message: "Namespace (optional, defaults to plugin name in PascalCase): ",
      default: (answers) => answers.name,
      pascal: true,
      placeholder: "__NAMESPACE__",
    },
    {
      type: "input",
      name: "textDomain",
      message: "Plugin text domain (optional, defaults to plugin name): ",
      default: (answers) => answers.name,
      slugify: true,
      placeholder: "__TEXT_DOMAIN__",
    },
    {
      type: "input",
      name: "author",
      message: "Author (optional): ",
      placeholder: "__AUTHOR__",
    },
    {
      type: "input",
      name: "pluginVersion",
      message: "Version (optional, default: 0.0.1): ",
      default: "0.0.1",
      placeholder: "__VERSION__",
    },
    {
      type: "input",
      name: "description",
      message: "Description (optional): ",
      placeholder: "__DESCRIPTION__",
    },
    {
      type: "input",
      name: "pluginUrl",
      message: "Plugin URL (optional): ",
      placeholder: "__PLUGIN_URL__",
    },
    {
      type: "input",
      name: "authorUrl",
      message: "Author URL (optional): ",
      placeholder: "__AUTHOR_URL__",
    },
    {
      type: "confirm",
      name: "enableFunctional",
      message: "Enable functional programming? (default: no): ",
      default: false,
    },
    {
      type: "confirm",
      name: "enableUtils",
      message: "Enable utils? (default: no): ",
      default: false
    },
    {
      type: "confirm",
      name: "enableSetting",
      message: "Enable plugin setting? (default: no): ",
      default: false
    },
  ]
};

module.exports = templateConfig;
