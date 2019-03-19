<?php

namespace api\oauth2\repositories;

use shop\extra\oauth2server\models\OauthClients;
use shop\repositories\NotFoundException;
use \OAuth2\Storage\ClientInterface;
use \OAuth2\Storage\ClientCredentialsInterface;

class ClientRepository implements ClientInterface, ClientCredentialsInterface
{
    /**
     * @property OauthClients $entety
     */
    private $entity;

    /**
     * @property ActiveQueryInterface $query
     */
    private $query;

    public function __construct(OauthClients $entity)
    {
        $this->entity = $entity;
        $this->query = $this->entity::find();
    }

    /**
     * Get client details corresponding client_id.
     *
     * OAuth says we should store request URIs for each registered client.
     * Implement this function to grab the stored URI for a given client id.
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return array
     *               Client details. The only mandatory key in the array is "redirect_uri".
     *               This function MUST return FALSE if the given client does not exist or is
     *               invalid. "redirect_uri" can be space-delimited to allow for multiple valid uris.
     *               <code>
     *               return array(
     *               "redirect_uri" => REDIRECT_URI,      // REQUIRED redirect_uri registered for the client
     *               "client_id"    => CLIENT_ID,         // OPTIONAL the client id
     *               "grant_types"  => GRANT_TYPES,       // OPTIONAL an array of restricted grant types
     *               "user_id"      => USER_ID,           // OPTIONAL the user identifier associated with this client
     *               "scope"        => SCOPE,             // OPTIONAL the scopes allowed for this client
     *               );
     *               </code>
     *
     * @ingroup oauth2_section_4
     */
    public function getClientDetails($clientId): array
    {
        $client = $this->get($clientId);

        $details = \array_filter([
            'redirect_uri' => $client->redirect_uri,
            'client_id' => (int)$client->client_id,
            'grant_types' => \explode(' ', $client->grant_types),
            'user_id' => $client->user_id,
            'scope' => $client->scope,
        ]);

        return $details;
    }

    /**
     * Get the scope associated with this client
     *
     * @return
     * STRING the space-delineated scope list for the specified client_id
     */
    public function getClientScope($clientId): ?string
    {
        $client = $this->get($clientId);

        return $client->scope ?? null;
    }

    /**
     * Check restricted grant types of corresponding client identifier.
     *
     * If you want to restrict clients to certain grant types, override this
     * function.
     *
     * @param $client_id
     * Client identifier to be check with.
     * @param $grant_type
     * Grant type to be check with
     *
     * @return
     * TRUE if the grant type is supported by this client identifier, and
     * FALSE if it isn't.
     *
     * @ingroup oauth2_section_4
     */
    public function checkRestrictedGrantType($clientId, $grantType): bool
    {
        $client = $this->get($clientId);
        $grantTypes = \explode(' ', $client->grant_types);

        return in_array($grantType, $grantTypes);
    }

    /**
     * Make sure that the client credentials is valid.
     *
     * @param $client_id
     * Client identifier to be check with.
     * @param $client_secret
     * (optional) If a secret is required, check that they've given the right one.
     *
     * @return
     * TRUE if the client credentials are valid, and MUST return FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-3.1
     *
     * @ingroup oauth2_section_3
     */
    public function checkClientCredentials($clientId, $clientSecret = null): bool
    {
        $client = $this->get($clientId);

        return $clientSecret === $client->client_secret;
    }

    /**
     * Determine if the client is a "public" client, and therefore
     * does not require passing credentials for certain grant types
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return
     * TRUE if the client is public, and FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-2.3
     * @see https://github.com/bshaffer/oauth2-server-php/issues/257
     *
     * @ingroup oauth2_section_2
     */
    public function isPublicClient($clientIid): bool
    {
        return false;
    }

    /**
     * get method get client entity
     *
     * @param string $clientId
     * @return OauthClients
     */
    private function get($clientId): OauthClients
    {
        if (!$client = $this->query->andWhere(['client_id' => $clientId])->one()) {
            throw new NotFoundException('Client not fount.');
        }

        return $client;
    }
}
