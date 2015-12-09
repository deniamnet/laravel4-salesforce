<?php

// ----------------------------------------------------------------------------

namespace Deniamnet\Laravel4Salesforce;

// ----------------------------------------------------------------------------

use Config;
use Exception;
use Illuminate\Config\Repository;
use Deniamnet\ForceDotComToolkitForPhp\SforceEnterpriseClient as Client;
use Deniamnet\ForceDotComToolkitForPhp\LoginScopeHeader as LoginScopeHeader;

// ----------------------------------------------------------------------------

class Salesforce
{

    /**
     * Prepare variables.
     */
    public $sf_client;
    public $sf_client_scope_header;

    /**
     * Constructor.
     */
    public function __construct($custom_wsdl_path = null, Repository $configExternal)
    {
        try {
            /**
             * Prepare the Client.
             */
            $this->sf_client = new Client();

            /**
             * Get default WSDL file.
             */
            $wsdl = trim($configExternal->get('laravel4-salesforce::wsdl'));
            if (empty($wsdl)) {
                $wsdl = __DIR__ . '/Wsdl/enterprise.wsdl.xml';
            }

            /**
             * Get default WSDL file from app config.
             */
            $default_app_wsdl = trim(Config::get('salesforce.wsdl'));
            if ( ! empty($default_app_wsdl)) {
                $wsdl = $default_app_wsdl;
            }

            /**
             * Get custom WSDL.
             */
            $custom_wsdl_path = trim($custom_wsdl_path);
            if ( ! empty($custom_wsdl_path)) {
                $wsdl = $custom_wsdl_path;
            }

            /**
             * Check WSDL file.
             */
            if ( ! is_file($wsdl)) {
                throw new Exception("Please load a valid WSDL file.");
            }

            /**
             * Create connection.
             */
            $this->sf_client->createConnection($wsdl);

            /**
             * Return connection.
             */
            return $this;
        } catch (Exception $e) {
            throw new Exception("Exception in Salesforce constructor: " . $e->getMessage() . "\n\n" . $e->getTraceAsString());
        }
    }

    /**
     * Custom: Login as a Portal User.
     */
    public function loginAsPortalUser($username = null, $password = null)
    {
        /**
         * Prepare and check credentials.
         */
        $username = trim($username);
        $password = trim($password);
        if (empty($username)) {
            throw new Exception("Empty username.");
        }
        if (empty($password)) {
            throw new Exception("Empty password.");
        }

        /**
         * Set login scope header.
         */
        $this->sf_client_scope_header = new LoginScopeHeader(
            Config::get('salesforce.organization_id'),
            Config::get('salesforce.portal_id')
        );
        $this->setLoginScopeHeader($this->sf_client_scope_header);

        /**
         * Login.
         */
        return $this->login($username, $password);
    }

    /**
     * Custom: Login as a Super User.
     */
    public function loginAsSuperUser()
    {
        /**
         * Set login scope header.
         */
        $this->sf_client_scope_header = new LoginScopeHeader(
            null,
            null
        );
        $this->setLoginScopeHeader($this->sf_client_scope_header);

        /**
         * Login.
         */
        return $this->login(
            Config::get('salesforce.username'),
            Config::get('salesforce.password') . Config::get('salesforce.token')
        );
    }

    /**
     * Enterprise Client: Create.
     */
    public function create($sObjects, $type)
    {
        return $this->sf_client->create($sObjects, $type);
    }

    /**
     * Enterprise Client: Merge.
     */
    public function merge($mergeRequest, $type)
    {
        return $this->sf_client->merge($mergeRequest, $type);
    }

    /**
     * Enterprise Client: Update.
     */
    public function update($sObjects, $type, $assignment_header = null, $mru_header = null)
    {
        return $this->sf_client->update($sObjects, $type, $assignment_header, $mru_header);
    }

    /**
     * Enterprise Client: Upsert.
     */
    public function upsert($ext_Id, $sObjects, $type = 'Contact')
    {
        return $this->sf_client->upsert($ext_Id, $sObjects, $type);
    }

    /*
     * Base Client: Convert lead.
     */
    public function convertLead($leadConverts)
    {
        return $this->sf_client->convertLead($leadConverts);
    }

