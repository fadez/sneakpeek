<?php

it('loads home page successfully', function () {
    $this->get('/')->assertOk();
});

it('loads easter egg page successfully', function () {
    $this->get('/who-is-alex-fadez')->assertStatus(418);
});
