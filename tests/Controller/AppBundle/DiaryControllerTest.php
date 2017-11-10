<?php

namespace Tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class DiaryControllerTest extends WebTestCase {

	private $client = null;

	public function setUp() {
		$this->client = static::createClient();
		// Création du client HTTP qui sera initialisé au début de chaque fonction de test
	}

	public function tearDown() {
		$this->client = null;
		// On vide la variable $client à la fin de chaque fonction de test
	}


	// -------------------- TESTS HTTP STATUS CODE -------------------- //

	public function testHomepageIsUp() {
		$this->client->request('GET', '/');
		// Envois de la requête en se servant du client initialisé dans setUp()
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		// Je verifie que le contenu de la réponse HTTP renvoit bien un code 200 (= okay)
	}
	
	public function testNewRecord() {
		$this->client->request('GET', '/diary/add-new-record');
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
	}
	
	public function testDeleteRecord() {
		$this->client->request('GET', '/diary/record');
		$this->assertSame(302, $this->client->getResponse()->getStatusCode());
	}
	
	public function testDiaryList() {
		$this->client->request('GET', '/diary/list');
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
	}


	// -------------------- TESTS WITH CRALWER -------------------- //

	public function testHomepage() {
		$crawler = $this->client->request('GET', '/');
		// Je recupere les infos de la page que je mets dans la variable $crawler

		$this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur FoodDiary !")')->count());
		// Je compte combien de fois apparait "Bienvenue sur FoodDiary !" dans le contenu de la page, enregistrée dans la variable $crawler.
		// Je test ensuite avec l'assertion qu'il soit bien égal à 1

		$this->assertSame(1, $crawler->filter('h1')->count());
		// Je peux aussi verifier qu'il y'ait bien un <h1> présent dans la page	
	}


	// -------------------- TESTS OF FORMS -------------------- //

	public function testAddRecord() {
		$crawler = $this->client->request('GET', '/diary/add-new-record');

		$form = $crawler->selectButton('Ajouter')->form();
		// Je selectionne le formulaire associé au bouton 'Ajouter' du DOM
		$form['food[username]'] = 'John Doe';
		$form['food[entitled]'] = 'Plat de pâtes';
		$form['food[calories]'] = 600;
		// Je remplis les champs ayant pour name="Food[X]" par des valeurs arbitraires
		$crawler = $this->client->submit($form);
		// J'envois le formulaire

		$crawler = $this->client->followRedirect();
		// Permet au client HTTP de suivre la redirection pour qu'on puisse voir le resultat du test en conditions réelles

		$this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
		// Je test qu'il y'ait bien sur la page redirigée une alert success qui indique donc que l'envois du formulaire s'est déroulé correctement
	}

	public function testAddRecordFail() {
		$crawler = $this->client->request('GET', '/diary/add-new-record');

		$form = $crawler->selectButton('Ajouter')->form();
		$form['food[username]'] = 'JD';
		$form['food[entitled]'] = 'Pizza';
		// Je fais exprès de laisser vide le champs calories
		$this->client->submit($form);

		// $crawler = $this->client->followRedirect();
		// Comme le formulaire n'est pas complet, il ne doit pas s'envoyer et il ne doit donc pas y avoir de redirection effectuée

		$this->assertSame(0, $crawler->filter('div.alert.alert-success')->count());
		// Le formulaire n'ayant pas été envoyé il ne doit y avoir aucune alert success
	}

	public function testClickList() {
		$crawler = $this->client->request('GET', '/diary/add-new-record');

		$link = $crawler->selectLink('Voir tous les rapports')->link();
		// Je selectionne le lien lié au texte 'Voir tous les rapports' présent dans le DOM

		$crawler = $this->client->click($link);
		// Je mets à jour le crawler après un click sur le lien

		$info = $crawler->filter('h1')->text();
		// Je recupère le texte du h1 présent dans le DOM après click
		// Redirection ?

		$info = $string = trim(preg_replace('/\s\s+/', ' ', $info));
		// Je retire les retour à la lignes pour faciliter la vérification

		$this->assertSame('Tous les rapports Tout ce qui a été mangé !', $info);
	}
}