<?php

require '../vendor/autoload.php';
require 'config/app.php';

use Illuminate\Validation\Validator;

$app = new \Slim\Slim();

$app->get('/', function() use ($app) {
    readfile('index.html');
    $app->stop();
});

<% _.each(entities, function (entity) { %>
$app->get('/<%= baseName %>/<%= pluralize(entity.name) %>', function() {
    $<%= pluralize(entity.name) %> = <%= _.capitalize(entity.name) %>::all();
    echo $<%= pluralize(entity.name) %>->toJson();
});

$app->get('/<%= baseName %>/<%= pluralize(entity.name) %>/:id', function($id) use($app) {
    $<%= entity.name %> = <%= _.capitalize(entity.name) %>::find($id);
    if (is_null($<%= entity.name %>)) {
        $app->response->status(404);
        $app->stop();
    }
    echo $<%= entity.name %>->toJson();
});

$app->post('/<%= baseName %>/<%= pluralize(entity.name) %>', function() use($app) {
    $body = $app->request->getBody();
    $obj = json_decode($body);
    $<%= entity.name %> = new <%= _.capitalize(entity.name) %>;
    <% _.each(entity.attrs, function (attr) { %>
    $<%= entity.name%>-><%= attr.attrName.replace(" ", "_").toLowerCase() %> = $obj->{'<%= attr.attrName.replace(" ", "_").toLowerCase() %>'};<% }); %>
    $<%= entity.name %>->save();
    $app->response->status(201);
    echo $<%= entity.name %>->toJson();
});

$app->put('/<%= baseName %>/<%= pluralize(entity.name) %>/:id', function($id) use($app) {
    $body = $app->request->getBody();
    $obj = json_decode($body);
    $<%= entity.name %> = <%= _.capitalize(entity.name) %>::find($id);
    if (is_null($<%= entity.name %>)) {
        $app->response->status(404);
        $app->stop();
    }
    <% _.each(entity.attrs, function (attr) { %>
    $<%= entity.name%>-><%= attr.attrName.replace(" ", "_").toLowerCase() %> = $obj->{'<%= attr.attrName.replace(" ", "_").toLowerCase() %>'};<% }); %>
    $<%= entity.name %>->save();
    echo $<%= entity.name %>->toJson();
});

$app->delete('/<%= baseName %>/<%= pluralize(entity.name) %>/:id', function($id) use($app) {
    $<%= entity.name %> = <%= _.capitalize(entity.name) %>::find($id);
    if (is_null($<%= entity.name %>)) {
        $app->response->status(404);
        $app->stop();
    }
    $<%= entity.name %>->delete();
    $app->response->status(204);
});

<% }); %>

$app->run();
