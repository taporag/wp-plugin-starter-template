<?php

namespace {NAMESPACE}\Includes;

/**
 * Class Loader
 *
 * The `Loader` class is an abstract base class that provides a common structure for loading dependencies 
 * and initializing components within the Accessly plugin. Classes extending `Loader` must implement 
 * specific functionality for loading their own dependencies and performing initialization tasks.
 *
 * @package {NAMESPACE}\Includes
 */
abstract class Loader {

    /**
     * Constructor for the Loader class.
     *
     * Initializes the loader by calling the `load_dependencies()` method to include necessary files and 
     * the `init()` method to set up the component. The actual implementation of these methods is deferred 
     * to the classes extending this abstract class.
     */
    public function __construct() {
        $this->load_dependencies();
        $this->init();
    }

    /**
     * Load all required dependencies.
     *
     * This method is responsible for loading all files, classes, or other resources needed by the component.
     * It must be implemented by the extending class to specify what dependencies are necessary and how they 
     * should be loaded.
     *
     * @return void
     */
    abstract protected function load_dependencies();

    /**
     * Initialize the component.
     *
     * This method is intended to set up the component by adding hooks, filters, or any initialization logic 
     * required to make the component functional. It must be implemented by the extending class.
     *
     * @return void
     */
    abstract public function init();
}
