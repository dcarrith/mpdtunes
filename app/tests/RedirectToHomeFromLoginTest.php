<?php

class RedirectToHomeFromLoginTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function test()
	{

		// Get a user to be
		//$user = User::find(1);

		// Be the user
		//$this->be($user);

		// Use the HttpKernel to get the login page
		//$crawler = $this->client->request('GET', '/login');

		Auth::shouldReceive('check')->once()->andReturn(true);

		// Assert that we get redirected to home since we're already logged in
		//$this->assertRedirectedToRoute('home');
        
		// Submit GET request to the LoginController@getLogin action
		$response = $this->action('GET', 'LoginController@getLogin');

		// Assert that we get redirected to home since we're already logged in
        	$this->assertRedirectedTo('/home');
		
		//$this->assertTrue($this->client->getResponse()->isOk());

		//$this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));
	}

}
