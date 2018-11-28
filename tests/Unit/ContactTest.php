<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Contact;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

	public function testsContactsAreCreatedCorrectly()
    {
        $payload = [
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmail.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ];

        $this->json('POST', '/api/contact', $payload)
            ->assertStatus(201)
            ->assertJson(['success'=> 'Developer successffully created']);
    }

    public function testsContactsAreUpdatedCorrectly()
    {
        $contact = factory(Contact::class)->create([
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmails.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ]);

        $payload = [
            'first_name' => 'kaykay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmails.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ];

        $response = $this->json('PUT', '/api/contact/' . $contact->id, $payload)
            ->assertStatus(200)
            ->assertJson(['success'=> 'Successfully updated']);
    }

    public function testsContactsAreDeletedCorrectly()
    {
        $contact = factory(Contact::class)->create([
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmail.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ]);

        $this->json('DELETE', '/api/contact/' . $contact->id, [] )
            ->assertStatus(204);
    }

    public function testsContactsAreSingleAndCorrect()
    {
        $contact = factory(Contact::class)->create([
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmail.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ]);

        $this->json('GET', '/api/contact/' . $contact->id, [] )
            ->assertStatus(200);
    }

    public function testsContactsAreListedCorrectly()
    {
        factory(Contact::class)->create([
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmail.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ]);

        factory(Contact::class)->create([
            'first_name' => 'kay',
        	'last_name' => 'odole',
        	'email' => 'kaythinks@gmail.com',
        	'phone_no' => '08132780640',
        	'github' => '@kaythinks',
        	'category' => 'Full Stack',
        ]);

        $response = $this->json('GET', '/api/contact', [] )
            ->assertStatus(200)
            ->assertJson([
                [ 'category' => 'Full Stack', 'num_of_devs' => 2 ]
            ])
            ->assertJsonStructure([
                '*' => ['category', 'num_of_devs'],
            ]);
    }

}
