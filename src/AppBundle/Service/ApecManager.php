<?php
/**
 * Created by PhpStorm.
 * User: ck
 * Date: 01/12/17
 * Time: 20:50
 */

namespace AppBundle\Service;

use GuzzleHttp\Cookie\FileCookieJar;

class ApecManager {

    private $username = null;
    private $password = null;
    private $cookieFilePath = null;

    public function __construct($username, $password, $cookieFilePath)
    {
        $this->username = $username;
        $this->password = $password;
        $this->cookieFilePath = $cookieFilePath;
    }

    /**
     * S'authentifie aupre de l'APEC
     * @return bool
     * @throws \Exception
     */
    public function connect() {
        // initialisation
        $cookieJar = new FileCookieJar($this->cookieFilePath, TRUE);
        $client = new \GuzzleHttp\Client(['cookies' => $cookieJar]);

        // connection
        try {
            $response = $client->post('https://recruteurs.apec.fr/cms/login',
                [
                    'form_params' => [
                        'source' => 'loginApecInterlocuteur',
                        'username' => $this->username,
                        'password' => $this->password,
                    ],
                    'timeout' => 5
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception ('Erreur de connection avec APEC', 502);
        }

        // retour
        unset ($cookieJar, $client);
        return true;
    }

    public function getNbrResults($keyword, $apecRegionId, $reconnectIfError=true ) {
        // initialisation
        {
            $url = 'https://recruteurs.apec.fr/cms/webservices/rechercheProfilCadre';
            $params = [
                'criteres' => [
                    'motsCles' => $keyword,
                    'lieux' => [$apecRegionId],
                    'lieuxFacetable' => true,
                    'fonctions' => [],
                    'fonctionsFacetable' => true,
                    'secteursActivite' => [],
                    'secteursActiviteFacetable' => true
                ],
                'limit' => 1,
                'offset' => 0
            ];
            $cookieJar = new FileCookieJar($this->cookieFilePath, TRUE);
            $client = new \GuzzleHttp\Client(['cookies' => $cookieJar]);
        }

        // dialogue avec l'api
        try {
            $responseGuzzle = $client->post($url, ['json' => $params]);
        } catch (\Exception $e) {
            if ($e->getCode() == 401 && $reconnectIfError === true) {
                // essai de reconnection + renvoi de la requete
                $this->connect();
                $fctName = __FUNCTION__;
                return $this->$fctName($keyword, $apecRegionId, false);
            }
            return (0);
        }
        $responseJson = json_decode($responseGuzzle->getBody(), true);
        $res = $responseJson['totalCount'];

        // retour
        unset($url, $params, $cookieJar, $client, $responseGuzzle, $responseJson);
        return ($res);
    }

}