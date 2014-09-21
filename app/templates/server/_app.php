<?php

require '../vendor/autoload.php';
require 'config/app.php';

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
    $request = json_decode($body, true);
    $errors = array();
    $validator = Validator::make(
        $request,
        array(<% _.each(entity.attrs, function (attr) {
                if (attr.required||attr.minLength||attr.maxLength||attr.min||attr.max) { 
                    var validator="required"; 
                    if (attr.attrType === "String") {
                        if (attr.minLength){validator += "|min:"+attr.minLength;}
                        if (attr.maxLength){validator += "|max:"+attr.maxLength;}                    
                    } else if (attr.attrType === "Boolean"){
                        validator += "|boolean";
                    } else if (attr.attrType === "Date"){
                        validator += "|date_format:Y-m-d";
                    } else if (attr.attrType === "Enum"){
                        validator += "|in:"+attr.enumValues;
                    } else if (attr.attrType === "Integer"){
                        validator += "|numeric";
                        if (attr.min){validator += "|min:"+attr.min;}
                        if (attr.max){validator += "|max:"+attr.max;} 
                    } else if (attr.attrType === "Float"){
                        validator += "|regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/";
                    }%>
            "<%= attr.attrName.replace(" ", "_").toLowerCase() %>" => "<%= validator %>",<% }}); %>
        )
    );
    if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
    if (count($errors) == 0) {
        $<%= entity.name %> = new <%= _.capitalize(entity.name) %>;
        <% _.each(entity.attrs, function (attr) { %>
        $<%= entity.name%>-><%= attr.attrName.replace(" ", "_").toLowerCase() %> = isset($request['<%= attr.attrName.replace(" ", "_").toLowerCase() %>'])?$request['<%= attr.attrName.replace(" ", "_").toLowerCase() %>']:NULL;<% }); %>
        $<%= entity.name %>->save();
        $app->response->status(201);
        echo $<%= entity.name %>->toJson();
    }
    else echo json_encode(array("code" =>400, "message" => $errors));
});

$app->put('/<%= baseName %>/<%= pluralize(entity.name) %>/:id', function($id) use($app) {
    $body = $app->request->getBody();
    $request = json_decode($body, true);
    $<%= entity.name %> = <%= _.capitalize(entity.name) %>::find($id);
    if (is_null($<%= entity.name %>)) {
        $app->response->status(404);
        $app->stop();
    }
    $errors = array();
    $validator = Validator::make(
        $request,
        array(<% _.each(entity.attrs, function (attr) {
                if (attr.required||attr.minLength||attr.maxLength||attr.min||attr.max) {
                    var validator="required"; 
                    if (attr.attrType === "String") {
                        if (attr.minLength){validator += "|min:"+attr.minLength;}
                        if (attr.maxLength){validator += "|max:"+attr.maxLength;}                    
                    } else if (attr.attrType === "Boolean"){
                        validator += "|boolean";
                    } else if (attr.attrType === "Date"){
                        validator += "|date_format:Y-m-d";
                    } else if (attr.attrType === "Enum"){
                        validator += "|in:"+attr.enumValues;
                    } else if (attr.attrType === "Integer"){
                        validator += "|numeric";
                        if (attr.min){validator += "|min:"+attr.min;}
                        if (attr.max){validator += "|max:"+attr.max;} 
                    } else if (attr.attrType === "Float"){
                        validator += "|regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/";
                    }%>
            "<%= attr.attrName.replace(" ", "_").toLowerCase() %>" => "<%= validator %>",<% }}); %>
        )
    );
    if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
    if (count($errors) == 0) {
        <% _.each(entity.attrs, function (attr) { %>
        $<%= entity.name%>-><%= attr.attrName.replace(" ", "_").toLowerCase() %> = isset($request['<%= attr.attrName.replace(" ", "_").toLowerCase() %>'])?$request['<%= attr.attrName.replace(" ", "_").toLowerCase() %>']:$<%= entity.name%>-><%= attr.attrName.replace(" ", "_").toLowerCase() %>;<% }); %>
        $<%= entity.name %>->save();
        $app->response->status(201);
        echo $<%= entity.name %>->toJson();
    }
    else echo json_encode(array("code" =>400, "message" => $errors));
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