    /*
     * Base Client: Create connection.
     */
    public function createConnection($wsdl, $proxy = null, $soap_options = array())
    {
        return $this->sf_client->createConnection($wsdl, $proxy, $soap_options);
    }

    /*
     * Base Client: Delete.
     */
    public function delete($ids)
    {
        return $this->sf_client->delete($ids);
    }

    /*
     * Base Client: Describe data category groups.
     */
    public function describeDataCategoryGroups($sObjectType)
    {
        return $this->sf_client->describeDataCategoryGroups($sObjectType);
    }

    /*
     * Base Client: Describe data category groups structures.
     */
    public function describeDataCategoryGroupStructures(array $pairs, $topCategoriesOnly)
    {
        return $this->sf_client->describeDataCategoryGroupStructures($pairs, $topCategoriesOnly);
    }

    /*
     * Base Client: Describe global.
     */
    public function describeGlobal()
    {
        return $this->sf_client->describeGlobal();
    }

    /*
     * Base Client: Describe layout.
     */
    public function describeLayout($type, array $recordTypeIds = null)
    {
        return $this->sf_client->describeLayout($type, $recordTypeIds);
    }

    /*
     * Base Client: Describe SObject.
     */
    public function describeSObject($type)
    {
        return $this->sf_client->describeSObject($type);
    }

    /*
     * Base Client: Describe SObjects.
     */
    public function describeSObjects($arrayOfTypes)
    {
        return $this->sf_client->describeSObjects($arrayOfTypes);
    }

    /*
     * Base Client: Describe tabs.
     */
    public function describeTabs()
    {
        return $this->sf_client->describeTabs();
    }

    /*
     * Base Client: Empty Recycle Bin.
     */
    public function emptyRecycleBin($ids)
    {
        return $this->sf_client->emptyRecycleBin($ids);
    }

    /*
     * Base Client: Get connection.
     */
    public function getConnection()
    {
        return $this->sf_client->getConnection();
    }

    /*
     * Base Client: Get deleted.
     */
    public function getDeleted($type, $startDate, $endDate)
    {
        return $this->sf_client->getDeleted($type, $startDate, $endDate);
    }

    /*
     * Base Client: Get functions.
     */
    public function getFunctions()
    {
        return $this->sf_client->getFunctions();
    }

    /*
     * Base Client: Get last request.
     */
    public function getLastRequest()
    {
        return $this->sf_client->getLastRequest();
    }

    /*
     * Base Client: Get last request headers.
     */
    public function getLastRequestHeaders()
    {
        return $this->sf_client->getLastRequestHeaders();
    }

    /*
     * Base Client: Get last response.
     */
    public function getLastResponse()
    {
        return $this->sf_client->getLastResponse();
    }

    /*
     * Base Client: Get last response headers.
     */
    public function getLastResponseHeaders()
    {
        return $this->sf_client->getLastResponseHeaders();
    }

    /*
     * Base Client: Get location.
     */
    public function getLocation()
    {
        return $this->sf_client->getLocation();
    }

    /*
     * Base Client: Get namespace.
     */
    public function getNamespace()
    {
        return $this->sf_client->getNamespace();
    }

    /*
     * Base Client: Get server timestamp.
     */
    public function getServerTimestamp()
    {
        return $this->sf_client->getServerTimestamp();
    }

    /*
     * Base Client: Get session ID.
     */
    public function getSessionId()
    {
        return $this->sf_client->getSessionId();
    }

    /*
     * Base Client: Get types.
     */
    public function getTypes()
    {
        return $this->sf_client->getTypes();
    }

    /*
     * Base Client: Get updated.
     */
    public function getUpdated($type, $startDate, $endDate)
    {
        return $this->sf_client->getUpdated($type, $startDate, $endDate);
    }

    /*
     * Base Client: Get user info.
     */
    public function getUserInfo()
    {
        return $this->sf_client->getUserInfo();
    }

    /*
     * Base Client: Get Invalidate sessions.
     */
    public function invalidateSessions()
    {
        return $this->sf_client->invalidateSessions();
    }

    /*
     * Base Client: Login.
     */
    public function login($username, $password)
    {
        return $this->sf_client->login($username, $password);
    }

    /*
     * Base Client: Logout.
     */
    public function logout()
    {
        return $this->sf_client->logout();
    }

