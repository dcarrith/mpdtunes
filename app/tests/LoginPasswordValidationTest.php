<?php

class LoginPasswordValidationTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function test()
	{

		// Set stage for a failed validation caused by username being left blank
		Input::replace(['password' => '']);
 
		//$this->app->instance('Post', $this->mock);
 
		$this->call('POST', '/login');
 
		// Failed validation should reload the create form
		$this->assertRedirectedToRoute('login');
 
		// The errors should be sent to the view
		$this->assertSessionHasErrors(['password']);
	}

}
