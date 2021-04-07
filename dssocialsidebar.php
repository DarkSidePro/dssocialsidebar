<?php
/**
* 2007-2019 PrestaShop.
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

class DssocialSidebar extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'dssocialsidebar';
        $this->tab = 'social_networks';
        $this->version = '1.0.0';
        $this->author = 'Dark-Side.pro';
        $this->need_instance = 1;

        /*
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DS: Social Sidebar');
        $this->description = $this->l('This module add social sidebar in your store');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    private function createTab()
    {
        $response = true;
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = 'AdminDarkSideMenu';
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = 'Dark-Side.pro';
            }
            $parentTab->id_parent = 0;
            $parentTab->module = '';
            $response &= $parentTab->add();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = 'AdminDarkSideMenuSecond';
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = 'Dark-Side Config';
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = '';
            $response &= $parentTab_2->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdministratorDs_socialSidebar';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = 'Social Sidebar';
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    private function tabRem()
    {
        $id_tab = Tab::getIdFromClassName('AdministratorDs_socialSidebar');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $tabCount_2 = Tab::getNbTabs($parentTab_2ID);
            if ($tabCount_2 == 0) {
                $parentTab_2 = new Tab($parentTab_2ID);
                $parentTab_2->delete();
            }
        }
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $tabCount = Tab::getNbTabs($parentTabID);
            if ($tabCount == 0) {
                $parentTab = new Tab($parentTabID);
                $parentTab->delete();
            }
        }

        return true;
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update.
     */
    public function install()
    {
        $this->createTab();

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        $this->tabRem();

        return parent::uninstall();
    }

    /**
     * Load the configuration form.
     */
    public function getContent()
    {
        /*
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitSocialsidebarModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSocialsidebarModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Move on hover'),
                        'name' => 'SOCIALSIDEBAR_HOVER',
                        'is_bool' => true,
                        'desc' => $this->l('Disable hover animations'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Hide on mobile'),
                        'name' => 'SOCIALSIDEBAR_MOBILE',
                        'is_bool' => true,
                        'desc' => $this->l('Disable on mobile'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'radio',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Select sidebar position'),
                        'name' => 'SOCIALSIDEBAR_POSITION',
                        'label' => $this->l('Sidebar Position'),
                        'values' => array(
                            array(
                                'id' => 'left',
                                'value' => null,
                                'label' => $this->getTranslator()->trans('Left', array(), 'Modules.Socialsidebar.Admin'),
                            ),
                            array(
                                'id' => 'right',
                                'value' => true,
                                'label' => $this->getTranslator()->trans('Right', array(), 'Modules.Socialsidebar.Admin'),
                            ),
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Facebook profile address '),
                        'name' => 'SOCIALSIDEBAR_FACEBOOK',
                        'label' => $this->l('Facebook'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Instagram profile address '),
                        'name' => 'SOCIALSIDEBAR_INSTAGRAM',
                        'label' => $this->l('Instagram'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Youtube chanel address'),
                        'name' => 'SOCIALSIDEBAR_YOUTUBE',
                        'label' => $this->l('Youtube'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Twitter profile addres'),
                        'name' => 'SOCIALSIDEBAR_TWITTER',
                        'label' => $this->l('Twitter'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Tumbrl profile addres'),
                        'name' => 'SOCIALSIDEBAR_TUMBRL',
                        'label' => $this->l('Tumbrl'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a Printerest profile addres'),
                        'name' => 'SOCIALSIDEBAR_PRINTEREST',
                        'label' => $this->l('PRINTEREST'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a LinkedIn profile addres'),
                        'name' => 'SOCIALSIDEBAR_LINKEDIN',
                        'label' => $this->l('Linkedin'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'SOCIALSIDEBAR_HOVER' => Configuration::get('SOCIALSIDEBAR_HOVER', true),
            'SOCIALSIDEBAR_MOBILE' => Configuration::get('SOCIALSIDEBAR_MOBILE', true),
            'SOCIALSIDEBAR_FACEBOOK' => Configuration::get('SOCIALSIDEBAR_FACEBOOK', null),
            'SOCIALSIDEBAR_INSTAGRAM' => Configuration::get('SOCIALSIDEBAR_INSTAGRAM', null),
            'SOCIALSIDEBAR_YOUTUBE' => Configuration::get('SOCIALSIDEBAR_YOUTUBE', null),
            'SOCIALSIDEBAR_TWITTER' => Configuration::get('SOCIALSIDEBAR_TWITTER', null),
            'SOCIALSIDEBAR_TUMBRL' => Configuration::get('SOCIALSIDEBAR_TUMBRL', null),
            'SOCIALSIDEBAR_PRINTEREST' => Configuration::get('SOCIALSIDEBAR_PRINTEREST', null),
            'SOCIALSIDEBAR_LINKEDIN' => Configuration::get('SOCIALSIDEBAR_LINKEDIN', null),
            'SOCIALSIDEBAR_POSITION' => Configuration::get('SOCIALSIDEBAR_POSITION', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }

        return $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Dssocialsidebar.Success'));
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
    }

    public function hookDisplayFooter()
    {
        $facebook = Configuration::get('SOCIALSIDEBAR_FACEBOOK');
        $instagram = Configuration::get('SOCIALSIDEBAR_INSTAGRAM');
        $printerest = Configuration::get('SOCIALSIDEBAR_PRINTEREST');
        $youtube = Configuration::get('SOCIALSIDEBAR_YOUTUBE');
        $hover = Configuration::get('SOCIALSIDEBAR_HOVER');
        $mobile = Configuration::get('SOCIALSIDEBAR_MOBILE');
        $twitter = Configuration::get('SOCIALSIDEBAR_TWITTER');
        $tumbrl = Configuration::get('SOCIALSIDEBAR_TUMBRL');
        $linkedin = Configuration::get('SOCIALSIDEBAR_LINKEDIN');
        $isMobile = Context::getContext()->isMobile();
        $position = Configuration::get('SOCIALSIDEBAR_POSITION');

        $this->context->smarty->assign('socialsidebar', array(
            'facebook' => $facebook,
            'instagram' => $instagram,
            'printerest' => $printerest,
            'youtube' => $youtube,
            'hover' => $hover,
            'twitter' => $twitter,
            'linkedin' => $linkedin,
            'tumbrl' => $tumbrl,
            'position' => $position,
        ));

        $output = $this->display(__FILE__, 'views/templates/hook/socialsidebar.tpl');

        if ($mobile == false) {
            return $output;
        }
    }
}
