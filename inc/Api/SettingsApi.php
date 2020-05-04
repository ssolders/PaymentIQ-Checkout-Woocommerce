<?php

/**
* @package PaymentIQ Checkout Plugin for Woocommerce
*/

namespace Inc\Api;

class SettingsApi {
  /* Pages that show in the admin left menu  */
  public $adminPages = array();

  /* Subpages of admin pages (Show in left menu when an admin page is selected)  */
  public $adminSubPages = array();
  
  /* Settings - a grouping of sections */
  public $settings = array();
  
  /* Sections - a grouping of fields */
  public $sections = array();
  
  /* Fields - actual form inputs */
  public $fields = array();

  public function register () {
    /* Setup the admin pages we've defined  */
    if ( ! empty($this->adminPages) ) {
      add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
    }
    
    /* Setup the inputs for the admin pages  */
    if ( ! empty($this->settings) ) {
      add_action( 'admin_init', array( $this, 'registerCustomFields' ) );
    }
  }

  public function addPages( array $pages ) {
    $this->adminPages = $pages;
    return $this;
  }
  
  // Title defaults it to the passed in Title, otherwise uses the defined $page.menu_title
  public function withSubPage( string $title = null ) {
    if ( empty($this->adminPages) ) {
      return $this; // Bow out ealy. Allow the chaining to continue. Returning this instance of the class
    }

    $adminPage = $this->adminPages[0];

    $subPage = [
      [
        'parent_slug' => $adminPage['menu_slug'],
        'page_title' => $adminPage['page_title'],
        'menu_title' => ($title) ? $title : $adminPage['menu_title'],
        'capability' => $adminPage['capability'],
        'menu_slug' => $adminPage['menu_slug'],
        'callback' => $adminPage['callback'],
      ]
    ];

    $this->adminSubPages = $subPage;
    return $this;
  }

  public function addSubPages ( array $pages ) {
    $this->adminSubPages = array_merge( $this->adminSubPages, $pages );
    return $this;
  }

  public function addAdminMenu () {
    foreach( $this->adminPages as $page ) {
      add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
    }
    foreach( $this->adminSubPages as $page ) {
      add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
    }
  }

  /* Set the configured settings from the calling class  */
  public function setSettings( array $settings ) {
    $this->settings = $settings;
    return $this;
  }
  
  /* Set the configured sections from the calling class  */
  public function setSections( array $sections ) {
    $this->sections = $sections;
    return $this;
  }
  
  /* Set the configured fields from the calling class  */
  public function setFields( array $fields ) {
    $this->fields = $fields;
    return $this;
  }

  /* Wordpress have a format for fields to be grouped by setting -> section -> fields
     This part is pretty messy but needed... 
     Loop the settings, sections and fields located in the getters ($settings, $section and $fields)
  */

  public function registerCustomFields () {

    foreach( $this->settings as $setting ) {
      // register setting
      register_setting(
        $setting["option_group"],
        $setting["option_name"],
        ( isset( $setting["callback"] ) ? $setting["callback"] : '' )
      );
    }

    foreach( $this->sections as $section ) {
      // add settings section
      add_settings_section (
        $section["id"],
        $section["title"],
        ( isset( $section["callback"] ) ? $section["callback"] : '' ),
        $section["page"]
      );
    }

    foreach( $this->fields as $field ) {
      //add settings field
      add_settings_field(
        $field["id"],
        $field["title"],
        ( isset( $field["callback"] ) ? $field["callback"] : '' ),
        $field["page"],
        $field["section"], 
        ( isset( $field["args"] ) ? $field["args"] : '' )
      );
    }
  }
}