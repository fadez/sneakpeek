<?php

it('passes the smoke test', function () {
    $urls = ['/'];

    visit($urls)->assertNoJavascriptErrors();
});
