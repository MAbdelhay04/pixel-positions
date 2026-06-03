<?php

it('redirects the home page to the job listings', function () {
    $response = $this->get('/');

    $response->assertRedirect('/jobs');
});
