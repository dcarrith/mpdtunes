<?php

class LoginUsernameValidationTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function test()
	{
	        // Mock Validator response and make it pass
	        $response = Mockery::mock('StdClass');
	        $response->shouldReceive('fails')->once()->andReturn(true);

	        $validation = Mockery::mock('Illuminate\Validation\Validator');
	        $validation->shouldReceive('make')->once()->andReturn($response);

	        //$model = new Model([], $validation);
	        //$result = $model->validate();

        	// If validation passes, we should return true
        	// and not set any errors.
        	//$this->assertTrue($result);
        	//$this->assertNull($model->getErrors());

		// Set stage for a failed validation caused by username being left blank
		//Input::replace(['username' => '']);
 
		//Validator::shouldReceive('make')->once()->andReturn(Mockery::mock(['fails' => 'true']));

		//$this->app->instance('Post', $this->mock);
 
		$this->call('POST', '/login');
 
		// Failed validation should reload the create form
		$this->assertRedirectedToRoute('login');
 
		// The errors should be sent to the view
		$this->assertSessionHasErrors(['username']);
	}

}
