<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{

		$user = User::find(1);

		$this->be($user);

		$crawler = $this->client->request('GET', '/');

		//$this->assertRedirectedToRoute('login');

		//Auth::shouldReceive('attempt')->once()->andReturn(true);

		$this->assertTrue($this->client->getResponse()->isOk());

		//$this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));
	}

}
