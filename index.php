<?php

/**
 * Laravel Application Entry Point Redirect
 * 
 * This file redirects to the actual Laravel entry point in public/index.php
 * Used for deployment environments that serve from root directory
 */

require_once __DIR__.'/public/index.php';
