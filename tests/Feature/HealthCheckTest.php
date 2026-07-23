<?php

it('returns successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