    /*
     * Base Client: Print debug info.
     */
    public function printDebugInfo()
    {
        return $this->sf_client->printDebugInfo();
    }

    /*
     * Base Client: Process submit request.
     */
    public function processSubmitRequest($processRequestArray)
    {
        return $this->sf_client->processSubmitRequest($processRequestArray);
    }

    /*
     * Base Client: Process work item request.
     */
    public function processWorkitemRequest($processRequestArray)
    {
        return $this->sf_client->processWorkitemRequest($processRequestArray);
    }

    /*
     * Base Client: Query.
     */
    public function query($query)
    {
        return $this->sf_client->query($query);
    }

    /*
     * Base Client: Query all.
     */
    public function queryAll($query, $queryOptions = null)
    {
        return $this->sf_client->queryAll($query, $queryOptions);
    }

    /*
     * Base Client: Query more.
     */
    public function queryMore($queryLocator)
    {
        return $this->sf_client->queryMore($queryLocator);
    }

    /*
     * Base Client: Reset password.
     */
    public function resetPassword($userId)
    {
        return $this->sf_client->resetPassword($userId);
    }

    /*
     * Base Client: Retrieve.
     */
    public function retrieve($fieldList, $sObjectType, $ids)
    {
        return $this->sf_client->retrieve($fieldList, $sObjectType, $ids);
    }

    /*
     * Base Client: Search.
     */
    public function search($searchString)
    {
        return $this->sf_client->search($searchString);
    }

    /*
     * Base Client: Send mass e-mail.
     */
    public function sendMassEmail($request)
    {
        return $this->sf_client->sendMassEmail($request);
    }

    /*
     * Base Client: Send single e-mail.
     */
    public function sendSingleEmail($request)
    {
        return $this->sf_client->sendSingleEmail($request);
    }

    /*
     * Base Client: Set allow field truncation header.
     */
    public function setAllowFieldTruncationHeader($header)
    {
        return $this->sf_client->setAllowFieldTruncationHeader($header);
    }

    /*
     * Base Client: Set assignment rule header.
     */
    public function setAssignmentRuleHeader($header)
    {
        return $this->sf_client->setAssignmentRuleHeader($header);
    }

    /*
     * Base Client: Set call options.
     */
    public function setCallOptions($header)
    {
        return $this->sf_client->setCallOptions($header);
    }

    /*
     * Base Client: Set e-mail header.
     */
    public function setEmailHeader($header)
    {
        return $this->sf_client->setEmailHeader($header);
    }

    /*
     * Base Client: Set endpoint.
     */
    public function setEndpoint($location)
    {
        return $this->sf_client->setEndpoint($location);
    }

    /*
     * Base Client: Set locale options.
     */
    public function setLocaleOptions($header)
    {
        return $this->sf_client->setLocaleOptions($header);
    }

    /*
     * Base Client: Set login scope header.
     */
    public function setLoginScopeHeader($header)
    {
        return $this->sf_client->setLoginScopeHeader($header);
    }

    /*
     * Base Client: Set MRU header.
     */
    public function setMruHeader($header)
    {
        return $this->sf_client->setMruHeader($header);
    }

    /*
     * Base Client: Set package version header.
     */
    public function setPackageVersionHeader($header)
    {
        return $this->sf_client->setPackageVersionHeader($header);
    }

    /*
     * Base Client: Set password.
     */
    public function setPassword($userId, $password)
    {
        return $this->sf_client->setPassword($userId, $password);
    }

    /*
     * Base Client: Set query options.
     */
    public function setQueryOptions($header)
    {
        return $this->sf_client->setQueryOptions($header);
    }

    /*
     * Base Client: Set session header.
     */
    public function setSessionHeader($id)
    {
        return $this->sf_client->setSessionHeader($id);
    }

    /*
     * Base Client: Set user territory delete header.
     */
    public function setUserTerritoryDeleteHeader($header)
    {
        return $this->sf_client->setUserTerritoryDeleteHeader($header);
    }

    /*
     * Base Client: Undelete.
     */
    public function undelete($ids)
    {
        return $this->sf_client->undelete($ids);
    }

    /*
     * Debugging: Dump.
     */
    public function dump()
    {
        $str = print_r($this, true);
        // $str .= print_r($this->sf_client , true);
    }

}

// ----------------------------------------------------------------------------
